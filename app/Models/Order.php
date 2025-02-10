<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user_id', 'ticket_id', 'quantity', 'status'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class,'ticket_id');
    }
}
