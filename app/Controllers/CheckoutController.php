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
        $products = $this->basket->products();
        $hash = bin2hex((random_bytes(32)));
        $string = '';

        $customer = User::firstOrCreate([
            'first_name' => $request->getParam('first_name'),
            'last_name' => $request->getParam('last_name'),
            'company' => $request->getParam('company'),
            'email' => $request->getParam('email'),
            'phone' => $request->getParam('phone'),
            'password' => password_hash('null', PASSWORD_DEFAULT),
        ]);

        $order = $customer->orders()->create([
            'hash' => $hash,
            'paid' => false,
            'total' => $this->basket->total($details['country'], $details['province']),
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

        $filesArray = [];
        $imagesArray = [];

        $zipname = __DIR__ . '/../../assets/archives/order_' . $order->id . '.zip';
        $zip = new \ZipArchive;

        $zip->open($zipname, $zip::CREATE | $zip::OVERWRITE);

        foreach ($_SESSION['choices'] as $_choice) {

            $path = realpath(__DIR__ . '/../../assets/uploads/' . $_choice['uploadPath']);

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }

        $zip->close();

        // Us email
        $this->mail->from($request->getParam('email'), $request->getParam('first_name') . ' ' . $request->getParam('last_name'))
            ->to([
                [
                'name' => 'Darkroast Digital',
                'email' => 'josh@darkroast.co',
                ]
            ])
            ->attatchments(__DIR__ . '/../../assets/archives/order_' . $order->id . '.zip')
            ->subject('An order has been placed by ' . $request->getParam('first_name') . ' ' . $request->getParam('last_name') . ' on Darkroast Express')
            ->send('mail/order.twig', compact('choices', 'details', 'order', 'filesArray', 'imagesArray'));

        // Them email
        $this->mail->from($request->getParam('email'), $request->getParam('first_name') . ' ' . $request->getParam('last_name'))
            ->to([
                [
                'name' => 'Darkroast Digital',
                'email' => 'joshstobbs@gmail.com',
                ]
            ])
            ->subject('Hey ' . $request->getParam('first_name') . '! Here\'s a summary of your Darkroast Express order.')
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

    public function clear($request, $response, $args)
    {
        unset($_SESSION['basket']);
        unset($_SESSION['choices']);

        return $response->withRedirect($this->router->pathFor('home'));
    }
}

