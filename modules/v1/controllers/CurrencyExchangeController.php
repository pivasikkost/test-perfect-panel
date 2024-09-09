<?php

namespace app\modules\v1\controllers;

use app\helpers\CustomStringHelper;
use app\modules\v1\components\currency\ConvertRequest;
use app\modules\v1\components\currency\RateRepositoryInterface;
use app\modules\v1\components\currency\RatesRequest;
use Exception;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;

class CurrencyExchangeController extends \yii\rest\Controller
{
    /**
     * @inheritDoc
     * @param RateRepositoryInterface $rateRepository Currency exchange rates
     */
    public function __construct(
        $id,
        $controller,
        private RateRepositoryInterface $rateRepository,
        array $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            //'optional' => ['*']
        ];

        $behaviors ['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['convert', 'rates'],
                    'roles' => ['@'],
                ],
            ],
            /*'denyCallback' => function () {
                throw new ForbiddenHttpException(Yii::t('yii', 'Invalid token'));
            }*/
        ];

        /*$behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];*/

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    /*public function checkAccess($action, $model = null, $params = [])
    {
        $user = Yii::$app->user;
        if ($user !== false && $user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }*/

    /**
     * {@inheritdoc}
     */
    protected function verbs(): array
    {
        return [
            'convert' => ['POST'],
            'rates' => ['GET', 'HEAD'],
        ];
    }

    /**
     * Запрос на обмен валюты c учетом комиссии = 2% (POST запрос)
     *
     * Формат запроса.
     * {
     *      "currency_from": "USD",
     *      "currency_to": "BTC",
     *      "value": 1.00
     * }
     * или в обратную сторону
     * {
     *      "currency_from": "BTC",
     *      "currency_to": "USD",
     *      value: 1.00
     * }
     *
     * Формат ответа.
     * В случае успешного запроса, отдаем:
     * {
     *      “status”: “success”,
     *      “code”: 200,
     *      “data”: {
     *          “currency_from” : "BTC",
     *          “currency_to” : "USD",
     *          “value”: 1.00,
     *          “converted_value”: 1.00,
     *          “rate” : 1.00,
     *      }
     * }
     * В случае ошибки:
     * {
     *      “status”: “error”,
     *      “code”: 403,
     *      “message”: “Invalid token”
     * }
     *
     * @param string $currency_from
     * @param string $currency_to
     * @param float $value Минимальный обмен равен 0,01 валюты
     * @return array
     * @throws Exception
     */
    public function actionConvert(string $currency_from, string $currency_to, float $value): array
    {
        $argNames = array_keys(get_defined_vars());
        $rates = $this->rateRepository->findAll();
        $argNames[] = 'rates';
        $request = new ConvertRequest(compact($argNames));
        $request->validate();

        if ($currency_from === 'USD') {
            $externalRate = $this->rateRepository->findOne($currency_to);
            // Комиссия 2%
            $ourRate = round((1 / $externalRate) * 0.98, 16);
        } elseif ($currency_to === 'USD') {
            $externalRate = $this->rateRepository->findOne($currency_from);
            $ourRate = round($externalRate * 0.98, 16);
        }
        $converted_value = round(
            $value * $ourRate,
            $currency_from === 'USD' ? 10 : 2
        );

        return [
            'currency_from' => $currency_from,
            'currency_to' => $currency_to,
            'value' => $value,
            'converted_value' => $converted_value,
            'rate' => $ourRate,
            //'converted_value' => $this->prettify($converted_value, 10),
            //'rate' => $this->prettify($ourRate),
        ];
    }

    /**
     * Получение всех курсов с учетом комиссии = 2% (GET запрос)
     *
     * Формат ответа.
     * В случае успешного запроса, отдаем:
     * {
     *      “status”: “success”,
     *      “code”: 200,
     *          “data”: {
     *          “USD” : <rate>,
     *          ...
     *      }
     * }
     * В случае ошибки:
     * {
     *      “status”: “error”,
     *      “code”: 403,
     *      “message”: “Invalid token”
     * }
     *
     * @param string|null $currency В качестве параметра может передаваться интересующая валюта,
     * в формате USD/RUB/EUR и тп. В этом случае, отдаем указанное в качестве параметра currency значение.
     * @return array Курсы обмена валют. Пример экземпляра: 'LBP' => 0.0000111649894567
     * @throws Exception
     */
    public function actionRates(?string $currency = null): array
    {
        $rates = $this->rateRepository->findAll();
        $argNames = ['currency', 'rates'];
        $request = new RatesRequest(compact($argNames));
        $request->validate();

        $externalRates = isset($currency)
            ? [$currency => $this->rateRepository->findOne($currency)]
            : $this->rateRepository->findAll()
        ;
        // Учитываем нашу комиссию = 2%
        $ourRates = array_map(
            fn($v): float => round($v * 0.98, 16),
            $externalRates
        );
        // Сортировка от меньшего курса к большему курсу.
        asort($ourRates);

        return $ourRates;
        /*return array_map(
            fn($v): string => $this->prettify($v),
            $ourRates
        );*/
    }

    /**
     * For not scientific notation numbers representation
     * @param float $number
     * @param int $precision
     * @return string
     */
    public function prettify (float $number, int $precision = 16): string
    {
        return CustomStringHelper::addLeadingZeroAndRound($number, $precision);
    }
}