<?php
namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLowStockNotification implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public Product $product) {}

    public function handle(): void
    {
        // Kirim notifikasi ke semua user role pegawai_gudang
        $gudangUsers = User::role('pegawai_gudang')->get();

        foreach ($gudangUsers as $user) {
            $user->notify(new LowStockNotification($this->product));
        }
    }
}