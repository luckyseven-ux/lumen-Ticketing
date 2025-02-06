<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets'; // Nama tabel di database
    protected $fillable = ['title', 'description', 'status']; // Kolom yang bisa diisi
    public $timestamps = true; // Aktifkan timestamps (created_at dan updated_at)
}