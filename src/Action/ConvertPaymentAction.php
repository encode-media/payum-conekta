<?php
/**
 * Created by.
 *
 * User: JCHR <car.chr@gmail.com>
 * Date: 22/07/19
 * Time: 8:40 AM
 */

declare(strict_types=1);

namespace EncodeMedia\Payum\Conekta\Action;

use EncodeMedia\Payum\Conekta\Model\PaymentMethodInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;

/**
 * Convert Payment Action
 *
 * @author JCHR <car.chr@gmail.com>
 */
class ConvertPaymentAction implements ActionInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        if (empty($payment->getNumber())) {
            throw new LogicException('The payment number field is required.');
        }

        $details->defaults([
            'currency' => 'MXN',
        ]);

        $details['metadata'] = [
            'number' => $payment->getNumber(),
        ];

        // Items
        if (! $details->offsetExists('line_items')) {
            $unitPrice = (int) ($payment->getTotalAmount() * 100);
            $details['line_items'] = [
                [
                    'name' => $payment->getDescription(),
                    'unit_price' => $unitPrice, // Centavos
                    'quantity' => 1,
                ],
            ];
        }

        if ($payment instanceof PaymentMethodInterface) {
            if (! $details->offsetExists('charges')) {
                $details['charges'] = [
                    [
                        'payment_method' => [
                            'type' => $payment->getConektaPaymentMethod(),
                        ],
                    ],
                ];
            }

            if ($payment->getExpiresAt()) {
                $details['charges'] = [
                    array_merge_recursive($details['charges'][0], [
                        'payment_method' => [
                            'expires_at' => $payment->getExpiresAt()->getTimestamp(),
                        ],
                    ]),
                ];
            }
        }

        $details->validateNotEmpty(['customer_info']);

        $request->setResult((array) $details);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() === 'array'
        ;
    }
}
