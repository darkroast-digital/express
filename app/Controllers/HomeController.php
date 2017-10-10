<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        $products = Product::all()->shuffle();

        return $this->view->render($response, 'category/index.twig', compact('products'));
    }
}
