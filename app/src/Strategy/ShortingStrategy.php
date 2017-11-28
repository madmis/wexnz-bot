<?php

namespace App\Strategy;

use App\Strategy\Traits\CloseNotTopBuyOrders;
use App\Strategy\Traits\MinimumTradeAmount;

/**
 * Class ShortingStrategy
 * @package App\Strategy
 */
class ShortingStrategy extends Strategy
{
    use CloseNotTopBuyOrders;
    use MinimumTradeAmount;

    /**
     * @param string $pair
     * @return float
     */
    public function getCurrentSellPrice(string $pair): float
    {
        return $this->getClient()->getTopSellPrice($pair);
    }

    /**
     * @param string $pair
     * @return float
     */
    public function getCurrentBuyPrice(string $pair): float
    {
        return $this->getClient()->getTopBuyPrice($pair);
    }
}
