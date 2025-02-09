<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketController extends Controller
{
    /**
     * Menampilkan semua tiket.
     */
    public function index()
    {
        // Ambil semua tiket dari database
        $tickets = Ticket::with('user')->get();

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Daftar tiket berhasil diambil.',
            'data' => $tickets,
        ], 200);
    }

    /**
     * Menampilkan detail tiket berdasarkan ID.
     */
    public function show($id)
    {
        // Cari tiket berdasarkan ID
        $ticket = Ticket::with('user')->find($id);

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
    /**
     * Membuat tiket baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'event_type' => 'nullable|string',
            'status' => 'required|string',
            'schedule' => 'required|date',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Simpan data ke database
        $ticket = new Ticket();
        $ticket->user_id = JWTAuth::user()->id; // ID pengguna yang login
        $ticket->title = $request->input('title');
        $ticket->description = $request->input('description');
        $ticket->event_type = $request->input('event_type');
        $ticket->status = $request->input('status', 'open'); // Default status: 'open'
        $ticket->save();

        // Kembalikan respons sukses
        return response()->json([
            'message' => 'Tiket berhasil dibuat!',
            'data' => $ticket,
        ], 201);
    }

    /**
     * Memperbarui tiket berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        // Cari tiket berdasarkan ID
        $ticket = Ticket::find($id);

        // Jika tiket tidak ditemukan, kembalikan error 404
        if (!$ticket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Perbarui data tiket
        $ticket->title = $request->input('title', $ticket->title);
        $ticket->description = $request->input('description', $ticket->description);
        $ticket->event_type = $request->input('event_type', $ticket->event_type);
        $ticket->status = $request->input('status', $ticket->status);
        $ticket->save();

        // Kembalikan respons sukses
        return response()->json([
            'message' => 'Tiket berhasil diperbarui.',
            'data' => $ticket,
        ], 200);
    }

    /**
     * Menghapus tiket berdasarkan ID.
     */
    public function destroy($id)
    {
        // Cari tiket berdasarkan ID
        $ticket = Ticket::find($id);

        // Jika tiket tidak ditemukan, kembalikan error 404
        if (!$ticket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        // Hapus tiket
        $ticket->delete();

        // Kembalikan respons sukses
        return response()->json([
            'message' => 'Tiket berhasil dihapus.',
        ], 200);
    }
}
