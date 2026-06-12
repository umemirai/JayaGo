<?php
namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\ShiftReport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function open()
{
    $activeShift = ShiftReport::where('user_id', Auth::id())
                              ->where('status', 'open')
                              ->whereDate('shift_date', today())
                              ->first();

    if ($activeShift) {
        return redirect()->route('kasir.pos'); // ← redirect ke POS kalau shift sudah ada
    }

    return view('kasir.shift.open');
}

    public function storeOpen(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
        ]);

        ShiftReport::create([
            'user_id'      => Auth::id(),
            'shift_date'   => today(),
            'shift_start'  => now()->format('H:i:s'),
            'opening_cash' => $request->opening_cash,
            'status'       => 'open',
        ]);

        return redirect()->route('kasir.pos');
    }

    public function close()
{
    $shift = ShiftReport::where('user_id', Auth::id())
                        ->where('status', 'open')
                        ->whereDate('shift_date', today())
                        ->first();

    // Kalau tidak ada shift aktif, redirect ke POS
    if (!$shift) {
        return redirect('/kasir/pos');
    }

    $salesData = Transaction::where('user_id', Auth::id())
        ->whereBetween('transaction_date', [
            now()->setTimeFromTimeString($shift->shift_start),
            now()
        ])
        ->selectRaw('
            COUNT(*) as total_transactions,
            SUM(total) as total_sales,
            SUM(CASE WHEN payment_method = "cash" THEN total ELSE 0 END) as cash_sales
        ')
        ->first();

    return view('kasir.shift.close', compact('shift', 'salesData'));
}

    public function storeClose(Request $request)
    {
        $request->validate([
            'closing_cash' => 'required|numeric|min:0',
            'notes'        => 'nullable|string|max:500',
        ]);

        $shift = ShiftReport::where('user_id', Auth::id())
                            ->where('status', 'open')
                            ->whereDate('shift_date', today())
                            ->firstOrFail();

        $totalSales = Transaction::where('user_id', Auth::id())
            ->whereBetween('transaction_date', [
                now()->setTimeFromTimeString($shift->shift_start), now()
            ])
            ->sum('total');

        $totalTransactions = Transaction::where('user_id', Auth::id())
            ->whereBetween('transaction_date', [
                now()->setTimeFromTimeString($shift->shift_start), now()
            ])
            ->count();

        $expectedCash = $shift->opening_cash + $totalSales;
        $discrepancy  = $request->closing_cash - $expectedCash;

        $shift->update([
            'shift_end'          => now()->format('H:i:s'),
            'closing_cash'       => $request->closing_cash,
            'total_sales'        => $totalSales,
            'total_transactions' => $totalTransactions,
            'discrepancy'        => $discrepancy,
            'notes'              => $request->notes,
            'status'             => 'closed',
        ]);

        return redirect()->route('kasir.shift.report');
    }
}