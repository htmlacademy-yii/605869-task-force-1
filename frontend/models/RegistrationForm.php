<?php

namespace frontend\models;

use Yii;
use yii\base\BaseObject;
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

        $cityModel = City::findOne(['kladr' => $this->kladr]);
        if (!$cityModel) {
            $cityModel = new City();

            if ($this->city) {
                $cityModel->name = $this->city;
            }

            if ($this->village) {
                $cityModel->name = $this->village;
            }

            $cityModel->long = $this->long;
            $cityModel->lat = $this->lat;
            $cityModel->kladr = $this->kladr;
            $cityModel->save();
        }

        $profile->city_id = $cityModel->id;
        $profile->address = $this->address;

        
        if (!$profile->save()) {
        	$transaction->rollBack();

        	return false;
		}
        
        $transaction->commit();
        
        return $user;
	}
}