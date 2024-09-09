<?php

namespace app\modules\v1;

use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->identityClass = 'app\modules\v1\models\ApiUserIdentity';
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }
}