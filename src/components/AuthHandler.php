<?php

    namespace TaskForce\components;

    use frontend\models\Auth;
    use frontend\models\City;
    use frontend\models\Profiles;
    use frontend\models\User;
    use Yii;
    use yii\authclient\ClientInterface;
    use yii\helpers\ArrayHelper;
    use yii\web\BadRequestHttpException;
    use yii\db\Exception;

    /**
     * AuthHandler handles successful authentication via Yii auth component
     */
    class AuthHandler
    {
        /**
         * @var ClientInterface
         */
        private ClientInterface $client;

        public function __construct(ClientInterface $client)
        {
            $this->client = $client;
        }

        /**
         * @throws Exception
         * @throws BadRequestHttpException
         */
        public function socialLogin()
        {
            $attributes = $this->client->getUserAttributes();
            $VKId = ArrayHelper::getValue($attributes, 'id');
            $email = ArrayHelper::getValue($attributes, 'email');

            /* @var Auth $auth */
            $auth = Auth::find()->where([
                                            'source' => $this->client->getId(),
                                            'source_id' => $VKId,
                                        ])->one();

            if (!Yii::$app->user->isGuest && !$auth) {
                $this->createAuth(Yii::$app->user, $VKId);

                return;
            }

            if ($auth) {
                /* @var User $user */
                $user = $auth->user;
                Yii::$app->user->login($user);

                return;
            }

            if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                throw new BadRequestHttpException('Пользователь с указанным адресом электронной почты уже существует');
            }

            if ($user = $this->createUser()) {
                Yii::$app->user->login($user);
            }
        }

        private function createUser(): ?User
        {
            $attributes = $this->client->getUserAttributes();
            $VKId = ArrayHelper::getValue($attributes, 'id');
            $email = ArrayHelper::getValue($attributes, 'email');
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
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $password = Yii::$app->security->generateRandomString(6);
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
                if (!$user->save()) {
                    throw new Exception('Ошибка сохранения пользователем');
                }

                $city = $this->getOrCreateCity($cityName, $latitude, $longitude);
                $this->createProfile($bdate, $phone, $city, $user);
                $this->createAuth($user, $VKId);

                return $user;
            } catch (\Throwable $exception) {
                $transaction->rollBack();

                return null;
            }
        }

        /**
         * @param $cityName
         * @param $latitude
         * @param $longitude
         * @return City
         * @throws Exception
         */
        private function getOrCreateCity($cityName, $latitude, $longitude): City
        {
            $city = City::find()->where(['name' => $cityName])->one();
            if (!$city) {
                $city = new City();
                $city->name = $cityName;
                $city->lat = $latitude;
                $city->long = $longitude;
                if (!$city->save()) {
                    throw new Exception('Ошибка сохранения города');
                }
            }

            return $city;
        }

        /**
         * @param $bdate
         * @param $phone
         * @param City $city
         * @param User $user
         * @return void
         * @throws Exception
         */
        private function createProfile($bdate, $phone, City $city, User $user): void
        {
            $profile = new Profiles();
            $profile->bd = $bdate;
            $profile->phone = $phone;
            $profile->city_id = $city->id;
            $profile->user_id = $user->id;
            if (!$profile->save()) {
                throw new Exception('Ошибка сохранения профиля');
            }
        }

        /**
         * @param User $user
         * @param $VKId
         * @return void
         * @throws Exception
         */
        private function createAuth(User $user, $VKId): void
        {
            $auth = new Auth();
            $auth->user_id = $user->id;
            $auth->source = $this->client->getId();
            $auth->source_id = (string)$VKId;

            if (!$auth->save()) {
                throw new Exception('Ошибка при сохранении авторизации');
            }
        }
    }
