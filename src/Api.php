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

use Conekta\Conekta;
use Conekta\Order;

/**
 * Api
 *
 * @author JCHR <car.chr@gmail.com>
 */
class Api
{
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_PAID = 'paid';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    public const STATUS_CHARGED_BACK = 'charged_back';
    public const STATUS_PRE_AUTHORIZED = 'pre_authorized';
    public const STATUS_VOIDED = 'voided';

    public const PAYMENT_OXXO_PAY = 'oxxo_cash';
    public const PAYMENT_CARD = 'card';
    public const PAYMENT_SPEI = 'spei';


    /** @var array */
    protected $options = [];

    /**
     * Api constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;

        $this->addCredentials();
    }

    /**
     * Capture
     *
     * @param array $data
     *
     * @return array
     */
    public function capture(array $data): array
    {
        $result = [];
        try {
            /** @var Order $order */
            $order = Order::create($data);
            $result = json_decode($order->__toJSON(), true);
        } catch (\Exception $e) {
            $result['error'] = sprintf('%s: %s', $e->getCode(), $e->getMessage());
        }

        return $result;
    }

    /**
     * Set credentials
     */
    private function addCredentials(): void
    {
        $prefix = $this->options['sandbox'] ? 'sandbox' : 'production';

        Conekta::setApiKey($this->options[$prefix.'_private_key']);
    }
}
