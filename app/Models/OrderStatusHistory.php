<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Get the order that owns the status history
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who changed the status
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get status name in Arabic
     */
    public function getStatusNameAttribute()
    {
        $statusNames = [
            'pending' => 'في الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي'
        ];

        return $statusNames[$this->status] ?? $this->status;
    }
} 