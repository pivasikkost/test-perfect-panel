<?php

namespace app\modules\v1\components;

use yii\base\InvalidConfigException;
use yii\web\Request;

/**
 * Формат ответа
 */
class ApiRequest extends Request
{
    /**
     * @inheritDoc
     * POST params added to actions arguments
     * @throws InvalidConfigException
     */
    public function resolve(): array
    {
        $result = parent::resolve();
        list($route, $params) = $result;
        return [$route, array_merge($params, $this->getBodyParams())];
    }


}
