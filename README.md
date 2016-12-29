### PHP SDK для работы с сервисом Яндекс.Доставка
1. Установите пакет
```
composer require vitalybaev/yandex.delivery
```
2. В разделе Настройки &rarr; Интеграция &rarr; API ключи перенесите полученые секретные ключи в массив вида:
```php
$methodKeys = [
    "getPaymentMethods" => "8fbde...11a58",
    "getSenderOrderLabel" => "464ff...579fb",
    ...
    "getPaymentMethods" => "2dd0...7d475f",
];
```
3. Создайте экзепляр клиента, передав ему client_id, sender_id и `$methodKeys`, подготовленные на прошлом шаге и выполняйте запросы:
```php
use Vitalybaev\YandexDelivery\Client;

$ydClient = new Client($clientId, $senderId, '1.0', $methodKeys);

$deliveries = $ydClient->call('searchDeliveryList', [
    'city_from' => 'Москва',
    'city_to' => 'Челябинск',
    'weight' => '1.25',
    'width' => '35',
    'height' => '25',
    'length' => '9',
    'total_cost' => '0',
    'order_cost' => '1920',
    'assessed_value' => '1920',
    'to_yd_warehouse' => '1',
]);
```

## Лицензия

> The MIT License
>  
>  Copyright (c) 2016 Vitaly Baev
>  
>  Permission is hereby granted, free of charge, to any person obtaining a copy
>  of this software and associated documentation files (the "Software"), to deal
>  in the Software without restriction, including without limitation the rights
>  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
>  copies of the Software, and to permit persons to whom the Software is
>  furnished to do so, subject to the following conditions:
>  
>  The above copyright notice and this permission notice shall be included in
>  all copies or substantial portions of the Software.
>  
>  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
>  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
>  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
>  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
>  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
>  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
>  THE SOFTWARE.