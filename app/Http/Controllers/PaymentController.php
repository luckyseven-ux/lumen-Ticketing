<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use app\Models\Ticket;
use App\Models\Order;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $ticketId = $request->input('ticket_id');
            $quantity = $request->input('quantity');

            // Pastikan tiket ada di database
            $ticket = Ticket::find($ticketId);
            if (!$ticket) {
                return response()->json(['error' => 'Ticket not found'], 404);
            }

            // Hitung total pembayaran berdasarkan harga tiket dikali quantity
            $grossAmount = $ticket->price * $quantity;

            // Generate orderId secara unik
            $orderId = 'ORD-' . time();

            // Set waktu expired (2 jam dari sekarang)
            $expiresAt = Carbon::now()->addHours(2);

            // Siapkan detail transaksi untuk Midtrans
            $transactionDetails = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'credit_card' => ['secure' => true],
            ];

            // Kirim request ke Midtrans
            $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
            $options = [
                'method' => 'POST',
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'authorization' => 'Basic ' . base64_encode(env('MIDTRANS_SERVER_KEY')),
                ],
                'body' => json_encode($transactionDetails),
            ];

            $response = file_get_contents($url, false, stream_context_create([
                'http' => $options
            ]));

            $data = json_decode($response, true);

            if (!$data || isset($data['error_messages'])) {
                return response()->json(['error' => 'Midtrans transaction failed'], 400);
            }

            // Simpan transaksi ke database
            $order = Order::create([
                'order_id' => $orderId,
                'user_id' => $userId,
                'ticket_id' => $ticketId,
                'quantity' => $quantity,
                'gross_amount' => $grossAmount,
                'transaction_token' => $data['token'],
                'status' => 'pending',
                'expires_at' => $expiresAt, // Simpan waktu kadaluarsa
            ]);

            return response()->json([
                'transactionToken' => $data['token'],
                'redirect_url' => $data['redirect_url'],
                'order' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
