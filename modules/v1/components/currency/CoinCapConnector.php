<?php

namespace app\modules\v1\components\currency;

use app\modules\v1\components\BaseConnector;
use app\modules\v1\components\currency\RatesItem;
use yii\helpers\Json;

class CoinCapConnector extends BaseConnector
{
    const PATH_GET_RATES = '/v2/rates';
    public string $baseUrl = 'https://api.coincap.io';

    /**
     * @return RatesItem[]|null
     */
    public function getRates(): array|null  {
        // Получаем список всех курсов валют
        //$data = Json::decode('{"data":[{"id":"bitcoin","symbol":"BTC","currencySymbol":"₿","type":"crypto","rateUsd":"54326.2455623257398614"}]}');
        //TODO кеширование, т.к. на портале есть ограничитель запросов
        $data = Json::decode(file_get_contents($this->baseUrl . self::PATH_GET_RATES));
        $rates = $data['data'];
        $rateObjects = [];
        foreach ($rates as $rate) {
            // maybe RatesLightItem ?
            $rateObjects[] = new RatesItem($rate);
        }
        RatesItem::loadMultiple($rateObjects, $rates);
        RatesItem::validateMultiple($rateObjects);

        return $rateObjects;
    }
}
