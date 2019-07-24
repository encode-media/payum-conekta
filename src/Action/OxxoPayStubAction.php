<?php
/**
 * Created by.
 *
 * User: JCHR <car.chr@gmail.com>
 * Date: 24/07/19
 * Time: 10:29 AM
 */

declare(strict_types=1);

namespace EncodeMedia\Payum\Conekta\Action;

use EncodeMedia\Payum\Conekta\Request\OxxoPayStub;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\RenderTemplate;

/**
 * Oxxo Pay Stub Action
 *
 * @author JCHR <car.chr@gmail.com>
 */
class OxxoPayStubAction implements ActionInterface, GatewayAwareInterface
{

    use GatewayAwareTrait;

    /** @var string */
    private $templateName;

    /** @var string */
    private $bussinesName;

    /**
     * Oxxo Pay Stub Action constructor.
     *
     * @param string      $templateName
     * @param string|null $bussinesName
     */
    public function __construct(string $templateName, ?string $bussinesName)
    {
        $this->templateName = $templateName;
        $this->bussinesName = $bussinesName;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var $payment PaymentInterface */
        $payment = $request->getModel();

        $this->gateway->execute($status = new GetHumanStatus($payment));

        if (! $status->isPending()) {
            throw new LogicException('Payment status is not pending');
        }

        $model = ArrayObject::ensureArrayObject($payment);

        $reference = $model->get('charges')[0]['payment_method']['reference'];

        $renderTemplate = new RenderTemplate($this->templateName, [
            'amount' => $model->get('amount') / 100,
            'currency' => $model->get('currency'),
            'reference' => str_split($reference, 4),
            'bussines_name' => $this->bussinesName,
        ]);
        $this->gateway->execute($renderTemplate);

        throw new HttpResponse($renderTemplate->getResult());
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof OxxoPayStub &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}