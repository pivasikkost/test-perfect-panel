<?php

namespace app\modules\v1\components\currency;

use app\modules\v1\components\BaseConnector;
use yii\base\UserException;
use yii\helpers\Json;

class CoinCapConnector extends BaseConnector
{
    const PATH_GET_RATES = '/v2/rates';
    public string $baseUrl = 'https://api.coincap.io';

    /**
     * @return RatesItem[]|null
     * @throws UserException
     */
    public function getRates(): array|null  {
        try {
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
        } catch (\Exception $e) {
            YII_ENV_DEV
                ? throw $e
                : throw new UserException(
                    'An error occurred while loading exchange rates from a third-party resource'
            );
        }

        return $rateObjects;
    }
}
