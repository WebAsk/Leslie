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

class access extends base {

    function __construct($view, $model = '\\framework\\models\\account') {
        
        parent::__construct($view, $model);
        
        $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['TRACK'] = false;

        
    }

    function index () {
        
        if (!empty($_SESSION['code'])) {
            
            $user = $this->model->selectnoview(
                'SELECT t.admin'
                . ' FROM users AS u, user_types AS t'
                . ' WHERE u.code = :code'
                . ' AND t.id = u.type',
                ['code' => $_SESSION['code']]
            );
            
            if (!empty($user['admin'])) {
                header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/admin');
            } else {
                header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/account');
            }
            
            exit;
            
        }
        
        if (!isset($_COOKIE['cookie'])) {
            \leslie::alert(_('Cookie storage is disabled on your browser'));
        }
        
        $this->view->description = _('Log in to your Account');
        
        $this->view->fields = $this->model->sel(
            'SELECT f.*'
            . ' FROM item_types AS t, field_types AS f, item_types_fields AS tf'
            . ' WHERE tf.id_field_type IN(SELECT id FROM field_types WHERE f.site = 1)'
            . ' AND t.accounts = 1'
            . ' AND tf.id_content_type = t.id'
            . ' AND tf.id_field_type = f.id'
            . ' ORDER BY f.`order`'
        );
       //echo '<pre>'; print_r($this->view->fields); echo '</pre>'; die;
       
       $this->view->template = 'default/only-main';
       $this->view->name = 'access';
       
       $this->view->action = 'login';

    }

    function signup () {
        
        unset($_POST['action']);

        if (!isset($_POST['items'])) {
            \leslie::$alerts['danger'][] = \leslie::translate('invalid request');
            unset($_REQUEST['location']);
            return;
        }
        
        $this->view->data = $_POST['items'];
        
        if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH'] && !$this->reCaptcha($_POST['g-recaptcha-response'])['success']) {
            \leslie::$alerts['danger'][] = _('Wrong captcha code');
            unset($_REQUEST['location']);
            return;
        }

        if(!$email = filter_var($_POST['items']['users']['email'], FILTER_SANITIZE_EMAIL)) {
           \leslie::$alerts['danger'] = _('Wrong e-mail address');
           unset($_REQUEST['location']);
           return;
        }

        if (!empty($this->model->selectnoview('SELECT id FROM users WHERE email = :email', ['email' => $email]))) {
           \leslie::$alerts['danger'] = _('This e-mail address exist yet');
           unset($_REQUEST['location']);
           return;
        }
        
        if (!preg_match($GLOBALS['PROJECT']['PASSWORD']['REGEX'], $_POST['items']['users']['password'])) {
            
            \leslie::$alerts['danger'][] = \leslie::translate($GLOBALS['PROJECT']['PASSWORD']['INFO']);
            unset($_REQUEST['location']);
            return;
        }

        if (isset($_POST['items']['users']['password_repeat']) && $_POST['items']['users']['password_repeat'] != $_POST['items']['users']['password']) {
           \leslie::$alerts['danger'] = _('Passwords do not match');
           unset($_REQUEST['location']);
           return;
        }
        
        $user = array();
        
        $fields = array();
        //echo '<pre>'; print_r($this->view->fields); echo '</pre>'; die;
        $user['name'] = \leslie::translate($this->model->item['type']['singular'] . ' registered') . ' ' . date('d/m/Y H:i');
        
        foreach ($this->view->fields as $key => $item) {
            
            if ($item['required'] && empty($_POST['items']['items_fields'][$item['id']])) {
               \leslie::$alerts['danger'] = 'invalid ' . $item['name'];
               unset($_REQUEST['location']);
               return;
            }
            
            if ($item['name'] == 'name') {
               $user['name'] = $_POST['items']['items_fields'][$item['id']];
            }
            
            if ($item['name'] == 'surname') {
               $user['name'] .= ' ' . $_POST['items']['items_fields'][$item['id']];
            }
            
            $fields[] = array(
                'id_language' => 1,
                'id_type' => $item['id'],
                'value' => $_POST['items']['items_fields'][$item['id']]
            );
            
        }
        
        $user['code'] = $this->model->generate_code('users');
        
        $this->model->insert('users', array(
            
            'email' => $email,
            'password' => $this->model->password($_POST['items']['users']['password']),
            'code' => $user['code'],
            'insert' => date('Y-m-d H:i:s')
            
        ));

        $user['id'] = $this->model->lastInsertId();

        $this->model->insert($this->model->item['type']['prefix'] . '_list', array(
            
            'id_user' => $user['id'],
            'id_type' => $this->model->item['type']['id'],
            'name' => $user['name'],
            'code' => $this->model->generate_code($this->model->item['type']['prefix'] . '_list')
                
        ));

        $user['content'] = $this->model->lastInsertId();

        $this->model->update('users', ['content' => $user['content']], 'id = ' . $user['id']);

        $this->model->insert($this->model->item['type']['prefix'] . '_languages', array(
            'id_content' => $user['content'],
            'title' => $user['name'],
            'id_language' => 1,
            'insert' => date('Y-m-d H:i:s')
        ));

        foreach ($fields as $item) {
           $item['id_content'] = $user['content'];
           $item['id_item_type'] = $this->model->item['type']['id'];
           $this->model->insert('items_fields', $item);
        }

        $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $user['code'];
        
        $mail = new \mail();
            
        $mail->to = $email;

        $mail->subject = _('SIGNUP_SUBJECT');
        
        $user['name'] = htmlentities($user['name']);
        
        $mail->message = '<p>'
        . \leslie::translate('Dear')
        . ' ' . $user['name'] . '<br /><br />'
        . _('SIGNUP_MESSAGE') . '<br />'
        . ':<br /><br />'
        . '<a href="' . $href . '">' . $href . '</a>'
        . '</p>';

        $mail->send();
        
        \leslie::$alerts['success'][] = \leslie::translate('Yourf registration has gonef well');

        if ($mail->sent) {
            
            $mail = new \mail();
            
            $mail->to = $GLOBALS['PROJECT']['E-MAIL']['REGISTRATIONS'];

            $mail->subject = \leslie::translate('New registration on ') . ' ' . $GLOBALS['PROJECT']['NAME'];

            $mail->message = '<ul>'
            . '<li>' . \leslie::translate('Name') . ': ' . $user['name'] . '</li>'
            . '<li>' . \leslie::translate('E-mail') . ': <a href="mailto:' . $email . '">' . $email . '</a></li>'
            . '</ul>'
            . '<a href="' . $GLOBALS['PROJECT']['URL']['ADMIN'] . '/users">' . $GLOBALS['PROJECT']['URL']['ADMIN'] . '/users</a>';
            
            $mail->send();

            \leslie::$alerts['success'][] = \leslie::translate('Please check yourf email and confirm');
            
        } else {
            
            \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
            \leslie::$alerts['danger'][] = \leslie::translate('please contact') . ': ' . \functions::antispam_contact($GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR']);

        }

        unset($this->view->data);
        
        unset($_REQUEST['location']);
        //return;

    }

    function confirm ($code, $table = 'users') {
        
        $id = $this->model->selectnoview(
            'SELECT id'
            . ' FROM ' . $table
            . ' WHERE code = :code'
            . ' ORDER BY `insert` DESC, id DESC'
            . ' LIMIT 0, 1', 
            ['code' => $code], 
            \PDO::FETCH_COLUMN
        );
        
        $what = substr($table, 0, -1);
        
        if (isset($id)) {
            
            $this->model->update($table, ['active' => 1, 'update' => date('Y-m-d H:i:s')], 'id = ' . $id);
            
            \leslie::$alerts['success'][] = \leslie::translate($what . ' enabled');
            
        } else {
            
            \leslie::$alerts['danger'][] = \leslie::translate($what . ' inexistent') . '!';
            
        }
        
    }
    
    function login () {
        
        if (!isset($_POST['items'])) {
            \leslie::$alerts['danger'] = _('Invalid parameters');
            unset($_REQUEST['location']);
            return;
        }

        if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH'] && !$this->reCaptcha($_POST['g-recaptcha-response'])['success']) {
            \leslie::$alerts['danger'] = _('Wrong captcha code');
            unset($_REQUEST['location']);
            return;
        }

        if(!$email = filter_var($_POST['items']['users']['email'], FILTER_SANITIZE_EMAIL)) {
            \leslie::$alerts['danger'] = _('Wrong e-mail address');
            unset($_REQUEST['location']);
            return;
        }
        
        if (!preg_match($GLOBALS['PROJECT']['PASSWORD']['REGEX'], $_POST['items']['users']['password'])) {
            
            \leslie::$alerts['danger'][] = _($GLOBALS['PROJECT']['PASSWORD']['INFO']);
            unset($_REQUEST['location']);
            return;
        }
        
        $user = $this->model->selectnoview(
            'SELECT u.id, u.code, u.active, u.disabled, u.email, a.title'
            . ' FROM users AS u'
                . ' LEFT JOIN ' . $this->model->item['type']['prefix'] . '_languages AS a ON a.id_content = u.content'
            . ' WHERE u.email = :email'
            . ' AND u.password = :password', 
            array(
                
                'email' => $email, 
                'password' => $this->model->password($_POST['items']['users']['password'])
                
            )
        );
        
        if (!isset($user['code'])) {
            \leslie::$alerts['danger'][] = _('Account not found');
            unset($_REQUEST['location']);
            return;
        }
        
        if (empty($user['title'])) { $user['title'] = _('User'); }
        
        if ($user['disabled']) {

            unset($_REQUEST['location']);
            \leslie::$alerts['danger'][] = _('This Account has been disabled');
            \leslie::$alerts['danger'][] = _('Please contact') . ': ' . \functions::antispam_contact($GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR']);
            return;
            
        }

        if (!$user['active']) {
            
            $link = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $user['code'];
            
            $mail = new \mail();
            
            $mail->to = $user['email'];
            
            $mail->subject = _('Confirm your e-mail address');
            
            $mail->message = '<p>'
            . \leslie::translate('Dear') . ' ' . htmlentities($user['title']) . '<br /><br />'
            . htmlentities(_('You have not confirmed your e-mail address. For activate your account you need to confirm your e-mail address. Follow the link below to confirm.')) . ':<br /><br />'
            . '<a href="' . $link . '">' . $link . '</a>'
            . '</p>';
            
            $mail->send();
            
            \leslie::$alerts['warning'][] = _('Unconfirmed e-mail address');
            
            if ($mail->sent) {
                
                \leslie::$alerts['warning'][] = _('Please check your inbox and confirm') . '.';
                
            } else {
                
                \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
                \leslie::$alerts['danger'][] = _('Please contact') . ': ' . $GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR'];
                
            }

            unset($_REQUEST['location']);
            return;
            
        }
        
        if ($GLOBALS['PROJECT']['SESSION']['CHECK']) {
            
            $session = $this->model->selone(
                'SELECT id, `insert`'
                . ' FROM sessions'
                . ' WHERE user = ' . $user['id']
                . ' ORDER BY `insert` DESC'
                . ' LIMIT 0, 1'
            );
            //print_r($session); exit;

            if (empty($session['id'])) {

                $session['code'] = $this->model->session($user['id'], 0);

                $link = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $session['code'] . '/sessions';

                $mail = new \mail();

                $mail->to = $user['email'];

                $mail->subject = _('First access');

                $mail->message = '<p>'
                . _('Dear') . ' ' . htmlentities($user['title']) . '<br /><br />'
                . _('For security reason you have to confirm your first access on platform following the next link')
                . ':<br /><br />'
                . '<a href="' . $link . '">' . $link . '</a>'
                . '</p>';

                $mail->send();

                \leslie::$alerts['warning'][] = _('This is your first access');

                if ($mail->sent) {

                    \leslie::$alerts['warning'][] = _('Please check your inbox and confirm');

                } else {

                    \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
                    \leslie::$alerts['danger'][] = _('Please contact') . ': ' . $GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR'];

                }
                
                unset($_REQUEST['location']);
                return false;

            }
            
            if ($GLOBALS['PROJECT']['SESSION']['AGENT']) {
                
                $session = $this->model->selone(
                    'SELECT id, code, active, `insert`'
                    . ' FROM sessions'
                    . ' WHERE user = ' . $user['id']
                    . ' AND agent = "' . $_SERVER['HTTP_USER_AGENT'] . '"'
                    . ' ORDER BY `insert` DESC'
                    . ' LIMIT 1'
                );
                //print_r($session); exit;
                
                if (empty($session)) {
                    
                    $session['code'] = $this->model->session($user['id'], 0);

                    $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $session['code'] . '/sessions';
                    
                    $mail = new \mail();
            
                    $mail->to = $user['email'];

                    $mail->subject = _('First access with new device');

                    $mail->message = '<p>'
                    . _('Dear') . ' ' . htmlentities($user['title']) . '<br /><br />'
                    . _('System has detected an attempt to access the platform through an ever verified device or application. Please confirm this new access by clicking on the following link') . ':'
                    . '</p>'
                    . '<a href="' . $href . '">' . $href . '</a>'
                    . '<p>'
                    . _('If you are not logged in, do not click on the link and delete this message. In case of further attempts, contact us') . '.'
                    . '</p>';

                    $mail->send();
                    
                    \leslie::$alerts['warning'][] = _('First access with new device');

                    if ($mail->sent) {

                        \leslie::$alerts['warning'][] = \leslie::translate('Please check your inbox and confirm');

                    } else {

                        \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
                        \leslie::$alerts['danger'][] = _('Please contact') . ': ' . $GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR'];

                    }
                    
                    unset($_REQUEST['location']);
                    return false;
                    
                } else if (empty($session['active'])) {

                    $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $session['code'] . '/sessions';
                    
                    $mail = new \mail();
            
                    $mail->to = $user['email'];

                    $mail->subject = _('Access from new device unconfirmed');

                    $mail->message = '<p>'
                    . \leslie::translate('Dear') . ' ' . htmlentities($user['title']) . '<br /><br />'
                    . _('System has detected a further attempt at access never confirmed by a new device or application. Please unlock the check by clicking on the following link') .':'
                    . '</p>'
                    . '<a href="' . $href . '">' . $href . '</a>'
                    . '<p>'
                    . _('If you are not logged in, do not click on the link and delete this message. In case of further attempts, contact us') . '.'
                    . '</p>';

                    $mail->send();
                    
                    \leslie::$alerts['warning'][] = _('Access from new device unconfirmed');

                    if ($mail->sent) {

                        \leslie::$alerts['warning'][] = _('Please check your inbox and confirm');

                    } else {

                        \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
                        \leslie::$alerts['danger'][] = _('Please contact') . ': ' . $GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR'];

                    }
                    
                    unset($_REQUEST['location']);
                    return false;

                }
            }

            if ($GLOBALS['PROJECT']['SESSION']['IP']) {
                
                $session = $this->model->selone(
                    'SELECT id, code, active, `insert`'
                    . ' FROM sessions'
                    . ' WHERE user = ' . $user['id']
                    . ' AND ip = "' . $_SERVER['REMOTE_ADDR'] . '"'
                    . ' ORDER BY `insert` DESC'
                    . ' LIMIT 1'
                );
                //print_r($session); exit;
                
                if (empty($session)) {
                    
                    $session['code'] = $this->model->session($user['id'], 0);

                    $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $session['code'] . '/sessions';
                    
                    $mail = new \mail();
            
                    $mail->to = $user['email'];

                    $mail->subject = _('First access from a new network');
                    
                    $mail->message = '<p>'
                    . _('Dear') . ' ' . htmlentities($user['title']) . '</p>'
                    . '<p>'
                    . _('The system detected an attempt to access through a network that has never been verified. For security reasons it is necessary to confirm this new access by clicking on the following link') . ':'
                    . '</p>'
                    . '<a href="' . $href . '">' . $href . '</a>'
                    . '<p>'
                    . _('If you are not logged in, do not click on the link and delete this message. In case of further attempts, contact us')
                    . '</p>';
                    
                    $mail->send();
                    
                    \leslie::$alerts['warning'][] = _('First access from a new network');

                    if ($mail->sent) {

                        \leslie::$alerts['warning'][] = _('Please check your inbox and confirm');

                    } else {

                        \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
                        \leslie::$alerts['danger'][] = _('Please contact') . ': ' . \functions::antispam_contact($GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR']);

                    }
                    
                    unset($_REQUEST['location']);
                    return false;

                } else if ($session['active'] != 1) {

                    $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/confirm/' . $session['code'] . '/sessions';
                    
                    $mail = new \mail();
            
                    $mail->to = $user['email'];

                    $mail->subject = _('Access from an unconfirmed network');
                    
                    $mail->message = '<p>'
                    . _('Dear') . ' ' . htmlentities($user['title']) . '<br /><br />'
                    . htmlentities(_('The system has detected a further attempt to access from a network that has never been verified. For security reasons, you must unlock the control by clicking on the following link')) . '.'
                    . ':</p>'
                    . '<a href="' . $href . '">' . $href . '</a>'
                    . '<p>'
                    . _('If you are not logged in, do not click on the link and delete this message. In case of further attempts, contact us')
                    . '</p>';
                    
                    $mail->send();
                    
                    \leslie::$alerts['warning'][] = _('Access from an unconfirmed network');

                    if ($mail->sent) {

                        \leslie::$alerts['warning'][] = _('Please check your inbox and confirm');

                    } else {

                        \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
                        \leslie::$alerts['danger'][] = _('Please contact') . ': ' . \functions::antispam_contact($GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR']);

                    }
                    
                    unset($_REQUEST['location']);
                    return false;

                }
            }
            
        }

        setcookie(
            "user",
            $user['code'],
            time() + (10 * 365 * 24 * 60 * 60),
            '/'
        );

        $_SESSION['code'] = $user['code'];
        
        if (time() - strtotime($session['insert']) >= $GLOBALS['PROJECT']['SESSION']['TIME']) {
            $session['code'] = $this->model->session($user['id']);
        } else {
            $this->model->update('sessions', ['update' => date('Y-m-d H:i:s')], 'id = ' . $session['id']);
        }
        
        if ($GLOBALS['PROJECT']['SESSION']['NOTICE']) {
        
            $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index/block/' . $session['code'];

            $mail = new \mail();

            $mail->to = $user['email'];

            $mail->subject = _('New account access');

            $mail->message = '<p>' . _('Dear') . ' ' . htmlentities($user['title']) . '</p>'
            . '<p>' . htmlentities(_('New access to your Account is just been detected. If you are not logged in and you want to block it, you can do so by clicking the following link. Otherwise we recommend avoiding the operation because a further security check would be necessary later')) . '.</p>'
            . '<a href="' . $href . '">' . $href . '</a>';

            $mail->send();
            
        }

    }
    
    function block ($code) {
        
        $id = $this->model->selectnoview(
            'SELECT id'
            . ' FROM sessions'
            . ' WHERE code = :code'
            . ' ORDER BY `insert` DESC, id DESC'
            . ' LIMIT 0, 1', 
            ['code' => $code], 
            \PDO::FETCH_COLUMN
        );
        
        if (isset($id)) {
            
            $this->model->update('sessions', ['active' => 0, 'update' => date('Y-m-d H:i:s')], 'id = ' . $id);
            
            \leslie::$alerts['success'][] = _('Access has been blocked');
            
        } else {
            
            \leslie::$alerts['danger'][] = _('Access not found');
            
        }
        
    }
    
    function recovery () {

        if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH'] && !$this->reCaptcha($_POST['g-recaptcha-response'])['success']) {
           \leslie::$alerts['danger'][] = _('Wrong captcha code');
           unset($_REQUEST['location']);
           return;
        }
        
        if (!$email = filter_var($_POST['items']['users']['email'], FILTER_SANITIZE_EMAIL)) {
           \leslie::$alerts['danger'][] = _('Wrong e-mail address');
           unset($_REQUEST['location']);
           return;
        }

        if (empty($user = $this->model->selone(
                
            'SELECT u.code, a.title'
            . ' FROM users As u'
                . ' LEFT JOIN ' . $this->model->item['type']['prefix'] . '_languages AS a ON a.id_content = u.content'
            . ' WHERE u.email = "' . $email . '"'
                
        ))) {
            
           unset($_REQUEST['location']);
           \leslie::$alerts['danger'][] = _('Account not found');
           return;
           
        }
        
        setcookie('reset', $user['code'], time() + 60 * 60, '/access/');
        
        if (empty($user['title'])) { $user['title'] = \leslie::translate('User'); }
       
        $mail = new \mail();
            
        $mail->to = $email;

        $mail->subject = _('Reset your Account password');
        
        $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/access/reset';

        $mail->message = '<p>' . \leslie::translate('Dear'). ' ' . htmlentities($user['title']) . ',</p>'
        . '<p>'. _('To reset your Account password follow the link below') . ':</p>'
        . '<a href="' . $href . '">' . $href . '</a>'
        . '<p>' . _('If you are not prompting to reset your password, please ignore and delete this message. Nobody has come to know your access data.') . '</p>';

        $mail->send();

        if ($mail->sent) {

            \leslie::$alerts['warning'][] = _('Please check your inbox and follow the instructions');

        } else {

            \leslie::$alerts['danger'][] = \leslie::translate($mail->error);
            \leslie::$alerts['danger'][] = _('Please contact') . ': ' . $GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR'];

        }
        
        unset($_REQUEST['location']);
        return;
        
    }

    function reset () {
        
        $this->index();
        
        $this->view->description = _('Reset your Account password');
        
        if (!empty($_COOKIE['reset'])) {
            
            \leslie::$alerts['success'][] = _('Type the new password for your account');
            $this->view->action = 'password';
            
        } else {
            
            \leslie::$alerts['danger'][] = _('Impossible to reset password. Please retry to recovery, else contact') . ': ' . \functions::antispam_contact($GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR']);
            
        }
        
        return;

    }
    
    function password () {
        
        if (!isset($_POST['items'])) {
            \leslie::$alerts['danger'] = _('Invalid request');
            unset($_REQUEST['location']);
            return;
        }
        
        if (!isset($_COOKIE['reset'])) {
            \leslie::$alerts['danger'] = _('Impossible to reset password. Please retry to recovery, else contact') . ': ' . \functions::antispam_contact($GLOBALS['PROJECT']['E-MAIL']['ADMINISTRATOR']);
            unset($_REQUEST['location']);
            return;
        }
        
        $code = $_COOKIE['reset'];
        
        if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH'] && !$this->reCaptcha($_POST['g-recaptcha-response'])['success']) {
            \leslie::$alerts['danger'][] = _('Wrong captcha code');
            unset($_REQUEST['location']);
            return;
        }

        if(!$email = filter_var($_POST['items']['users']['email'], FILTER_SANITIZE_EMAIL)) {
           \leslie::$alerts['danger'] = _('Wrong e-mail address');
           unset($_REQUEST['location']);
           return;
        }
        
        $user = $this->model->selectnoview(
            'SELECT u.id, a.title'
            . ' FROM users As u'
            . ' LEFT JOIN ' . $this->model->item['type']['prefix'] . '_languages AS a ON a.id_content = u.content'
            . ' WHERE u.code = :code'
            . ' AND u.email = :email', 
            [
                'code' => $code,
                'email' => $email
            ]
        );
        //print_r($user); exit;
        
        if (empty($user['title'])) { $user['title'] = \leslie::translate('User'); }
        
        if (empty($user)) {
            \leslie::$alerts['danger'] = "user not foundm";
            unset($_REQUEST['location']);
            return;
        }
        
        if (!preg_match($GLOBALS['PROJECT']['PASSWORD']['REGEX'], $_POST['items']['users']['password'])) {
            
            \leslie::$alerts['danger'][] = $GLOBALS['PROJECT']['PASSWORD']['INFO'];
            unset($_REQUEST['location']);
            return;
        }

        if (isset($_POST['items']['users']['password_repeat']) && $_POST['items']['users']['password_repeat'] != $_POST['items']['users']['password']) {
           \leslie::$alerts['danger'] = _('Passwords do not match');
           unset($_REQUEST['location']);
           return;
        }
        
        $this->model->update(
            'users', 
            [
                'password' => $this->model->password($_POST['items']['users']['password']), 
                'update' => date('Y-m-d H:i:s')
            ],
            'id = ' . $user['id']
        );

        setcookie('reset', null, -1, '/');
        unset($_COOKIE['reset']);
        
        $mail = new \mail();
            
        $mail->to = $email;

        $mail->subject = _('Your Account password has been updated');

        $mail->message = '<p>' . \leslie::translate('Dear') . ' ' . htmlentities($user['title']) . '</p>' . PHP_EOL
        . '<p>' . \leslie::translate('Your account password has just been updated. If you believe there has been an error, contact the administrators immediately') . '.</p>';

        $mail->send();
        
        header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/access?alert=success&message=' . \leslie::translate('password updatedf') . '. ' . \leslie::translate('Please access'));
        exit;
        
    }
   
}
