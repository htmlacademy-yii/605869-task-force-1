<?php

namespace frontend\controllers;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;
use frontend\models\City;
use frontend\models\RegistrationForm;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $model = new RegistrationForm();
        
        $cities = City::find()->all();
        $cityList = ArrayHelper::map($cities, 'id', 'name');
        if (Yii::$app->request->post()) {
        	$model->load(Yii::$app->request->post());
            if ($model->validate() && $model->createUser()) {
              return $this->goHome();
            }
        }
        return $this->render('index', ['model' => $model, 'cities' => $cities, 'cityList' => $cityList]);
    }
}