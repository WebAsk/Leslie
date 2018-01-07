<?php

/* 
 * Copyright (C) 2017 WebAsk di Francesco Luti
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class controller {
   
   
    function __construct($view, $model = null){

        
        $this->view = $view;
        
        if (!empty($model)) {
            
            if (is_object($model)) {
                $this->model = $model;
            } else {
                $this->model = new $model;
            }

            if (isset($_COOKIE['user']) && $this->model->selectnoview('SELECT t.admin FROM users AS u, user_types AS t WHERE u.code = :code AND t.id = u.type', ['code' => $_COOKIE['user']], \PDO::FETCH_COLUMN)) {
                $GLOBALS['PROJECT']['USER']['ADMIN'] = true;
            }
            
        }

        

    }
   
    protected function mail ($to, $subject, $message, $to_name = null, $from = null) {

        include_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
        include_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.smtp.php';

        $mail = new \PHPMailer;

        $mail->isSMTP();
        $mail->Host = $GLOBALS['PROJECT']['SMTP']['HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $GLOBALS['PROJECT']['SMTP']['USERNAME'];
        $mail->Password = $GLOBALS['PROJECT']['SMTP']['PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $GLOBALS['PROJECT']['SMTP']['PORT'];
        if (empty($from)) {
           $mail->setFrom('noreply@' . $GLOBALS['PROJECT']['DOMAIN'], $GLOBALS['PROJECT']['NAME']);
        } else {
           $mail->setFrom($from);
        }

        $mail->addAddress($to, $to_name);
        $mail->isHTML(true);

        $mail->Subject = $subject;

        ob_start();
        include FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . 'index.php';
        $template = ob_get_clean();

        $mail->Body = str_replace('{$message}', $message, $template);

        return $mail->send();
    }
   
    protected function resultset_to_indexset ($resultset, $index, $value) {
        $return = array();
        foreach ($resultset as $result) {
           $return[$result[$index]] = $result[$value];
        }
        return $return;
    }

    protected function reCaptcha ($response) {

        return json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $GLOBALS['PROJECT']['CAPTCHA']['KEY']['server'] . "&response=" . $response . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);

    }
   
}