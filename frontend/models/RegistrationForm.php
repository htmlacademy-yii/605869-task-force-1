<?php

    namespace frontend\models;

    use frontend\repositories\CityRepository;
    use Yii;
    use yii\base\Model;

    class RegistrationForm extends Model
    {
        public $email;
        public $name;
        public $city;
        public $village;
        public $password;
        public $address;
        public $lat;
        public $long;
        public $kladr;

        /**
         * @return array
         */
        public function rules()
        {
            return [
                [['email', 'name', 'address', 'password'], 'required'],
                [['email', 'name', 'city', 'password', 'lat', 'long', 'kladr', 'address', 'village'], 'safe'],
                [['email', 'name', 'password'], 'string', 'max' => 100],
                [['email'], 'email'],
                [['email'], 'unique', 'targetClass' => User::class],
//            [['city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],
                [['password'], 'string', 'min' => 8],
            ];
        }

        public function attributeLabels()
        {
            return [
                'email' => 'Электронная почта',
                'address' => 'Город проживания',
                'long' => 'Долгота',
                'lat' => 'Широта',
                'city' => 'Город',
                'kladr' => 'Код КЛАДР города',
            ];
        }

        public function createUser()
        {
            $transaction = Yii::$app->db->beginTransaction();

            $user = new User();
            $user->email = $this->email;
            $user->name = $this->name;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);

            if (!$user->save()) {
                $transaction->rollBack();

                return false;
            }

            $profile = new Profiles();
            $profile->user_id = $user->id;

            if (!$this->city && !$this->village) {
                return null;
            }

            $cityName = $this->city ?: $this->village;

            $cityModel = CityRepository::getCityByKladrCode(
                $this->kladr,
                $cityName,
                $this->long,
                $this->lat
            );

            $profile->city_id = $cityModel->id;
            $profile->address = $this->address;


            if (!$profile->save()) {
                $transaction->rollBack();

                return false;
            }

            $siteSettings = new SiteSettings();
            $siteSettings->user_id = $user->id;
            $siteSettings->save();

            $transaction->commit();

            return $user;
        }
    }