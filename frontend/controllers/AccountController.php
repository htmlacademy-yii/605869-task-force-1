<?php

    namespace frontend\controllers;

    use frontend\models\AccountForm;
    use frontend\models\City;
    use frontend\models\Photo;
    use frontend\models\User;
    use Throwable;
    use Yii;
    use yii\helpers\Url;
    use yii\web\BadRequestHttpException;
    use yii\web\UploadedFile;

    class AccountController extends SecuredController
    {
        public function init()
        {
            $this->enableCsrfValidation = false;
        }

        public function actionIndex()
        {
            $model = new AccountForm();

            if (Yii::$app->request->isPost) {
                $model->load(Yii::$app->request->post());
                $model->save();
            }


            $cities = City::getListCities();

            return $this->render(
                'index',
                [
                    'model' => $model,
                    'user' => Yii::$app->user->getIdentity(),
                    'cities' => $cities,
                ]
            );
        }

        public function actionUpload()
        {
            $uploadPath = Yii::getAlias('@webroot') . '/uploads/photo';

            if (isset($_FILES['file'])) {
                $user = User::findOne(Yii::$app->user->getId());
                $files = UploadedFile::getInstancesByName('file');
                if (count($user->photos) == 6 || (count($user->photos) + count($files)) >= 6) {
                    throw new BadRequestHttpException();
                }

                foreach ($files as $file) {
                    $fileName = md5(microtime() . Yii::$app->user->getId());
                    $saveFiles = $file->saveAs($uploadPath . '/' . $fileName . '.' . $file->getExtension());

                    if ($saveFiles) {
                        $model = new Photo();
                        $model->user_id = Yii::$app->user->getId();
                        $model->name = $fileName . '.' . $file->getExtension();
                        $model->save();
                    }
                }
            }

            return false;
        }

        public function actionDeletePhoto(int $id): void
        {
            $photo = Photo::findOne($id);
            if ($photo) {
                $photo->delete();
                try {
                    unlink(Yii::getAlias('@webroot') . '/uploads/photo/' . $photo->name);
                } catch (Throwable $e) {
                }
            }

            $this->redirect(Url::toRoute('/account'));
        }
    }
