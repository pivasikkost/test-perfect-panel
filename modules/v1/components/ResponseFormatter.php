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
            $newData->data = isset($response->data) ? (object) $response->data : null;
        } elseif (!YII_ENV_DEV) {
            $newData = new stdClass();
            $newData->status = 'error';
            //$newData->code = 403;
            $newData->message = $response->data['message']; // Message from error action
            //$newData->message = $response->statusText;
            //$newData->message = 'Invalid token';
        }
        if (isset($newData)) {
            $newData->code = $response->statusCode;
            $response->data = $newData;
        }
        //$response->statusCode = 200;

        return parent::format($response);
    }
}
