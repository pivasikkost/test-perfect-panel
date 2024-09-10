Задание 1, SQL
------------
Запрос выборки данных из представленных таблиц, который найдет и выведет всех посетителей библиотеки, возраст которых попадает в диапазон от 7 и до 17 лет, которые  взяли две книги одного автора (взяли всего 2 книги и они одного автора), книги были у них в руках не более двух календарных недель (не просрочили 2-х недельный срок пользования)
~~~
SELECT
    u.id as ID,
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    b.author as Author,
    GROUP_CONCAT(DISTINCT b.name ORDER BY b.name SEPARATOR ', ') as Books
FROM user_books ub
INNER JOIN books b
  ON ub.book_id = b.id
INNER JOIN users u
  ON ub.user_id = u.id
WHERE
  user_id IN (
    SELECT user_id
    FROM user_books ub1
    GROUP BY ub1.user_id
    HAVING COUNT(DISTINCT book_id) = 2
  ) -- users with 2 books
  AND TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE) BETWEEN 7 AND 17
  AND TIMESTAMPDIFF(DAY, ub.get_date, CURRENT_DATE) <= 14
GROUP BY ub.user_id , b.author
HAVING COUNT(DISTINCT book_id) = 2
;
~~~

Если имелось в виду, не сейчас взявшие и не сдавшие за 2 недели, а когда-либо бравшие и возвращавшие меньше чем через 2 недели, то нужно просто заменить в этом запросе константу CURRENT_DATE во втором случае на ub.return_date

<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Задание 2, тестовый проект, основанный на Yii 2</h1>
    <br>
</p>

### JSON API сервис на языке php 8 для работы с курсами обмена валют (относительно USD).

ТРЕБОВАНИЯ
------------
Минимальное требование этого проекта — поддержка вашим веб-сервером PHP 8.0.

УСТАНОВКА
------------
### Клонировать репозиторий

Сначала вам нужно клонировать репозиторий этого проекта:

~~~
git clone https://github.com/pivasikkost/test-perfect-panel <target folder>
~~~

### Установка с помощью Docker и Composer

Если у вас нет [Composer](https://getcomposer.org/), вы можете установить его, следуя инструкциям
на [getcomposer.org](https://getcomposer.org/doc/00-intro.md#installation-nix).

Некоторые файлы проекта отсутствуют в репозитории, чтобы установить их, перейдите в каталог проекта и выполните команды:

Обновите пакеты поставщиков

    docker-compose run --rm php composer update --prefer-dist

апустите триггеры установки (создание кода проверки файлов cookie)

    docker-compose run --rm php composer install    

Запустите контейнер

    docker-compose up -d

Приложение будет доступно по адресам:

    http://127.0.0.1:8000
    http://localhost:8000

Bearer токен для авторизации:
~~~
080042cad6356ad5dc0a720c18b53b8e53d4c274
~~~

**ПРИМЕЧАНИЯ:**
- Минимально необходимая версия движка Docker `17.04` для разработки (см. [Настройка производительности для монтирования томов](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- Конфигурация по умолчанию использует хост-том в вашем домашнем каталоге `.docker-composer` для кэшей Composer

ТЕСТИРОВАНИЕ
-------------

### Метод rates


Из консоли отправьте следующий запрос
~~~
curl -i -H "Accept:application/json" -H "Authorization: Bearer 080042cad6356ad5dc0a720c18b53b8e53d4c274" "http://localhost:8000/v1/currency-exchange/rates?currency=BTC"
~~~
Получите ответ
~~~
HTTP/1.1 200 OK
Date: Mon, 09 Sep 2024 10:50:52 GMT
Server: Apache/2.4.56 (Debian)
Vary: Accept
X-Debug-Tag: 66ded30c996e8
X-Debug-Duration: 866
X-Debug-Link: /debug/default/view?tag=66ded30c996e8
Set-Cookie: _csrf=b83cb5c64d950ec4ca7b1d68896eeb23b86862b480eadda1ac67fafafd595b8da%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22TmTxUUpoOyBUOJLr06gcvJepC4G9AaGC%22%3B%7D; path=/; HttpOnly; SameSite=Lax
Content-Length: 32
Content-Type: application/json; charset=UTF-8
{
    "status": "success",
    "code": 200,
    "data": {
        "BTC": 54334.25987123895
    }
}
~~~
Сделайте то же самое только без currency параметра и используя проверку разных правил валидации

### Метод convert
Из консоли отправьте следующий запрос
~~~
curl -i -X POST -H "Authorization: Bearer 080042cad6356ad5dc0a720c18b53b8e53d4c274" -d 'currency_from=BTC&currency_to=USD&value=0.01' "http://localhost:8000/v1/currency-exchange/convert"
~~~
Получите ответ
~~~
HTTP/1.1 200 OK
Date: Mon, 09 Sep 2024 11:00:21 GMT
Server: Apache/2.4.56 (Debian)
Vary: Accept
X-Debug-Tag: 66ded5456ed86
X-Debug-Duration: 794
X-Debug-Link: /debug/default/view?tag=66ded5456ed86
Set-Cookie: _csrf=1b510ee9f28ade5f06f75016a898ba41a9101f30d1e69890c2d1f49c518dafdea%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%224jVzTT7lkgjkCXIhQqya441d6CF78euS%22%3B%7D; path=/; HttpOnly; SameSite=Lax
Content-Length: 137
Content-Type: application/json; charset=UTF-8
{
    "status": "success",
    "code": 200,
    "data": {
        "currency_from": "BTC",
        "currency_to": "USD",
        "value": 0.01,
        "converted_value": 543.1,
        "rate": 54309.594394244836
    }
}
~~~
Сделайте то же самое только используя проверку разных комбинаций параметров
