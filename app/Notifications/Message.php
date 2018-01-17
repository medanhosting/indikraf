<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Mail;
use App\Mail\SendMessageToAdmin as Mailable;

class Message extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $name,$email,$title,$message;
    public function __construct($name,$email,$title,$message)
    {
        $this->name=$name;
        $this->email=$email;
        $this->title=$title;
        $this->message=$message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $from=$this->email;
        $subject=$this->title;
        $user=\App\User::where('role_id',1)->first();
        $sendto=$user->email;
        $message=$this->message;
        // return Mail::send('emails.blast_email', ['user'=>$user,'text'=>$this->message], function ($message) use ($subject,$from,$user){
        //     $message->from($from);
        //     $message->subject($subject);
        //     $message->to($user->email);
        // });

        return (new Mailable($user,$message,$from,$subject))->to($sendto);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
          'name'=>$this->name,
          'email'=>$this->email,
          'title'=>$this->title,
          'message'=>$this->message
        ];
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //       'name'=>$this->name,
    //       'email'=>$this->email,
    //       'title'=>$this->title,
    //       'message'=>$this->message
    //     ]);
    // }
}
