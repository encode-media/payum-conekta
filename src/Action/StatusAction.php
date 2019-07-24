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

use EncodeMedia\Payum\Conekta\Api;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

/**
 * Status Action
 *
 * @author JCHR <car.chr@gmail.com>
 */
class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if ($model->offsetExists('error')) {
            $request->markFailed();

            return;
        }

        if (! $model->offsetExists('payment_status')) {
            $request->markNew();

            return;
        }

        switch ($model->get('payment_status')) {
            case Api::STATUS_PENDING_PAYMENT:
                $request->markPending();
                break;

            case Api::STATUS_DECLINED:
                $request->markFailed();
                break;

            case Api::STATUS_EXPIRED:
                $request->markExpired();
                break;

            case Api::STATUS_PAID:
                $request->markPayedout();
                break;

            case Api::STATUS_PARTIALLY_REFUNDED:
            case Api::STATUS_REFUNDED:
                $request->markRefunded();
                break;

            case Api::STATUS_PRE_AUTHORIZED:
                $request->markAuthorized();
                break;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
