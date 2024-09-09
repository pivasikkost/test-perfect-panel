<?php

namespace app\modules\v1\components\currency;

class ConvertRequest extends RatesRequest
{
    public string $currency_from; //example: "BTC"
    public string $currency_to; //example: "USD"
    public float $value; //example: 0.01

    public array $rates = [];

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['currency_from', 'currency_to', 'value'], 'required'],
            ['value', 'double', 'min' => 0.01],
            [['currency_from', 'currency_to'], 'string', /*'max' => 5*/],
            [['currency_from', 'currency_to'],
                'filter',
                'filter' => 'strtoupper',
                'skipOnArray' => true,
                'skipOnEmpty' => true
            ],
            [['currency_from', 'currency_to'],
                'required',
                'requiredValue' => 'USD',
                'message' => 'currency_from or currency_to must be USD',
                'when' => function($model, $attribute) {
                    if (!in_array('USD', [$model->currency_from, $model->currency_to], true)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            ],
            [['currency_from', 'currency_to'], 'existValue'],
        ];
    }
}