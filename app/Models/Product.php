<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode', 'name', 'category', 'price', 'cost_price',
        'stock', 'stock_minimum', 'unit', 'supplier',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_minimum;
    }

    public function mutations()
    {
        return $this->hasMany(StockMutation::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Scope pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%")
                     ->orWhere('barcode', 'like', "%{$keyword}%");
    }
}