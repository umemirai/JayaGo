<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'reference',
        'notes',
        'mutation_date',
    ];

    protected $casts = [
        'mutation_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}