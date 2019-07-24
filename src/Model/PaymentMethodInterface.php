<?php
/**
 * Created by.
 *
 * User: JCHR <car.chr@gmail.com>
 * Date: 24/07/19
 * Time: 03:57 PM
 */

declare(strict_types=1);

namespace EncodeMedia\Payum\Conekta\Model;

/**
 * Interface PaymentMethod
 *
 * @author JCHR <car.chr@gmail.com>
 */
interface PaymentMethodInterface
{

    /**
     * Payment method
     *
     * @return string|null
     */
    public function getConektaPaymentMethod(): ?string;


    /**
     * Set oxxo pay
     */
    public function setConektaOxxoPay();

    /**
     * Set conekta spei
     */
    public function setConektaSpei();

    /**
     * Set conekta card
     */
    public function setConektaCard();
}
