<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket; // Pastikan model Ticket sudah dibuat

class TicketController extends Controller
{
    /**
     * Menyimpan tiket baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|string|max:50',
        ]);

        // Simpan data ke database
        $ticket = new Ticket();
        $ticket->title = $request->input('title');
        $ticket->description = $request->input('description');
        $ticket->status = $request->input('status', 'open'); // Default status: 'open'
        $ticket->save();

        // Kembalikan respons sukses
        return response()->json([
            'message' => 'Tiket berhasil dibuat!',
            'data' => $ticket,
        ], 201);
    }

    public function index()
    {
        // Ambil semua tiket dari database
        $tickets = Ticket::all();

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Daftar tiket berhasil diambil.',
            'data' => $tickets,
        ], 200);
    }
    public function show($id)
    {
        // Cari tiket berdasarkan ID
        $ticket = Ticket::find($id);

        // Jika tiket tidak ditemukan, kembalikan error 404
        if (!$ticket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Detail tiket berhasil diambil.',
            'data' => $ticket,
        ], 200);
    }
}
