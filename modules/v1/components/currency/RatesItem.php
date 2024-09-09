<?php

namespace app\modules\v1\components\currency;

use yii\base\Model;

class RatesItem extends Model
{
    public string $id; //example: "lebanese-pound"
    public string $symbol; //example: "LBP"
    public ?string $currencySymbol; //example: "£"
    public string $type; //example: "fiat"
    public float $rateUsd; //example: "0.0000111649894567"

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['symbol', 'rateUsd'], 'required'],
            ['symbol', 'string', /*'max' => 5*/],
            ['rateUsd', 'double'],
        ];
    }
}