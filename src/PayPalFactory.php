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

namespace BrianFaust\PayPal;

use InvalidArgumentException;

class PayPalFactory
{
    /**
     * Make a new PayPal client.
     *
     * @param array $config
     *
     * @return \PayPal\PayPal
     */
    public function make(array $config)
    {
        $config = $this->getConfig($config);

        return $this->getClient($config);
    }

    /**
     * Get the configuration data.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getConfig(array $config)
    {
        $keys = ['client_id', 'client_secret'];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new InvalidArgumentException("Missing configuration key [$key].");
            }
        }

        return array_only($config, ['client_id', 'client_secret']);
    }

    /**
     * Get the PayPal client.
     *
     * @param array $auth
     *
     * @return \PayPal\PayPal
     */
    protected function getClient(array $auth)
    {
        return new PayPal(
            $auth['client_id'],
            $auth['client_secret']
        );
    }
}
