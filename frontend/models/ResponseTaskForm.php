<?php
    
    namespace frontend\models;
    
    use Yii;
    use yii\base\Model;
    
    class ResponseTaskForm extends Model
    {
        public $payment;
        public $comment;
        
        private Task $task;
        
        public function __construct($task)
        {
            parent::__construct();
            $this->task = $task;
        }
    
        public function rules()
        {
            return [
                [
                    ['payment', 'comment'],
                    'safe'
                ],
                [
                    ['payment', 'comment'],
                    'required',
                    'message' => 'Поле не заполнено.'
                ],
                ['payment', 'integer', 'min' => '1'],
                ['comment', 'trim'],
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'payment' => 'Ваша цена',
                'comment' => 'Комментарий'
            ];
        }
        
        public function createReply()
        {
            if(!$this->validate()) {
                return false;
            }
            
            $reply = new Replies();
            $reply->task_id = $this->task->id;
            $reply->user_id = Yii::$app->user->identity->getId();
            $reply->price = $this->payment;
            $reply->description = $this->comment;
            $reply->status = $reply::STATUS_NEW;
            $reply->save();
            
            return true;
        }
    }