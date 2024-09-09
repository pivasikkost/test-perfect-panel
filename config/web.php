<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'v1/currency-exchange/rates',
    'bootstrap' => ['log'],
    'container' => require(__DIR__ . '/di_config.php'),
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'class' => 'app\modules\v1\components\ApiRequest',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'knchf1R4oiyFU8Nm6txZsKzT8LoEKppT',
            // To let the API accept input data in JSON format
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            /*'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },*/
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    //'class' => 'yii\web\JsonResponseFormatter',
                    'class' => 'app\modules\v1\components\ResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            //'identityClass' => 'app\modules\v1\models\ApiUserIdentity',
            //'enableAutoLogin' => true,
            //'enableSession' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
            //'errorAction' => 'v1/currency-exchange/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'api/v1?method=rates' => 'v1/currency-exchange/rates',
                'api/v1?method=convert' => 'v1/currency-exchange/convert',

                //'api/v1' => 'v1/currency-exchange/<method>',
                //'api/v1?method=<method_name:\w+>' => 'v1/currency-exchange/<method_name>',
                //'api/v1<method_name:[/^(.*)?method=(\w+)$/]>&<parameter>=<value>' => 'v1/currency-exchange/<method_name>',
                //'api/v1?method=<method_name:[\w-]+>&<parameter:[\w-]+>=<value:[\w-]+>' => 'v1/currency-exchange/<method_name>',
                //'api/v1?method=<method_name>&<parameter>=<value>' => 'v1/currency-exchange/<method_name>',
                '<module:\w+>/<controller:[\w-]+>/<action:[\w-]+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:[\w-]+>' => '<module>/<controller>',
                '<controller:\w+>/<action:[\w-]+>' => '<controller>/<action>',
                '<module:\w+>' => '<module>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
