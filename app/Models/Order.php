<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $table = 'orders';
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->transaction_token = Str::uuid(); // Generate UUID otomatis
        });
    }

    protected $fillable = ['user_id', 'ticket_id', 'quantity', 'status', 'price','transaction_token'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function getGrossAttribute()
    {
        return $this->ticket ? $this->ticket->price * $this->quantity : 0;
    }
    protected $casts = [
        'expires_at' => 'datetime', // Pastikan ini ada agar bisa dibandingkan dengan Carbon
    ];
    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }
}
