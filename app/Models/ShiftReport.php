<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftReport extends Model
{
    protected $fillable = [
        'user_id',
        'shift_date',
        'shift_start',
        'shift_end',
        'opening_cash',
        'closing_cash',
        'total_sales',
        'total_transactions',
        'discrepancy',
        'notes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}