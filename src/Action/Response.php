<?php
    
    
    namespace TaskForce\Action;
    
    
    use frontend\models\Replies;
    use frontend\models\User;
    use Yii;
    
    /**
     * Class Response
     * @package TaskForceAction
     * Класс наследуется от абстрактного, возвращает действие "Откликнуться"
     */
    class Response extends AbstractSelectingAction
    {
        /**
         * @return string|null
         * метод - для человекопонятного названия действия
         */
        public function getActionTitle($task_id)
        {
            if (User::findOne(Yii::$app->user->identity->getId())->role === User::ROLE_EXECUTOR &&
                empty(Replies::findAll(
                    [
                        'task_id' => $task_id,
                        'user_id' => Yii::$app->user->identity->getId()
                    ]
                ))) {
                return 'Откликнуться';
            }
            
            return null;
        }
        
        /**
         * @return string|null
         * метод - для машинного названия действия
         */
        public function getActionCode()
        {
            return 'response';
        }
        
        /**
         * @param $idPerformer
         * @param $idCustomer
         * @param $idUser
         * @return bool
         * метод для проверки прав на совершение действия по отклику
         */
        public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
        {
            return ($idUser !== $idCustomer);
        }
    }