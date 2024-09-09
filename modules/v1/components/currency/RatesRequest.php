<?php

namespace app\modules\v1\components\currency;

class RatesRequest extends BaseApiRequest
{
    public ?string $currency; //example: "BTC"

    public array $rates = [];

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['currency'], 'string', /*'max' => 5*/],
            [['currency'],
                'filter',
                'filter' => 'strtoupper',
                'skipOnArray' => true,
                'skipOnEmpty' => true
            ],
            [['currency'], 'existValue'],
        ];
    }

    public function existValue($attribute, $params) {
        if (!in_array($this->$attribute, array_keys($this->rates), true)) {
            $this->addError($attribute, "There is no exchange rate for currency {$this->$attribute}");
        }
    }
}