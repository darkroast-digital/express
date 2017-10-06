<?php

namespace App\Controllers;

use App\Controllers\Controller;

class ContactController extends Controller
{
    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'contact.twig');
    }

    public function post($request, $response, $args)
    {
        $params = $request->getParams();

        $this->c->mail->from($request->getParam('email'), $request->getParam('name'))
            ->to([
            [
            'name' => 'Darkroast Digital',
            'email' => 'hi@darkroast.co',
            ]
            ])
            ->subject('A new message from ' . $request->getParam('name') . ' on Darkroast Express')
            ->send('mail/mail.twig', compact('params'));
    }
}

