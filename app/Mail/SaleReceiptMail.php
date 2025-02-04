<?php

namespace App\Mail;

use App\Models\Sales;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $venta; 

    public function __construct($venta)
    {
        $this->venta = $venta;
    }

    public function build()
    {
        return $this->view('emails.sale_receipt')
                    ->with(['sale' => $this->venta]);
    }
}
