<?php

namespace Vitalybaev\YandexDelivery;

/**
 * Библиотека для выполнения запросов к API Яндекс.Доставки.
 */
class Client
{
    /**
     * client_id, выдается при подключении магазина
     *
     * @var string
     */
    private $clientId;

    /**
     * sender_id, выдается при подключении магазина
     *
     * @var string
     */
    private $senderId;

    /**
     * Версия API
     *
     * @var string
     */
    private $version = '1.0';

    /**
     * Ключи запросов, выдаются в разделе Настройки -> Интеграции
     *
     * @var array
     */
    private $methodKeys = [];

    /**
     * Http клиент
     *
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * Конструктор
     *
     * @param string $clientId
     * @param string $senderId
     * @param string $apiVersion
     * @param array  $methodKeys
     */
    function __construct($clientId, $senderId, $apiVersion = '1.0', $methodKeys = [])
    {
        $this->clientId = $clientId;
        $this->senderId = $senderId;
        $this->apiVersion = $apiVersion;

        if (count($methodKeys) > 0) {
            foreach ($methodKeys as $key => $value) {
                $this->methodKeys[$key] = $value;
            }
        }

        $this->httpClient = new \GuzzleHttp\Client();
    }

    /**
     * @return string
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * @param  $senderId
     *
     * @return static
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param  $clientId
     *
     * @return static
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * Выполняет запрос к API.
     *
     * @param  string $method
     * @param  array $parameters
     *
     * @return object
     *
     * @throws Exception\InvalidJsonException
     * @throws Exception\MethodKeysNotExistsException
     */
    public function call($method, $parameters)
    {
        // Получаем method_key
        if (!isset($this->methodKeys[$method])) {
            throw new Exception\MethodKeysNotExistsException("Не указан method_key для метода $method.");
        }

        $httpParameters = array_merge($parameters, [
            'client_id' => $this->clientId,
            'sender_id' => $this->senderId,
        ]);

        $secretKey = md5($this->getPostValues($httpParameters) . $this->methodKeys[$method]);
        $httpParameters['secret_key'] = $secretKey;

        // Выполняем запрос
        $httpResponse = $this->httpClient->request('POST', "https://delivery.yandex.ru/api/$this->apiVersion/$method", [
            'form_params' => $httpParameters,
        ]);

        // Получаем тело ответа
        $body = $httpResponse->getBody()->getContents();
        $json = json_decode($body);
        if (!$json) {
            throw new Exception\InvalidJsonException("Некорректный JSON");
        }

        return $json;
    }

    /**
     * Возвращает строку для получения секретного ключа.
     *
     * @param $data
     *
     * @return string
     */
    private function getPostValues($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        ksort($data);
        return join('', array_map(function($k) {
            return $this->getPostValues($k);
        }, $data));
    }
}
