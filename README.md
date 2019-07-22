# Payum Conekta

Payum gateway package for Conekta by [Encode Media](http://encodemedia.com.mx).

---

## Installation

```bash
$ composer require encode-media/payum-conekta
```

## Configuration

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

## Symfony integration

Register `conekta` Gateway Factory as a service

```yaml
# config/services.yaml

services:
    app.payum.conekta_factory:
        class: Payum\Core\Bridge\Symfony\Builder\GatewayFactoryBuilder
        arguments: [EncodeMedia\Payum\Conekta\ConektaGatewayFactory]
        tags:
            - { name: payum.gateway_factory_builder, factory: conekta }
```

Configure the gateway
```yaml
# config/packages/payum.yaml

payum:
    gateways:
        conekta:
            factory: conekta
            public_key: key_eYvWV7gSDkNYXsmr
            private_key: key_eYvWV7gSDkNYXsmr
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
## Developed by
[Encode Media](http://encodemedia.com.mx) diseño y desarrollo web a la medida.

## License

Library is released under the [MIT License](LICENSE).
