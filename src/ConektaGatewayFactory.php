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
use EncodeMedia\Payum\Conekta\Action\OxxoPayStubAction;
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
    public const FACTORY_NAME = 'conekta';

    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'Conekta gateway',
            'payum.template.oxxo_pay' => '@PayumConekta/stub/oxxo_pay.html.twig',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.stub_oxxo_pay' => static function (ArrayObject $config) {
                return new OxxoPayStubAction($config['payum.template.oxxo_pay_stub'], $config['bussines_name']);
            },
        ]);

        if (! $config->offsetExists('payum.api')) {
            $config['payum.default_options'] = [
                'sandbox_public_key' => 'key_eYvWV7gSDkNYXsmr',
                'sandbox_private_key' => 'key_eYvWV7gSDkNYXsmr',
                'production_public_key' => '',
                'production_private_key' => '',
                'bussines_name' => '',
                'sandbox' => true,
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'sandbox_public_key',
                'sandbox_private_key',
                'production_public_key',
                'production_private_key',
            ];

            $config['payum.api'] = static function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $conektaConfig = [
                    'sandbox_private_key' => $config['sandbox_private_key'],
                    'production_private_key' => $config['production_private_key'],
                    'sandbox' => $config['sandbox'],
                ];

                return new Api($conektaConfig);
            };
        }

        $config['payum.paths'] = array_replace([
            'PayumConekta' => __DIR__.'/Resources/views',
        ], $config['payum.paths'] ?? []);
    }
}
