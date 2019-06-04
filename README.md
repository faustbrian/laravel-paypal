# Laravel PayPal

[![Build Status](https://img.shields.io/travis/artisanry/PayPal/master.svg?style=flat-square)](https://travis-ci.org/artisanry/PayPal)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/artisanry/paypal.svg?style=flat-square)]()
[![Latest Version](https://img.shields.io/github/release/artisanry/PayPal.svg?style=flat-square)](https://github.com/artisanry/PayPal/releases)
[![License](https://img.shields.io/packagist/l/artisanry/PayPal.svg?style=flat-square)](https://packagist.org/packages/artisanry/PayPal)

> A [PayPal](https://paypal.com) bridge for Laravel.

## Installation

Require this package, with [Composer](https://getcomposer.org/), in the root directory of your project.

```bash
$ composer require artisanry/paypal
```

## Configuration

Laravel PayPal requires connection configuration. To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="Artisanry\PayPal\PayPalServiceProvider"
```

This will create a `config/paypal.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

#### Default Connection Name

This option `default` is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is `main`.

#### PayPal Connections

This option `connections` is where each of the connections are setup for your application. Example configuration has been included, but you may add as many connections as you would like.

## Usage

#### PayPalManager

This is the class of most interest. It is bound to the ioc container as `paypal` and can be accessed using the `Facades\PayPal` facade. This class implements the ManagerInterface by extending AbstractManager. The interface and abstract class are both part of [Graham Campbell's](https://github.com/GrahamCampbell) [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at that repository. Note that the connection class returned will always be an instance of `PayPal\PayPal`.

#### Facades\PayPal

This facade will dynamically pass static method calls to the `paypal` object in the ioc container which by default is the `PayPalManager` class.

#### PayPalServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

### Examples

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `main`. After you enter your authentication details in the config file, it will just work:

```php
// You can alias this in config/app.php.
use Artisanry\PayPal\Facades\PayPal;

PayPal::getAll(['count' => 1, 'start_index' => 0]);
// We're done here - how easy was that, it just works!

PayPal::getById($paymentId);
// This example is simple and there are far more methods available.
```

The PayPal manager will behave like it is a `PayPal\PayPal`. If you want to call specific connections, you can do that with the connection method:

```php
use Artisanry\PayPal\Facades\PayPal;

// Writing this…
PayPal::connection('main')->getById($paymentId);

// …is identical to writing this
PayPal::getById($paymentId);

// and is also identical to writing this.
PayPal::connection()->getById($paymentId);

// This is because the main connection is configured to be the default.
PayPal::getDefaultConnection(); // This will return main.

// We can change the default connection.
PayPal::setDefaultConnection('alternative'); // The default is now alternative.
```

If you prefer to use dependency injection over facades like me, then you can inject the manager:

```php
use Artisanry\PayPal\PayPalManager;

class Foo
{
    protected $paypal;

    public function __construct(PayPalManager $paypal)
    {
        $this->paypal = $paypal;
    }

    public function bar($paymentId)
    {
        $this->paypal->getById($paymentId);
    }
}

App::make('Foo')->bar('my-payment-id');
```

## Documentation

There are other classes in this package that are not documented here. This is because the package is a Laravel wrapper of [the official PayPal package](https://github.com/paypal/PayPal-PHP-SDK).

## Testing

``` bash
$ phpunit
```

## Security

If you discover a security vulnerability within this package, please send an e-mail to hello@basecode.sh. All security vulnerabilities will be promptly addressed.

## Credits

- [Brian Faust](https://github.com/faustbrian)
- [All Contributors](../../contributors)

## License

[MIT](LICENSE) © [Brian Faust](https://basecode.sh)
