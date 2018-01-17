<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Mail\Mailable;
use Mail;
use App\Mail\Blast_email;
use App\Models\Email;

class Send_email_blast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 1;
    public $user,$text,$subject;
    public function __construct(Email $u,$text,$subject)
    {
        $this->user=$u;
        $this->text=$text;
        $this->subject=$subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $email = new Blast_email($this->user,$this->text,$this->subject);
        // Mail::to($this->user->email)->send($email);

        // echo $this->user->email;
        // $mailer->to($this->user->email)->send($email);
        $subject=$this->subject;
        Mail::send('emails.blast_email', ['user'=>$this->user,'text'=>$this->text], function ($message) use ($subject){
            $message->from('salim@indikraf.com');
            $message->subject($subject);
            $message->to($this->user->email);
        });
    }
}
