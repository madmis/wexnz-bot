<?php

namespace App\Strategy\Traits;

use App\Service\WexnzClient;
use madmis\WexnzApi\Exception\ClientErrorException;
use madmis\WexnzApi\Model\Order;

/**
 * Trait CloseNotTopBuyOrders
 * @package App\Strategy\Traits
 * @method WexnzClient getClient
 * @method void info(string $message)
 */
trait CloseNotTopBuyOrders
{
    /**
     * @param string $pair
     */
    public function closeNotTopBuyOrders(string $pair)
    {
        $this->info('<g>Close not TOP buy orders.</g>');

        /** @var Order[] $activeOrders */
        try {
            $activeOrders = $this->getClient()->getActiveBuyOrders($pair);
            $count = \count($activeOrders);
        } catch (ClientErrorException $e) {
            $count = 0;
        }
        $this->info("\t<g>Active buy orders: {$count}</g>");

        if ($count) {
            $topOrder = $this->getClient()->getTopBuyOrder($pair);
            if ($topOrder) {
                foreach ($activeOrders as $activeOrder) {
                    $amountEq = bccomp($activeOrder->getAmount(), $topOrder->getAmount(), 6) === 0;
                    $rateEq = bccomp($activeOrder->getRate(), $topOrder->getRate(), 6) === 0;
                    if (!$amountEq && !$rateEq) {
                        $this->info(sprintf(
                            "\t<w>Order #%s: volume|%s price|%s - not in the TOP. Close it.</w>",
                            $activeOrder->getId(),
                            $activeOrder->getAmount(),
                            $activeOrder->getRate()
                        ));
                        $this->getClient()->trade()->cancelOrder($activeOrder->getId());
                        $this->info("\t<w>Order closed</w>");
                    } else {
                        $this->info(sprintf(
                            "\t<g>Order #%s: volume|%s price|%s - in the TOP. Don't close it.</g>",
                            $activeOrder->getId(),
                            $activeOrder->getAmount(),
                            $activeOrder->getRate()
                        ));
                    }
                }
            }
        } else {
            $this->info("\t<y>There is nothing to close</y>");
        }
    }
}
