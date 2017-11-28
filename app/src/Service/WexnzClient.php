<?php

namespace App\Service;

use madmis\ExchangeApi\Exception\ClientException;
use madmis\WexnzApi\Api;
use madmis\WexnzApi\Model\Depth;
use madmis\WexnzApi\Model\Order;
use madmis\WexnzApi\Model\TradeHistory;
use madmis\WexnzApi\WexnzApi;

/**
 * Class WexnzClient
 * @package App\Service
 */
class WexnzClient extends WexnzApi
{
    public const PAIR_BCHBTC = 'bch_btc';

    /**
     * @var array
     */
    private $minTradeAmounts = [
        'btc' => 0.001,
        'bch' => 0.001,
    ];

    /**
     * @param string $publicKey
     * @param string $secretKey
     */
    public function __construct($publicKey, $secretKey)
    {
        parent::__construct(
            'https://wex.nz',
            $publicKey,
            $secretKey,
            '/var/www/var/'
        );
    }

    /**
     * Get latest executed buy order
     * @param string $pair
     * @return TradeHistory|null
     */
    public function getLatestBuyOrder(string $pair): ?TradeHistory
    {
        /** @var TradeHistory[] $history */
        $history = $this->trade()->tradeHistory($pair, true);
        /** @var TradeHistory $latestBuy */
        $latestBuy = null;
        foreach ($history as $item) {
            if ($item->getType() === Api::BUY && $item->isYourOrder()) {
                $latestBuy = $item;
                break;
            }
        }

        return $latestBuy;
    }

    /**
     * @param string $currency
     *
     * @return float
     *
     * @throws ClientException
     * @throws \LogicException
     */
    public function getCurrencyFunds(string $currency): float
    {
        // check pair balance. If we have base currency sell it
        $info = $this->trade()->userInfo(true);

        return $info->getFunds()[$currency] ?? 0;
    }

    /**
     * @param string $pair
     *
     * @return float
     */
    public function getTopSellPrice(string $pair): float
    {
        return $this->getTopSellOrder($pair)->getRate();
    }

    /**
     * @param string $pair
     *
     * @return Depth
     */
    public function getTopSellOrder(string $pair): Depth
    {
        $orders = $this->shared()->depth($pair, 1, true);

        return $orders['asks'][0];
    }

    /**
     * @param string $pair
     *
     * @return float
     */
    public function getTopBuyPrice(string $pair): float
    {
        return $this->getTopBuyOrder($pair)->getRate();
    }

    /**
     * @param string $pair
     *
     * @return Depth
     */
    public function getTopBuyOrder(string $pair): Depth
    {
        $orders = $this->shared()->depth($pair, 1, true);

        return $orders['bids'][0];
    }

    /**
     * @param string $pair
     *
     * @return Order[]|array
     */
    public function getActiveBuyOrders(string $pair): array
    {
        return array_filter(
            $this->trade()->activeOrders($pair, true),
            function (Order $order) {
                return $order->getType() === Api::BUY;
            }
        );
    }

    /**
     * @param string $pair
     *
     * @return Order[]|array
     */
    public function getActiveSellOrders(string $pair): array
    {
        return array_filter(
            $this->trade()->activeOrders($pair, true),
            function (Order $order) {
                return $order->getType() === Api::SELL;
            }
        );
    }

    /**
     * Get minimal trade amount for currency
     *
     * @param string $currency
     *
     * @return float
     */
    public function minTradeAmount(string $currency): float
    {
        $res = $this->minTradeAmounts[$currency] ?? 0.001;

        return (float)$res;
    }

    /**
     * @param array $amounts
     */
    public function setMinTradeAmounts(array $amounts)
    {
        if ($amounts) {
            $this->minTradeAmounts = array_merge($this->minTradeAmounts, $amounts);
        }
    }

    /**
     * @param string $pair
     *
     * @return array
     */
    public static function splitPair(string $pair): array
    {
        return explode('_', $pair);
    }

}