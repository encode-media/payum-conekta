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

use EncodeMedia\Payum\Conekta\Action\Api\AbstractBaseApiAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Cancel;

/**
 * Cancel Action
 *
 * @author JCHR <car.chr@gmail.com>
 */
class CancelAction extends AbstractBaseApiAwareAction
{

    /**
     * {@inheritDoc}
     *
     * @param Cancel $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        throw new \LogicException('Not implemented Cancel.');
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Cancel &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
