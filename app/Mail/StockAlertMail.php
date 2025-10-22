<?php
namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StockAlert;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $type;
    public $messageText;

    public function __construct(Product $product, string $type, string $messageText)
    {
        $this->product = $alert->product;
        $this->type = $alert->type;
        $this->messageText = $alert->message;
    }

    public function build()
    {
        $title = "Stock alert: {$this->product->name} ({$this->type})";

        $productUrl = url('/inventory/stock?search=' . urlencode($this->product->name));

        $bodyHtml = '<p>' . e($this->messageText) . '</p>'
                  . '<p><strong>Product:</strong> ' . e($this->product->name) . ' (SKU: ' . e($this->product->sku ?? 'N/A') . ')</p>'
                  . '<p><strong>Current stock:</strong> ' . e($this->product->current_stock ?? 'N/A') . '</p>'
                  . '<p><a class="btn" href="' . $productUrl . '">View product</a></p>';

        return $this->subject($title)
                    ->view('emails.formatted')
                    ->with([
                        'title' => $title,
                        'bodyHtml' => $bodyHtml,
                    ]);
    }
}
