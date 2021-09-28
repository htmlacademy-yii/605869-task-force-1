<?php

    namespace frontend\helpers;

    use Yii;

    class MailSender
    {
        /**
         * @param string $setTo
         * @param string $subject
         */
        public static function mail(string $setTo, string $subject)
        {
            $result = Yii::$app->mailer->compose()
                ->setFrom('test12345box@gmail.com')
                ->setTo('$setTo')
                ->setSubject('$subject')
                ->setTextBody('$subject')
                ->setHtmlBody('<b>$subject</b>')
                ->send();
        }
    }
