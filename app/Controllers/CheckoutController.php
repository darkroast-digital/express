<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Braintree_Transaction;

class CheckoutController extends Controller
{
    public function index($request, $response, $args)
    {
        $products = $this->basket->products();

        return $this->view->render($response, 'Checkout/index.twig');
    }

    public function order($request, $response, $args)
    {
        $details = $request->getParams();

        $string = '';

        $customer = User::firstOrCreate([
            'first_name' => $request->getParam('first_name'),
            'last_name' => $request->getParam('last_name'),
            'company' => $request->getParam('company'),
            'email' => $request->getParam('email'),
            'phone' => $request->getParam('phone'),
            'password' => password_hash('null', PASSWORD_DEFAULT),
        ]);

        $products = $this->basket->products();
        $hash = bin2hex((random_bytes(32)));

        $order = $customer->orders()->create([
            'hash' => $hash,
            'paid' => false,
            'total' => $this->basket->subTotal(),
        ]);

        $order->products()->saveMany(
            $this->basket->all()
        );

        $braintree_config = new \Braintree_Configuration;

        $braintree_config::environment('sandbox');
        $braintree_config::merchantId('rqt73v4n4kfhyzyq');
        $braintree_config::publicKey('prmwc62q9y2n4xqt');
        $braintree_config::privateKey('3bb4fecabc388e29d3f45c82f6eb736a');

        $result = Braintree_Transaction::sale([
          'amount' => $this->basket->subTotal(),
          'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
          'options' => [
          'submitForSettlement' => true,
          ]
        ]);

        // check for a failed transaction

        if ($result->success == false) {
            return $response->withRedirect($this->router->pathFor('basket'));
        }

        foreach ($_SESSION['choices'] as $choice) {

            foreach ($choice as $key => $value) {

                if (!is_array($value)) {
                    if ($key === 'product') {
                        $string .= '<li><h4>' . ucfirst(str_replace('-', ' ', $key)) . ' ' . $value . '</h4></li>';
                    }

                    $string .= '<li><strong>' . ucfirst(str_replace('-', ' ', $key)) . '</strong>: ' . $value . '</li>';
                }

                if (is_array($value)) {

                    $string .= '<li><strong>' . ucfirst(str_replace('-', ' ', $key)) . '</strong><ul>';
                        foreach ($value as $k => $v) {
                            $string .= '<li>' . $v . '</li>';
                        }
                    $string .= '</ul></li>';
                }
            }
        }

        $choices = $string;

        // Us email
        $this->mail->from($request->getParam('email'), $request->getParam('name'))
            ->to([
                [
                'name' => 'Darkroast Digital',
                'email' => 'joshstobbs@gmail.com',
                ]
            ])
            ->subject('A new message from ' . $request->getParam('name') . ' on Darkroast Express')
            ->send('mail/order.twig', compact('choices', 'details', 'order'));

        // Them email
        $this->mail->from($request->getParam('email'), $request->getParam('name'))
            ->to([
                [
                'name' => 'Darkroast Digital',
                'email' => 'joshstobbs@gmail.com',
                ]
            ])
            ->subject('A new message from ' . $request->getParam('name') . ' on Darkroast Express')
            ->send('mail/order.twig', compact('choices', 'details', 'summary'));
      
        if (!$request->getParam('payment_method_nonce')) {
            return $response->withRedirect($this->router->pathFor('basket'));
        }

        unset($_SESSION['basket']);
        unset($_SESSION['choices']);

        // Redirect to receipt page
        return $response->withRedirect($this->router->pathFor('checkout.order.summary', [
            'hash' => $hash,
        ]));
    }

    public function showOrder($request, $response, $args)
    {
        $order = Order::where('hash', $args['hash'])->first();

        $total = 0;

        foreach ($order->products as $product) {
            $total = $total + $product->price;
        }

        return $this->view->render($response, 'Checkout/order.twig', compact('order', 'total'));
    }
}

