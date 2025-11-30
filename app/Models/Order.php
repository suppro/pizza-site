<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'Order';
    
    protected $fillable = [
        'user_id', 'status_id', 'delivery_address', 'comment', 'total_price'
    ];
    
    public $timestamps = false;
    
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}