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

namespace framework\controllers;

class contacts extends base {
   
    public $email;
    public $message;

    function index () {

       $this->view->title = ucfirst(\leslie::translate('contacts'));
       $this->view->name = 'contacts';
       $this->view->template = 'default/contents';
       $this->view->email = $this->email;
       $this->view->message = $this->message;
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/jquery.validate.min.js';
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/localization/messages_it.min.js';
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/additional-methods.min.js';

    }

    function send_email() {

        $this->email = $_POST['email'];
        $this->message = $_POST['message'];

        $recaptcha = $this->reCaptcha($_POST['g-recaptcha-response']);

        if ($recaptcha['success']) {

           if ($this->email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {


              if(!empty($this->message)){

                 if($this->mail($GLOBALS['COMPANY']['EMAIL'], 'Site contact', $this->message, $this->email)) {
                    \leslie::$alerts['success'][] = 'email sent';
                    $this->email = NULL;
                    $this->message = NULL;
                 }else{
                    \leslie::$alerts['danger'][] = 'email unsent';
                 }

              }else{
                 \leslie::$alerts['danger'][] = 'message empty';
              }

           }else{
              \leslie::$alerts['danger'][] = 'invalid email';
           }

        }else{

        \leslie::$alerts['danger'][] = 'invalid captcha';
        }
    }

    function sending () {

       if (isset($_POST['g-recaptcha-response'])) {
          $recaptcha = $this->reCaptcha($_POST['g-recaptcha-response']);
       } else {
          $recaptcha['success'] = true;
       }

       //print_r($_POST); die;

       if ($recaptcha['success']) {

          require_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
          require_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.smtp.php';

          $mail = new \PHPMailer;

          //$mail->SMTPDebug = 3;                               // Enable verbose debug output

          $mail->isSMTP();                                      // Set mailer to use SMTP
          $mail->Host = $GLOBALS['PROJECT']['SMTP']['HOST'];  // Specify main and backup SMTP servers
          $mail->SMTPAuth = true;                               // Enable SMTP authentication
          $mail->Username = $GLOBALS['PROJECT']['SMTP']['USERNAME'];                 // SMTP username
          $mail->Password = $GLOBALS['PROJECT']['SMTP']['PASSWORD'];                           // SMTP password
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = $GLOBALS['PROJECT']['SMTP']['PORT'];                                    // TCP port to connect to

          $mail->setFrom($_POST['email'], $_POST['first_name'] . ' ' . $_POST['last_name']);
          $mail->addAddress($GLOBALS['COMPANY']['EMAIL']);     // Add a recipient
          //$mail->addAddress('ellen@example.com');               // Name is optional
          //$mail->addReplyTo('info@example.com', 'Information');
          //$mail->addCC('cc@example.com');
          //$mail->addBCC('bcc@example.com');

          //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
          //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
          $mail->isHTML(true);                                  // Set email format to HTML

          $mail->Subject = $_POST['subject'];
          $mail->Body = '<ul>';
          foreach ($_POST['body'] as $key => $value) {
             $mail->Body .= '<li><strong>' . $key . '</strong>: ' . $value . '</li>';
          }
          $mail->Body .= '</ul>';
          //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

          if(!$mail->send()) {
             \leslie::$alerts['danger'] = 'email not sent'; 
             echo 'Mailer Error: ' . $mail->ErrorInfo;
          } else {
             if (!empty($_POST['location'])) {
                header('location: ' . $_POST['location']);
                die;
             } else {
                \leslie::$alerts['success'] = 'email sent'; 
                echo 'Message has been sent';
             }
          }
       }
    }

    function ajax () {

       header('Content-Type: application/json');

       if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
          echo json_encode(array('response' => false, 'message' => 'invalid request'));
          die;
       }

       if (isset($_POST['g-recaptcha-response'])) {
          $recaptcha = $this->reCaptcha($_POST['g-recaptcha-response']);
       } else {
          $recaptcha['success'] = true;
       }

       //print_r($_POST); die;

       if (!$recaptcha['success']) {
          echo json_encode(array('response' => false, 'message' => 'invalid recaptcha'));
          die;
       }

       require_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
       require_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'php-mailer' . DIRECTORY_SEPARATOR . 'class.smtp.php';

       $mail = new \PHPMailer;

       //$mail->SMTPDebug = 3;                               // Enable verbose debug output

       $mail->isSMTP();                                      // Set mailer to use SMTP
       $mail->Host = $GLOBALS['PROJECT']['SMTP']['HOST'];  // Specify main and backup SMTP servers
       $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username = $GLOBALS['PROJECT']['SMTP']['USERNAME'];                 // SMTP username
       $mail->Password = $GLOBALS['PROJECT']['SMTP']['PASSWORD'];                           // SMTP password
       $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
       $mail->Port = $GLOBALS['PROJECT']['SMTP']['PORT'];                                    // TCP port to connect to

       $mail->setFrom($_POST['email'], $_POST['first_name'] . ' ' . $_POST['last_name']);
       $mail->addAddress($GLOBALS['COMPANY']['EMAIL']);     // Add a recipient
       //$mail->addAddress('ellen@example.com');               // Name is optional
       //$mail->addReplyTo('info@example.com', 'Information');
       //$mail->addCC('cc@example.com');
       //$mail->addBCC('bcc@example.com');

       //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
       //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
       $mail->isHTML(true);                                  // Set email format to HTML

       $mail->Subject = $_POST['subject'];
       $mail->Body = '<ul>';
       foreach ($_POST['body'] as $key => $value) {
          $mail->Body .= '<li><strong>' . $key . '</strong>: ' . $value . '</li>';
       }
       $mail->Body .= '</ul>';
       //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

       if(!$mail->send()) {
          echo json_encode(array('response' => false, 'message' => 'error'));
       } else {
          echo json_encode(array('response' => true, 'message' => 'ok'));
       }
       die;
    }
   
}
