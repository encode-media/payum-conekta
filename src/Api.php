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

        Conekta::setApiKey($this->options['private_key']);
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
            $result['success'] = true;
            $result['response'] = $order->getArrayCopy();
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['response'] = $order->getArrayCopy();
        }

        return $result;
    }
}
