<?php

namespace app\modules\v1\components;

use stdClass;
use yii\web\JsonResponseFormatter;

class ResponseFormatter extends JsonResponseFormatter
{
    protected int $errCode;

    /**
     * @inheritDoc
     */
    public function format($response)
    {
        if ($response->isSuccessful) {
            $newData = new ApiResponse();
            $newData->data = (object) ($response->data ?? null);
        } else {
            $newData = new stdClass();
            $newData->status = 'error';
            //$newData->code = 403;
            //$newData->message = 'Invalid token';
            $newData->message = $response->data['message']; // $response->statusText
        }
        $newData->code = $response->statusCode;
        YII_ENV_DEV ?: $response->data = $newData;
        //$response->statusCode = 200;

        return parent::format($response);
    }
}
