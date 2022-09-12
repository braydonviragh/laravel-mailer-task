<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send ( Request $request ) { 
        $array = [];

        $array = $request['body'];
        $entityBody = file_get_contents('php://input');
        array_push($array, $entityBody);
        $emailDestination = $array['sendTo'];
        $text = $array['joke'];
        $subject = 'Your Chuck Norris Joke';

        Mail::send('emails.emailTemplate', $array,
            function ($message) use ($array){ 
                $message->to($array['sendTo'], $array['senderName'])
                ->subject('Your Chuck Norris Joke');
            });
        }
}
