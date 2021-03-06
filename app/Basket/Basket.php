<?php

namespace App\Basket;

use App\Models\Product;
use App\Models\Option;

class Basket
{
    protected $basket;
    protected $products;

    public function __construct($basket = 'basket', $products = [])
    {
        if (!isset($_SESSION[$basket])) {
            $_SESSION[$basket] = [];
        };

        $this->basket = $basket;
        $this->products = $products;
    }

    public function add(Product $product, $params)
    {
        $variants = [];
        $options = $product->options;

        if (isset($params['option'])) {
            $extras = $params['option'];

            foreach ($extras as $extra) {

                foreach ($options  as $option) {

                    if ($option->slug == $extra) {
                        array_push($variants, $option);
                    }
                }
            }
        }

        $optionsArray = [];

        foreach ($variants as $variant) {
            array_push($optionsArray,
                [
                    'name' => $variant->name,
                    'price' => $variant->price,
                ]
            );
        };

        $basket = [
            'product' => $product->name,
            'price' => $product->price,
            'options' => $optionsArray,
        ];

        $_SESSION[$this->basket][] = $basket;
    }

    public function products()
    {
        $basket = $_SESSION[$this->basket];

        $options = [];

        foreach ($basket as $product) {

            $price = $product['price'];

            if (count($product['options']) > 0 ) {

                foreach ($product['options'] as $option) {
                    $price = $price + $option['price'];

                    $options[] = $option;  
                }

                $item = [
                    'name' => $product['product'],
                    'price' => $price,
                    'options' => $options
                ];

            } else {

                $item = [
                    'name' => $product['product'],
                    'price' => $price,
                    'options' => null
                ];

            }

            array_push($this->products, $item);
        }
        
        return $this->products;
    }

    public function all()
    {
        $ids = [];
        $items = [];

        foreach ($_SESSION[$this->basket] as $product) {
            $item = Product::where('name', $product['product'])->first();

            $items[] = $item;
        }

        return $items;
    }

    public function count()
    {
        return count($_SESSION[$this->basket]);
    }

    public function subtotal()
    {
        $price = 0;

        foreach ($this->products as $product) {
            $price = $price + $product['price'];
        }

        return $price;
    }

    public function tax($country, $territory)
    {
        $tax = new \App\Tax\Tax($country, $territory);
        $subtotal = $this->subtotal();

        $taxes = $tax->calculateTax($subtotal);

        return $taxes;
    }

    public function total($country, $territory)
    {
        $total = $this->subtotal() + $this->tax($country, $territory);

        return $total;
    }

    public function remove(Product $product)
    {
        dump(array_search('', $_SESSION['basket']));
        dump($_SESSION['basket']);

        foreach ($this->all() as $item) {
            if ($product->name == $item['product']) {
                
            }
        }
    }
}
