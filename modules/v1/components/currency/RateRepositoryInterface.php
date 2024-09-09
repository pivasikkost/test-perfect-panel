<?php

namespace app\modules\v1\components\currency;

interface RateRepositoryInterface
{
    /**
     * @return array Курсы обмена валют. Пример экземпляра: 'LBP' => 0.0000111649894567
     */
    public function findAll(): array;

    /**
     * @param string $currencyId
     * @return float|null Курс обмена валюты Пример экземпляра: 0.0000111649894567
     */
    public function findOne(string $currencyId): float|null;
}