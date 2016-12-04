<?php

/*
 * This file is part of Laravel PayPal.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * This file is part of Laravel PayPal.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\PayPal;

use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;

class PayPal
{
    /**
     * @var PayPal\Rest\ApiContext
     */
    private $apiContext;

    /**
     * @param string $clientId
     * @param string $clientSecret
     *
     * @return PayPal\Rest\ApiContext
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->setContext($this->apiContext($clientId, $clientSecret));
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $requestId
     *
     * @return PayPal\Rest\ApiContext
     */
    public function apiContext($clientId = null, $clientSecret = null, $requestId = null): ApiContext
    {
        return new ApiContext($this->getOAuthTokenCredential($clientId, $clientSecret), $requestId);
    }

    /**
     * @param $paymentId
     * @param $apiContext
     *
     * @return PayPal\Api\Payment
     */
    public function getById($paymentId, ApiContext $apiContext = null)
    {
        return Payment::get($paymentId, $apiContext ?? $this->apiContext);
    }

    /**
     * @param array                  $parameters
     * @param \PayPal\Api\ApiContext $apiContext
     *
     * @return \PayPal\Api\Payment
     */
    public function getAll($parameters, ApiContext $apiContext = null)
    {
        return Payment::all($parameters, $apiContext ?? $this->apiContext);
    }

    /**
     * @param \PayPal\Api\ApiContext $apiContext
     */
    public function setContext($apiContext): void
    {
        $this->apiContext = $apiContext;
    }

    /**
     * @return \PayPal\Api\ApiContext
     */
    public function getContext(): ApiContext
    {
        return $this->apiContext;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        $sdkClass = substr($method, 3);

        if (class_exists($apiClass = "PayPal\\Api\\$sdkClass")) {
            return new $apiClass();
        }

        if (class_exists($authClass = "PayPal\\Auth\\$sdkClass")) {
            return new $authClass($arguments[0], $arguments[1]);
        }

        return call_user_func_array([$this, $method], $arguments);
    }
}
