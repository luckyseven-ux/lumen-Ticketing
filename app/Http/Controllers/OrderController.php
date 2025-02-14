<?php
 namespace App\Http\Controllers;
 use Midtrans\Snap;
 use Midtrans\Config;
 use App\Models\Order;
 use App\Models\Ticket;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 
 class OrderController extends Controller
 {
    public function index(){
        $user=auth()->user();
        return response()->json(Order::where('user_id',$user->id)->with('ticket')->get());
    }

    public function store(Request $request)
{
    $user = auth()->user();
    $ticket = Ticket::find($request->ticket_id);

    if (!$ticket) {
        return response()->json([
            'message' => 'Ticket Not Found !!'
        ], 404);
    }
    if ($ticket->avaiable_seats <= 0) {
        return response()->json([
            'message' => 'All tickets have been sold out'
        ], 400);
    }

    // Buat order di database
    $order = Order::create([
        'user_id' => $user->id,
        'ticket_id' => $ticket->id,
        'quantity' => $request->quantity,
        'status' => 'pending'
    ]);

    // Kurangi jumlah tiket yang tersedia
    $ticket->update(['avaiable_seats' => $ticket->avaiable_seats - 1]);

    // **Konfigurasi Midtrans**
    Config::$serverKey = env('MIDTRANS_SERVER_KEY'); // Pastikan di .env sudah ada MIDTRANS_SERVER_KEY
    Config::$isProduction = false; // Ganti ke true jika ingin mode produksi
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // **Siapkan data pembayaran**
    $transaction_details = [
        'order_id' => "ORDER-" . $order->id,
        'gross_amount' => $ticket->price * $order->quantity
    ];

    $customer_details = [
        'first_name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone ?? '08123456789'
    ];

    $transaction = [
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details
    ];

    // **Dapatkan token Midtrans**
    try {
        $snapToken = Snap::getSnapToken($transaction);
        $redirectUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

        // Simpan transaction token ke dalam order
        $order->update([
            'transaction_token' => $snapToken
        ]);

        return response()->json([
            'message' => 'Your Ticket Booked Successfully!',
            'redirect_url' => $redirectUrl
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function updatePayment(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::find($id);

        if (!$order || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found or unauthorized'], 403);
        }

        $order->update(['status' => 'paid']);
        return response()->json(['message' => 'Payment updated successfully', 'order' => $order]);
    }

    public function cancel($id)
    {
        $user = auth()->user();
        $order = Order::find($id);

        if (!$order || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found or unauthorized'], 403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Cannot cancel a paid order'], 400);
        }

        // Kembalikan tiket yang tersedia
        $order->ticket->update(['available_seats' => $order->ticket->available_seats + 1]);

        // Hapus pesanan
        $order->delete();

        return response()->json(['message' => 'Order canceled successfully']);
    }

 }