<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class UserLoginForm extends Model
{
	public $email;
	public $password;
	private $_user;
	
	public function attributeLabels()
	{
		return [
			'email' => 'email',
			'password' => 'Пароль'
		];
	}
	
	public function rules()
	{
		return [
            [['email', 'password'], 'safe'],
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            ['password', 'validatePassword'],
        ];
	}
	
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Неправильный email или пароль');
			}
		}
	}
	
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser());
		}
	}
	
	/**
	 * @return User|null
	 */
	public function getUser()
	{
		if ($this->_user === null) {
			$this->_user = User::find()->where(['email' => $this->email])->one();
		}
		
		return $this->_user;
	}
}