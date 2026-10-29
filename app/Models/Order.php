<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'subtotal',
        'shipping_cost',
        'grand_total',
        'status',
        'payment_status',
        'payment_type',
        'snap_token',
        'midtrans_transaction_id',
        'shipping_courier',
        'tracking_number',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'customer_notes',
        'paid_at',
        'shipped_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Status helpers
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-100 text-amber-700 border-amber-300'],
            'paid' => ['label' => 'Sudah Dibayar', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-300'],
            'processing' => ['label' => 'Diproses', 'class' => 'bg-blue-100 text-blue-700 border-blue-300'],
            'shipped' => ['label' => 'Dikirim', 'class' => 'bg-indigo-100 text-indigo-700 border-indigo-300'],
            'completed' => ['label' => 'Selesai', 'class' => 'bg-teal-100 text-teal-700 border-teal-300'],
            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-rose-100 text-rose-700 border-rose-300'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-gray-100 text-gray-700 border-gray-300'],
        };
    }
}
