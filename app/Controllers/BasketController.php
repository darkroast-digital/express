<?php

namespace App\Controllers;

use App\Controllers\Controller;

class BasketController extends Controller
{
    public function index($request, $response, $args)
    {
        $products = $this->basket->products();

        $subtotal = $this->basket->subtotal();

        return $this->view->render($response, 'Basket/index.twig', compact('products', 'subtotal'));
    }
}

