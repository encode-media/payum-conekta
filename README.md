# Payum Conekta

Payum gateway package for Conekta by [Encode Media](http://encodemedia.com.mx).

---

# Installation

```bash
$ composer require encode-media/payum-conekta
```

# Configuration

```php
<?php

use Payum\Core\PayumBuilder;
use Payum\Core\GatewayFactoryInterface;

$defaultConfig = [];

$payum = (new PayumBuilder)
    ->addGatewayFactory('conekta', static function(array $config, GatewayFactoryInterface $coreGatewayFactory) {
        return new \EncodeMedia\Payum\Conekta\ConektaGatewayFactory($config, $coreGatewayFactory);
    })

    ->addGateway('conekta', [
        'factory' => 'conekta',
        'sandbox' => true,
    ])

    ->getPayum()
;
```

# Usage

```php
<?php

use Payum\Core\Request\Capture;

$conektaOxxo = $payum->getGateway('conekta');

$model = new \ArrayObject([
  // ...
]);

$conektaOxxo->execute(new Capture($model));
```
## Developed by [Encode Media](http://encodemedia.com.mx)

Diseño y desarrollo web a la medida.

## License

Library is released under the [MIT License](LICENSE).
