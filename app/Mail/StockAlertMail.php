<?php
namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $type;
    public $messageText;

    public function __construct(Product $product, string $type, string $messageText)
    {
        $this->product = $product;
        $this->type = $type;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject("Stock alert: {$this->product->name} ({$this->type})")
            ->view('emails.stock_alert')
            ->with([
                'product' => $this->product,
                'type' => $this->type,
                'messageText' => $this->messageText,
            ]);
    }
}
