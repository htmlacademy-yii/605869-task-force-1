<?php

    namespace frontend\models;

    use Throwable;
    use Yii;
    use yii\base\Model;
    use yii\web\UploadedFile;

    class AccountForm extends Model
    {
        public $avatarUpload;
        public $name;
        public $email;
        public $city;
        public $dateBirthday;
        public $about;
        public $specializations;
        public $phone;
        public $skype;
        public $telegram;
        public $password;
        public $passwordConfirmation;
        public $file;

        public int $showNewMessages;
        public int $showActionsOfTask;
        public int $showNewReview;
        public int $showMyContactsCustomer;
        public int $hideAccount;

        /** @var User */
        private $user;

        /** @var Profiles */
        private $profiles;

        /** @var SiteSettings */
        private $siteSettings;

        public function init()
        {
            $user = User::findOne(Yii::$app->user->getId());
            $this->user = $user;
            $profiles = $this->user->profiles;
            $this->profiles = $profiles;
            $this->avatarUpload = $user->getAvatar();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->city = $this->profiles->city->id;
            $this->dateBirthday = $this->profiles->bd;
            $this->about = $this->profiles->about;
            $this->phone = $this->profiles->phone;
            $this->skype = $this->profiles->skype;
            $this->telegram = $this->profiles->telegram;


            $this->specializations = $user->specializations ? array_column($user->specializations, 'category_id') : [];

            $siteSettings = SiteSettings::findOne(['user_id' => Yii::$app->user->identity->getId()]);

            if (!$siteSettings) {
                $siteSettings = new SiteSettings();
                $siteSettings->user_id = Yii::$app->user->getId();
                $siteSettings->save();
            }

            $this->showNewMessages = $siteSettings->show_new_messages;
            $this->showActionsOfTask = $siteSettings->show_actions_of_task;
            $this->showNewReview = $siteSettings->show_new_review;
            $this->showMyContactsCustomer = $siteSettings->show_my_contacts_customer;
            $this->hideAccount = $siteSettings->hide_account;
        }

        public function rules(): array
        {
            return [
                [['name', 'email'], 'required'],
                ['name', 'string', 'min' => 2, 'max' => 256],
                ['email', 'email'],
                ['about', 'string'],
                [['name', 'about'], 'trim'],
                ['dateBirthday', 'date', 'format' => 'php:Y-m-d'],
                [['skype', 'telegram'], 'string', 'max' => 256],
                [['skype', 'telegram'], 'trim'],
                ['phone', 'string', 'min' => 8, 'max' => 11],
                ['password', 'string', 'min' => 6],
                [
                    'passwordConfirmation',
                    'compare',
                    'compareAttribute' => 'password',
                    'message' => "Пароли не совпадают",
                    'skipOnEmpty' => true
                ],
                [
                    'file',
                    'file',
                    'skipOnEmpty' => true,
                    'extensions' => 'png, jpg, jpeg',
                    'maxFiles' => 6
                ],
                [
                    ['avatarUpload'],
                    'file',
                    'skipOnEmpty' => true,
                    'message' => 'Изображение должно иметь формат jpg, png, jpeg',
                    'extensions' => ['png', 'jpg', 'jpeg'],
                    'maxFiles' => 1
                ],
                ['specializations', 'safe'],
                ['city', 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],
                [
                    [
                        'showNewMessages',
                        'showActionsOfTask',
                        'showNewReview',
                        'showMyContactsCustomer',
                        'hideAccount'
                    ],
                    'integer'
                ],
            ];
        }

        public function attributeLabels(): array
        {
            return [
                "name" => 'Ваше имя',
                "email" => 'Email',
                "city" => 'Город',
                "dateBirthday" => 'День рождения',
                "about" => 'Информация о себе',
                "avatarUpload" => 'Сменить аватар',
                "skype" => 'SKYPE',
                "telegram" => 'TELEGRAM',
                "phone" => 'ТЕЛЕФОН',
                "password" => 'Новый пароль',
                "passwordConfirmation" => 'Повтор пароля',
                "file" => 'Выбрать фотографии',
                "specializations" => 'Специализации',
                "showNewMessages" => 'Новое сообщение',
                "showActionsOfTask" => 'Действие по заданию',
                "showNewReview" => 'Новый отзыв',
                "showMyContactsCustomer" => 'Показывать мои контакты только заказчику',
                "hideAccount" => 'Не показывать мой профиль',
            ];
        }

        public function save()
        {
            $this->saveUserAvatar();
            $this->savePassword();
            $this->saveUserData();
            $this->saveProfileData();
            $this->saveSiteSettingsData();
            $this->saveSpecialization();
        }

        private function savePassword()
        {
            if ($this->password && $this->passwordConfirmation) {
                $this->user->updateAttributes(
                    [
                        'password' => Yii::$app->security->generatePasswordHash($this->password),
                    ]
                );
            }
        }

        private function saveUserData()
        {
            $this->user->updateAttributes(
                [
                    'name' => $this->name,
                    'email' => $this->email,
                ]
            );
        }

        private function saveProfileData()
        {
            $this->profiles->updateAttributes(
                [
                    /*                    'address' => $this->city,
                                        'city_id' => City::findOne(['name' => $this->city])->id,*/
                    'bd' => $this->dateBirthday,
                    'about' => $this->about,
                    'skype' => $this->skype,
                    'telegram' => $this->telegram,
                    'phone' => $this->phone,
                ]
            );
        }

        private function saveSiteSettingsData()
        {
            $this->user->siteSettings->updateAttributes(
                [
                    'show_new_messages' => $this->showNewMessages,
                    'show_actions_of_task' => $this->showActionsOfTask,
                    'show_new_review' => $this->showNewReview,
                    'show_my_contacts_customer' => $this->showMyContactsCustomer,
                    'hide_account' => $this->hideAccount,
                ]
            );
        }

        private function saveSpecialization()
        {
            if ($this->specializations) {
                if ($this->user->role === User::ROLE_CUSTOMER) {
                    $this->user->updateAttributes(['role' => User::ROLE_EXECUTOR]);
                }

                Specialization::deleteAll(['user_id' => $this->user->id]);

                foreach ($this->specializations as $item) {
                    $specialization = new Specialization();
                    $specialization->user_id = $this->user->id;
                    $specialization->category_id = $item;
                    $specialization->save();
                }
            } else {
                $this->user->updateAttributes(['role' => User::ROLE_CUSTOMER]);
            }
        }

        public function saveUserAvatar()
        {
            $uploadPath = Yii::getAlias('@webroot') . '/uploads/avatars';
            $file = UploadedFile::getInstance($this,'avatarUpload');
            if ($file) {

                $fileName = md5(microtime() . $this->user->id);
                $saveFiles = $file->saveAs($uploadPath . '/' . $fileName . '.' . $file->getExtension());

                if ($saveFiles) {
                    $this->profiles->updateAttributes(
                        [
                            'avatar' => $fileName . '.' . $file->getExtension(),
                        ]
                    );

                    if ($this->avatarUpload) {
                        try {
                            unlink($uploadPath . '/' . $this->avatarUpload);
                        } catch (Throwable $e) {
                        }
                    }
                }
            }
        }
    }
    