<?php

namespace frontend\service;

use Dadata\DadataClient;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

class DadataService extends Component
{
    public string $key;
    public string $secret;

    private DadataClient $dadataClient;

    public function init()
    {
        $this->dadataClient = new DadataClient($this->key, $this->secret);
    }

    public function getCityDtoByAddress(string $address): ?CityDTO
    {
        $cacheKey = md5($address);
        if (Yii::$app->cache->exists($cacheKey)) {
            return new CityDTO(JSON::decode(Yii::$app->cache->get($cacheKey)));
        }

        $data = $this->dadataClient->clean('address', $address);
        Yii::$app->cache->set($cacheKey, JSON::encode($data), 3600);

        return new CityDTO($data);
    }
}
