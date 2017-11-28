<?php

namespace App\Controllers;

use App\Controllers\Controller;


class BraintreeController extends Controller
{
    public function token($request, $response, $args)
    {
        return $response->withJson([
            'token' => $this->braintree::generate(),
        ]);
    }
}
