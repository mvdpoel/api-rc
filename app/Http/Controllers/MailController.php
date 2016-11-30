<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\ResponseMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * @param Request $oRequest
     */
    public function send(Request $oRequest)
    {
        /** @var Validator $oValidator */
        $oValidator = Validator::make(
            $oRequest->only(['title', 'sender', 'receiver', 'body']),
            [
                'title'     => 'required',
                'sender'    => 'required',
                'receiver'  => 'required',
                'body'      => 'required'
            ]);

        if ($oValidator->fails()) {
            // Only dump data for demo purposes
            dd($oValidator->errors()->toArray());
        }

        // Would be cleaner via a message queue
        Mail::to($oRequest->input('receiver'))
            ->send(
                new ResponseMail(
                    $oRequest->only(['title', 'sender', 'receiver', 'body'])
                )
            );


    }
}
