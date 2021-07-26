<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class TaskForceAsset extends AssetBundle
{
    public $css = [
        'css/normalize.css',
        'css/style.css',
        'https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css',
    ];

    public $js = [
        'js/address.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js',
        'https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js',
        'js/main.js',
        'js/plugins.js',
        'https://api-maps.yandex.ru/2.1/?apikey=e666f398-c983-4bde-8f14-e3fec900592a&lang=ru_RU',
        'js/map.js',
        'js/messenger.js',
    ];
}
