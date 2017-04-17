<?php

namespace App\Http\Controllers;

use Mail;
use Session;
use App\Category;
use Illuminate\Http\Request;

class PagesController extends Controller {

    /**
     * URL: /contact/send (POST)
     *
     * @param Request $request
     * @return array
     */
    public function contactSend(Request $request)
    {
        // Check if its our form
        if ( Session::token() !== $request->input( '_token' ) ) {
            return Response::json( array(
                'msg' => 'Unauthorized attempt to send email'
            ) );
        }

        $name  = $request->input( 'name' );
        $email = $request->input( 'email' );
        $phone = $request->input( 'phone' );
        $body  = $request->input( 'message' );

        // Only allow sending email if the site is not demo
        if (!env('SHOP_DEMO')) {
            Mail::send('emails.contact',
                [
                    'name'  => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'body'  => $body
                ],
                function($message) use ($name, $email)
                {
                    $message->from($email, $name)
                        ->to(env('OWNER_EMAIL'), env('OWNER_NAME'))
                        ->subject('Demo Shop - Contact Form');
                });
        }

        $response = [
            'status' => 'success',
            'msg' => 'Email sent successfully',
        ];

        return $response;
    }

}
