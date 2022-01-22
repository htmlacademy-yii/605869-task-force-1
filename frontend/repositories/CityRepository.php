<?php

namespace frontend\repositories;

use frontend\models\City;

class CityRepository
{
    public static function getCityByKladrCode(string $kladrCode, string $cityName, float $long, float $lat): City
    {
        $model = City::findOne(['kladr' => $kladrCode]);
        if(!$model) {
            $model = new City();
            $model->kladr = $kladrCode;
            $model->name = $cityName;
            $model->long = $long;
            $model->lat = $lat;
            $model->save();
        }

        return $model;
    }
}
