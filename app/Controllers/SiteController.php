<?php

namespace App\Controllers;

use App\Controllers\Controller;

class SiteController extends Controller
{
    public function faqs($request, $response, $args)
    {
        return $this->view->render($response, 'faqs.twig');
    }

    public function works($request, $response, $args)
    {
        return $this->view->render($response, 'how-it-works.twig');
    }
}
