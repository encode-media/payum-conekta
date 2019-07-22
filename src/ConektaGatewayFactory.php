<?php
/**
 * Created by.
 *
 * User: JCHR <car.chr@gmail.com>
 * Date: 22/07/19
 * Time: 8:40 AM
 */

declare(strict_types=1);

namespace EncodeMedia\Payum\Conekta;

use EncodeMedia\Payum\Conekta\Action\AuthorizeAction;
use EncodeMedia\Payum\Conekta\Action\CancelAction;
use EncodeMedia\Payum\Conekta\Action\CaptureAction;
use EncodeMedia\Payum\Conekta\Action\ConvertPaymentAction;
use EncodeMedia\Payum\Conekta\Action\NotifyAction;
use EncodeMedia\Payum\Conekta\Action\RefundAction;
use EncodeMedia\Payum\Conekta\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

/**
 * Conekta Gateway Factory
 *
 * @author JCHR <car.chr@gmail.com>
 */
class ConektaGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'conekta',
            'payum.factory_title' => 'Conekta gateway',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);

        if (! $config->offsetExists('payum.api')) {
            $config['payum.default_options'] = [
                'public_key' => '',
                'secret_key' => '',
                'sandbox' => true,
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['public_key', 'secret_key'];

            $config['payum.api'] = static function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config);
            };
        }
    }
}
