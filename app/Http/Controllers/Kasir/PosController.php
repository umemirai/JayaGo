<?php
namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class PosController extends Controller
{
    public function struk($id)
    {
        $transaction = Transaction::with(['items', 'cashier'])->findOrFail($id);
        return view('kasir.struk', compact('transaction'));
    }
}