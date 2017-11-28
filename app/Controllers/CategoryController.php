<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index($request, $response, $args)
    {

        $category = $args['slug'];
        $products = Product::where('category', $category)->get();

        if ($category == 'all') {
            $products = Product::all()->shuffle();
        }
        
        return $this->view->render($response, 'category/index.twig', compact('products'));
    }
}

