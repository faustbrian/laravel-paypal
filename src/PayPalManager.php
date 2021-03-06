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

namespace Artisanry\PayPal;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

class PayPalManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \Artisanry\PayPal\PayPalFactory
     */
    private $factory;

    /**
     * Create a new PayPal manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Artisanry\PayPal\PayPalFactory         $factory
     */
    public function __construct(Repository $config, PayPalFactory $factory)
    {
        parent::__construct($config);

        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return mixed
     */
    protected function createConnection(array $config): PayPal
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName(): string
    {
        return 'laravel-paypal';
    }

    /**
     * Get the factory instance.
     *
     * @return \Artisanry\PayPal\PayPalFactory
     */
    public function getFactory(): PayPalFactory
    {
        return $this->factory;
    }
}
