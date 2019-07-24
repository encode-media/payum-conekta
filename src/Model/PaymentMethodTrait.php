<?php
/**
 * Created by.
 *
 * User: JCHR <car.chr@gmail.com>
 * Date: 23/07/19
 * Time: 09:26 AM
 */

declare(strict_types=1);

namespace EncodeMedia\Payum\Conekta\Model;

use EncodeMedia\Payum\Conekta\Api;

/**
 * Trait PaymentMethod
 *
 * @author JCHR <car.chr@gmail.com>
 */
trait PaymentMethodTrait
{

    /** @var string */
    private $conektaPaymentMethod;

    /**
     * Payment method
     *
     * @return string|null
     */
    public function getConektaPaymentMethod(): ?string
    {
        return $this->conektaPaymentMethod;
    }

    /**
     * Set oxxo pay
     */
    public function setConektaOxxoPay(): void
    {
        $this->conektaPaymentMethod = Api::PAYMENT_OXXO_PAY;
    }

    /**
     * Set conekta spei
     */
    public function setConektaSpei(): void
    {
        $this->conektaPaymentMethod = Api::PAYMENT_SPEI;
    }

    /**
     * Set conekta card
     */
    public function setConektaCard(): void
    {
        $this->conektaPaymentMethod = Api::PAYMENT_CARD;
    }
}
