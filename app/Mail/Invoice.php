<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

// Model
use App\User;
use App\Models\Transaction;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $transaction,$user;
    public function __construct(User $user,Transaction $transaction)
    {
        $this->user=$user;
        $this->transaction=$transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('salim@indikraf.com')
                    ->subject('order: '.$this->transaction->order_id)
                    ->view('emails.invoice_email',['transaction'=>$this->transaction,'user'=>$this->user]);
    }
}
