<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
	public $email;
	public $name;
	public $city;
	public $password;
	
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'name', 'city', 'password'], 'required'],
            [['email', 'name', 'city', 'password'], 'safe'],
            [['email', 'name', 'password'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
            [['city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],
            [['password'], 'string', 'min' => 8],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => 'Имя',
            'city' => 'Город',
            'password' => 'Пароль',
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
        $profile->city_id = $this->city;
        
        if (!$profile->save()) {
        	$transaction->rollBack();
        	
        	return false;
		}
        
        $transaction->commit();
        
        return true;
	}
}