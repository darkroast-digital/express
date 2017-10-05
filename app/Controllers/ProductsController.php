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
        $details = $product->details;

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

