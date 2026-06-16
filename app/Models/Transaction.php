<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'total',
        'discount',

        'subtotal',
        'paid',
        'change',
        'payment_method',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'subtotal'            => 'decimal:2',
        'paid'             => 'decimal:2',
        'change'           => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias agar query yang menggunakan relasi 'user' (misal di LaporanController) tetap berfungsi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateInvoice(): string
    {
        $prefix = 'INV-' . date('Ymd');
        $last   = self::where('invoice_number', 'like', $prefix . '%')
                      ->latest()->first();
        $seq    = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}