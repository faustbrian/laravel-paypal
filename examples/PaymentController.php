<?php

declare(strict_types=1);

/*
 * This file is part of Laravel PayPal.
 *
 * (c) Brian Faust <hello@basecode.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

use Artisanry\PayPal\Facades\PayPal;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        PayPal::setDefaultConnection('sandbox');

        PayPal::getContext()->setConfig([
            'mode'             => 'sandbox',
            'service.EndPoint' => 'https://api.sandbox.paypal.com',
            // 'http.ConnectionTimeOut' => 30,
            // 'log.LogEnabled' => true,
            // 'log.FileName' => storage_path('logs/paypal.log'),
            // 'log.LogLevel' => 'FINE'
        ]);
    }

    public function getCheckout()
    {
        $payer = PayPal::getPayer()
                       ->setPaymentMethod('paypal');

        $amount = PayPal::getAmount()
                        ->setCurrency('EUR')
                        ->setTotal(42);

        $transaction = PayPal::getTransaction()
                             ->setAmount($amount)
                             ->setDescription('What are you selling?');

        $redirectUrls = PayPal::getRedirectUrls()
                              ->setReturnUrl(action('TestController@getDone'))
                              ->setCancelUrl(action('TestController@getCancel'));

        $payment = PayPal::getPayment()
                         ->setIntent('sale')
                         ->setPayer($payer)
                         ->setRedirectUrls($redirectUrls)
                         ->setTransactions([$transaction]);

        $response = $payment->create(PayPal::getContext());

        return redirect($response->links[1]->href);
    }

    public function getDone(Request $request)
    {
        $id = $request->get('paymentId');
        $token = $request->get('token');
        $payer_id = $request->get('PayerID');

        $payment = PayPal::getById($id, $this->_apiContext);

        $paymentExecution = PayPal::PaymentExecution();

        $paymentExecution->setPayerId($payer_id);
        $executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        return view('checkout.done');
    }

    public function getCancel()
    {
        return view('checkout.cancel');
    }
}
