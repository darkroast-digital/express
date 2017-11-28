<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Braintree_Transaction;
use Mailgun\Mailgun;

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

        $_SESSION['details'] = $details;

        $products = $this->basket->products();
        $hash = bin2hex((random_bytes(32)));
        $string = '';

        $_SESSION['products'] = $products;

        $_SESSION['details']['hash'] = $hash;

        $customer = User::firstOrCreate([
            'first_name' => $request->getParam('first_name'),
            'last_name' => $request->getParam('last_name'),
            'company' => $request->getParam('company'),
            'email' => $request->getParam('email'),
            'phone' => $request->getParam('phone'),
            'password' => password_hash('null', PASSWORD_DEFAULT),
        ]);

        if (isset($details['province'])) {
            $totalPrice = $this->basket->total($details['country'], $details['province']);
        } else {
            $totalPrice = $this->basket->total($details['country'], $details['state']);
        }

        $order = $customer->orders()->create([
            'hash' => $hash,
            'paid' => false,
            'total' => $totalPrice,
        ]);

        $order->products()->saveMany(
            $this->basket->all()
        );

        if (isset($details['province'])) {
            $tax = new \App\Tax\Tax($request->getParam('country'), $request->getParam('province'));
        } elseif (isset($details['state'])) {
            $tax = new \App\Tax\Tax($request->getParam('country'), $request->getParam('state'));
        }

        $braintree_config = new \Braintree_Configuration;

        $braintree_config::environment(getenv('BRAINTREE_ENVIRONMENT'));
        $braintree_config::merchantId(getenv('BRAINTREE_MERCHANTID'));
        $braintree_config::publicKey(getenv('BRAINTREE_PUBLICKEY'));
        $braintree_config::privateKey(getenv('BRAINTREE_PRIVATEKY'));

        $result = Braintree_Transaction::sale([
          'amount' => $order->total,
          'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
          'options' => [
          'submitForSettlement' => true,
          ]
        ]);

        //check for a failed transaction

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

        $filesExist = false;

        foreach ($_SESSION['choices'] as $choice) {

            $basePath = __DIR__ . '/../../assets/uploads/' . $choice['uploadPath'];

            $files = glob($basePath . '/files/*');
            $images = glob($basePath . '/images/*');

            if (count($files) != 0) {
                $filesExist = true;
            } 

            if (count($images) != 0) {
                $filesExist = true;
            }
        }

        if ($filesExist == true) {
        
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

        }

        $mg = Mailgun::create('key-1715c074f053673f6e3c4c79e8595390');

        $mg->messages()->send('darkroast.co', [
          'from'    => 'hi@darkroast.co',
          'to'      => 'hi@darkroast.co',
          'subject' => 'An order has been placed by ' . $request->getParam('first_name') . ' ' . $request->getParam('last_name') . ' on Darkroast Express',
          'html'    => $this->view->fetch('mail/order.twig', compact('choices', 'details', 'order', 'filesArray', 'imagesArray')),
          'attachment' => [
            ['filePath' => __DIR__ . '/../../assets/archives/order_' . $order->id . '.zip', 'filename' => $order->id . '.zip']
          ]
        ]);
      
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
        $details = $_SESSION['details'];
        $order = Order::where('hash', $args['hash'])->first();
        $details = $_SESSION['details'];
        $products = $_SESSION['products'];

        $subtotal = 0;

        foreach ($products as $product) {
            $subtotal = $subtotal + $product['price'];
        }

        if (isset($details['province'])) {
            $tax = new \App\Tax\Tax($details['country'], $details['province']);
        } elseif (isset($details['state'])) {
            $tax = new \App\Tax\Tax($details['country'], $details['state']);
        }

        $taxes = $tax->calculateTax($subtotal);
        $total = $subtotal + $taxes;

        // Them email
        // $this->mail->from('hi@darkroast.co', 'Darkroast Digital')
        //     ->to([
        //         [
        //             'name' => $details['first_name'] . ' ' . $details['last_name'],
        //             'email' => $details['email'],
        //         ]
        //     ])
        //     ->subject('Hey ' . $details['first_name'] . '! Here\'s a summary of your Darkroast Express order.')
        //     ->send('mail/summary.twig', compact('details', 'order'));

        $mg = Mailgun::create('key-1715c074f053673f6e3c4c79e8595390');

        $mg->messages()->send('darkroast.co', [
          'from'    => 'hi@darkroast.co',
          'to'      => $details['email'],
          'subject' => 'Hey ' . $details['first_name'] . '! Here\'s a summary of your Darkroast Express order.',
          'html'    => $this->view->fetch('mail/summary.twig', compact('details', 'order'))
        ]);

        return $this->view->render($response, 'Checkout/order.twig', compact('products', 'order', 'total', 'taxes'));
    }

    public function clear($request, $response, $args)
    {
        unset($_SESSION['basket']);
        unset($_SESSION['choices']);

        return $response->withRedirect($this->router->pathFor('home'));
    }
}

