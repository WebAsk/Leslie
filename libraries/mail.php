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

defined('PROJECT_ROOT') or die;

class mail {
    
    public $from;
    public $to;
    public $subject;
    public $message;
    
    public $sent = false;
    public $error;
    
    function __construct() {
        
        $this->from = 'noreply@' . $GLOBALS['PROJECT']['DOMAIN'];
        
    }
    
    function send ($service = null, $template = 'index') {
        
        switch ($service) {
            
            case 'sendinblue':
                
                
                break;
                
            default:
                
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
                
                $mail->setFrom($this->from, $GLOBALS['PROJECT']['NAME']);

                $mail->addAddress($this->to);
                $mail->isHTML(true);

                $mail->Subject = $this->subject;
                
                ob_start();
                include FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR . $template . '.php';
                $template = ob_get_clean();

                $mail->Body = str_replace('{$message}', $this->message, $template);

                $this->sent = $mail->send();
                
                if (!$this->sent) {
                    $this->error = $mail->ErrorInfo;
                }
            
        }

        
    }
    
}

