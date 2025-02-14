<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire';
    protected $description = 'Set orders to expired if not paid within 2 hours';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expiredOrders = Order::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours(2))
            ->update(['status' => 'expired']);

        $this->info("Expired $expiredOrders pending orders.");
    }
}
