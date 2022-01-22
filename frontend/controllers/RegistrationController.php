<?php

namespace frontend\controllers;

use Yii;
use frontend\models\City;
use frontend\models\RegistrationForm;

class RegistrationController extends UnsecuredController
{
    public $layout = 'registration';

    public function actionIndex()
    {
        $model = new RegistrationForm();

        $cities = City::find()->all();
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate() && ($user = $model->createUser())) {
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }
        return $this->render('index', ['model' => $model, 'cities' => $cities]);
    }
}
