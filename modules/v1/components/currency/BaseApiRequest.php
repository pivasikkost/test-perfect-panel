<?php

namespace app\modules\v1\components\currency;

use yii\base\UserException;
use yii\base\Model;

class BaseApiRequest extends Model
{
    /**
     * @inheritDoc
     * Преобразует первую ошибку валидирования в Exception, если она есть.
     * @throws Exception
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $validate = Model::validate($attributeNames, $clearErrors);
        if (!$validate) {
            $errors = $this->getFirstErrors();
            $attribute = key($errors);
            throw new UserException($this->getFirstError($attribute));
        }
        return $validate;
    }
}