<?php

namespace App\Controllers;

use App\Controllers\Controller;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'home.twig');
    }
}
