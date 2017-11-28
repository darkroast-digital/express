<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Mailgun\Mailgun;

class ContactController extends Controller
{
    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'contact.twig');
    }

    public function post($request, $response, $args)
    {
        $params = $request->getParams();
        $mg = Mailgun::create('key-1715c074f053673f6e3c4c79e8595390');

        $mg->messages()->send('darkroast.co', [
          'from'    => $request->getParam('email'),
          'to'      => 'support@darkroast.co',
          'subject' => 'A new message from ' . $request->getParam('name') . ' on Darkroast Express',
          'html'    => $this->view->fetch('mail/mail.twig', compact('params'))
        ]);
    }
}

