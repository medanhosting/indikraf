<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Email;

class Blast_email extends Mailable
{
    public $user,$text;
    public $subject;
    use Queueable, SerializesModels;
    public function __construct(Email $user,$text,$subject)
    {
      $this->user=$user;
      $this->text=$text;
      $this->subject=$subject;
    }

    public function build()
    {
        return $this->from('salim@indikraf.com')
                    ->subject($this->subject)
                    ->view('emails.blast_email',['user'=>$this->user,'text'=>$this->text]);
    }

}
