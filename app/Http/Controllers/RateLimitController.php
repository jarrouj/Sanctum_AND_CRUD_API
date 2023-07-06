<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitController extends Controller
{
    public function sendMessage(Request $request)
    {
        $user = $request->user(); 

        if (RateLimiter::tooManyAttempts('send-message:'.$user->id, 5)) {
            $seconds = RateLimiter::availableIn('send-message:'.$user->id);
            return 'You may try again in '.$seconds.' seconds.';
        }

        RateLimiter::hit('send-message:'.$user->id);

        $message = $request->input('message');


        if ($message) {
            return response('Message sent successfully!');
        } else {
            return response('Failed to send message.', 500);
        }
    }
}
