<?php

    namespace TaskForce\components;

    use frontend\models\Auth;
    use frontend\models\City;
    use frontend\models\Profiles;
    use frontend\models\User;
    use Yii;
    use yii\authclient\ClientInterface;
    use yii\helpers\ArrayHelper;

    /**
     * AuthHandler handles successful authentication via Yii auth component
     */
    class AuthHandler
    {
        /**
         * @var ClientInterface
         */
        private $client;

        public function __construct(ClientInterface $client)
        {
            $this->client = $client;
        }

        public function socialLogin()
        {
            $attributes = $this->client->getUserAttributes();
            $email = ArrayHelper::getValue($attributes, 'email');
            $id = ArrayHelper::getValue($attributes, 'id');
            $cityName = ArrayHelper::getValue($attributes, 'city');
            $latitude = ArrayHelper::getValue($attributes, 'latitude');
            $longitude = ArrayHelper::getValue($attributes, 'longitude');
            $name = ArrayHelper::getValue($attributes, 'first_name', '') . ' ' . ArrayHelper::getValue(
                    $attributes,
                    'last_name',
                    ''
                );
            $bdate = ArrayHelper::getValue($attributes, 'bdate');
            $phone = ArrayHelper::getValue($attributes, 'phone');

            /* @var Auth $auth */
            $auth = Auth::find()->where(
                [
                    'source' => $this->client->getId(),
                    'source_id' => $id,
                ]
            )->one();

            if (Yii::$app->user->isGuest) {
                if ($auth) {
                    // login
                    /* @var User $user */
                    $user = $auth->user;
                    Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                } else {
                    // signup
                    if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t(
                                'app',
                                "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.",
                                ['client' => $this->client->getTitle()]
                            ),
                        ]);
                    } else {
                        $password = Yii::$app->security->generateRandomString(6);
                        $user = new User(
                            [
                                'name' => $name,
                                'email' => $email,
                                'password' => Yii::$app->getSecurity()->generatePasswordHash($password),
                            ]
                        );
                        $transactionUser = User::getDb()->beginTransaction();

                        $city = City::find()->where(['name' => $cityName])->one();
                        if (!$city) {
                            $city = new City(
                                [
                                    'name' => $cityName,
                                    'lat' => $latitude,
                                    'long' => $longitude,
                                ]
                            );
                            $transactionCity = City::getDb()->beginTransaction();
                        }

                        $profile = new Profiles(
                            [
                                'bd' => $bdate,
                                'phone' => $phone,
                                'city_id' => $city->id,
                                'user_id' => $user->id,
                            ]
                        );
                        $transactionProfile = Profiles::getDb()->beginTransaction();

                        if ($user->save()) {
                            $auth = new Auth(
                                [
                                    'user_id' => $user->id,
                                    'source' => $this->client->getId(),
                                    'source_id' => (string)$id,
                                ]
                            );

                            if ($auth->save()) {
                                $transactionUser->commit();
                                $transactionCity->commit();
                                $transactionProfile->commit();
                                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                            } else {
                                Yii::$app->getSession()->setFlash('error', [
                                    Yii::t('app', 'Unable to save {client} account: {errors}', [
                                        'client' => $this->client->getTitle(),
                                        'errors' => json_encode($auth->getErrors()),
                                    ]),
                                ]);
                            }
                        } else {
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', 'Unable to save user: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($user->getErrors()),
                                ]),
                            ]);
                        }
                    }
                }
            } else {
                // user already logged in
                if (!$auth) {
                    // add auth provider
                    $auth = new Auth(
                        [
                            'user_id' => Yii::$app->user->id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]
                    );
                    if ($auth->save()) {
                        /** @var User $user */
                        $user = $auth->user;
                        Yii::$app->getSession()->setFlash('success', [
                            Yii::t('app', 'Linked {client} account.', [
                                'client' => $this->client->getTitle()
                            ]),
                        ]);
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to link {client} account: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($auth->getErrors()),
                            ]),
                        ]);
                    }
                } else {
                    // there's existing auth
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t(
                            'app',
                            'Unable to link {client} account. There is another user using it.',
                            ['client' => $this->client->getTitle()]
                        ),
                    ]);
                }
            }
        }
    }
