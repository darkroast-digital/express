<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Option;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index($request, $response, $args)
    {
        $product = Product::where('slug', $args['slug'])->first();

        if (isset($product->details)) {
            $details = $product->details;
        }

        if (isset($product->options)) {
            $options = $product->options;
        }

        if (isset($product->images)) {
            $images = $product->images;
        }

        return $this->view->render($response, 'Product/index.twig', compact('product'));
    }

    public function store($request, $response, $args)
    {
        if (!isset($_SESSION['choices'])) {
            $_SESSION['choices'] = [];
         };

         $choices = $request->getParams();

         if (isset($_FILES['files'])) {
             if (($_FILES['files']['tmp_name'][0]) != '') {
                 $choices = array_merge($choices, $_FILES['files']);
             }
         }

         if (isset($_FILES['images'])) {
             if (($_FILES['images']['tmp_name'][0]) != '') {
                 $choices = array_merge($choices, $_FILES['images']);
             }
        }
         
         array_push($_SESSION['choices'], $choices);

        $product = Product::where('slug', $args['slug'])->first();
        $params = $request->getParams();

        $this->basket->add($product, $params);

        return $response->withRedirect($this->router->pathFor('basket'));
    }

    public function remove($request, $response, $args)
    {
        $product = Product::where('slug', $args['slug'])->first();

        $this->basket->remove($product);
    }
}

