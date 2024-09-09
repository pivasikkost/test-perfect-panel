<?php

use app\components\ag\AgConnector;
use app\modules\billing\Module as BillingModule;
use app\modules\rating\Module as RatingModule;
use GuzzleHttp\Client;
use UrbanIndo\Yii2\JsonFileTarget\JsonFileTarget;
use yii\di\Instance;
use yii\log\FileTarget;

$namespace = getenv('MY_POD_NAMESPACE');

return [
    // Будут создаваться каждый раз при обращении к ним
    'definitions' => [
    ],
    // Будут создаваться один раз при обращении к ним,
    // и далее использоваться раннее созданные
    'singletons' => [
        app\modules\v1\components\currency\RateRepositoryInterface::class =>
            app\modules\v1\components\currency\RateRepository::class
        ,
    ],
];
