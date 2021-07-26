<?php

namespace frontend\modules\api;

use yii\base\Module;

/**
 * api module definition class
 */
class ApiModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
