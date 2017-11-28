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

    public function privacy($request, $response, $args)
    {
        return $this->view->render($response, 'privacy.twig');
    }

    public function terms($request, $response, $args)
    {
        return $this->view->render($response, 'terms.twig');
    }
}
