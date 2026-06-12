<?php
namespace App\Notifications;

use App\Models\Product;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LowStockNotification extends Notification
{
    public function __construct(public Product $product) {}

    public function via($notifiable): array
    {
        return ['database']; // simpan di DB, bisa tambah 'mail' jika perlu
    }

    public function toDatabase($notifiable): array
    {
        return [
            'product_id'   => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock'=> $this->product->stock,
            'min_stock'    => $this->product->stock_minimum,
            'message'      => "Stok {$this->product->name} rendah: {$this->product->stock} {$this->product->unit} (min: {$this->product->stock_minimum})",
        ];
    }
}