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
         if (isset($request->getUploadedFiles()['files'])) {
             $files = $request->getUploadedFiles()['files'];
         }

         if (isset($request->getUploadedFiles()['images'])) {
             $images = $request->getUploadedFiles()['images'];
         }

         $hash = bin2hex((random_bytes(32)));
         $uploadPath = __DIR__ . '/../../assets/uploads/' . $hash;

         if (!file_exists($uploadPath)) {
            mkdir($uploadPath);
            mkdir($uploadPath . '/files');
            mkdir($uploadPath . '/images');
         }

         $choices['uploadPath'] = $hash;

        foreach ($files as $file) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                $name = $file->getClientFilename();
                $file->moveTo($uploadPath . '/files/' . $name);
            }
        }

        if (isset($request->getUploadedFiles()['images'])) {
            foreach ($images as $image) {
                if ($image->getError() === UPLOAD_ERR_OK) {
                    $name = $image->getClientFilename();
                    $image->moveTo($uploadPath . '/images/' . $name);
                }
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

