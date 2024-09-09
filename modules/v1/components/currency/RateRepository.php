<?php

namespace app\modules\v1\components\currency;

use yii\base\Component;

class RateRepository extends Component implements RateRepositoryInterface
{
    /**
     * @inheritDoc
     * @param RateRepositoryInterface $rateRepository Currency exchange rates
     */
    public function __construct(
        private CoinCapConnector $coinCapConnector,
        array $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
        $rates = $this->coinCapConnector->getRates();
        /** @var array $ratesLight example: 'LBP' => 0.0000111649894567 */
        $ratesLight = [];
        foreach ($rates as $rate) {
            $ratesLight[$rate['symbol']] = $rate['rateUsd'];
        }

        return $ratesLight;

    }

    /**
     * @inheritDoc
     */
    public function findOne(string $currencyId): float|null
    {
        $rates = $this->findAll();
        return $rates[$currencyId] ?? null;
    }
}