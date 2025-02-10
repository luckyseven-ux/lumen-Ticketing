<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    public function run()
    {
        DB::table('tickets')->insert([
            [
                'title' => 'VIP Ticket',
                'description' => 'Akses eksklusif dengan tempat duduk terbaik.',
                'event_type' => 'Concert',
                'price' => 500000,
                'status' => 'available',
                'available_seats' => 50,
                'schedule' => now()->addDays(10()),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Regular Ticket',
                'description' => 'Tiket standar untuk acara.',
                'event_type' => 'Concert',
                'price' => 250000,
                'status' => 'available',
                'available_seats' => 100,
                'schedule' => now()->addDays(15()),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Economy Ticket',
                'description' => 'Pilihan hemat untuk acara.',
                'event_type' => 'Theater',
                'price' => 100000,
                'status' => 'available',
                'available_seats' => 200,
                'schedule' => now()->addDays(20()),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
