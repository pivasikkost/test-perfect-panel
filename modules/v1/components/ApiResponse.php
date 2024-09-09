<?php

namespace app\modules\v1\components;

/**
 * Формат ответа
 */
class ApiResponse
{
    /**
     * @var string Расшифровка статуса, 'success' в случае отсутствия ошибки
     */
    public string $status = 'success';

    /**
     * @var int|null Код ответа, 200 в случае отсутствия ошибки
     */
    public ?int $code = 200;

    /**
     * @var mixed Результат выполнения запроса
     */
    public mixed $data;

    /**
     * @var string Текст ошибки. Пустая строка ('') в случае отсутствия ошибки
     */
    //public string $message = '';

}
