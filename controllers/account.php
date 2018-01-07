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

class account extends \controller {
   
    protected $user;
    protected $item;
    private $session;

    function __construct($view, $model = null) {
        
        parent::__construct($view, $model);

        if (isset($_SESSION['code'])) {
            $code = $_SESSION['code'];
        } else if ($GLOBALS['PROJECT']['SESSION']['COOKIE'] && isset($_COOKIE['user'])) {
            $code = $_COOKIE['user'];
        } else {
            $this->out();
        }

        $this->user = $this->model->user('code', $code);

        if (empty($this->user['id'])) {
            $this->out();
        }

        $session = $this->model->selectnoview(
            'SELECT id, active, `update`, `insert`'
            . ' FROM sessions'
            . ' WHERE user = :user'
            . ' AND ip = :ip'
            . ' AND agent = :agent'
            . ' ORDER BY `insert` DESC'
            . ' LIMIT 0, 1',
            [
                'user' => $this->user['id'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'agent' => $_SERVER['HTTP_USER_AGENT']
            ]
        );
        //print_r($session); exit;
        
        if (empty($session)) { $this->out('session not foundf'); }
        
        if (empty($session['active'])) { $this->out('session not active'); }
        
        empty($session['update'])? $session_time = $session['insert']: $session_time = $session['update'];
        
        if ($GLOBALS['PROJECT']['SESSION']['CHECK'] && time() - strtotime($session_time) > $GLOBALS['PROJECT']['SESSION']['TIME']) {

            $this->out('session expiredf');

        }

        $this->model->update('sessions', ['update' => date('Y-m-d H:i:s')], 'id = ' . $session['id']);

        $this->view->user = $this->user;
        $this->view->current = 'account';

        //$this->view->template .= DIRECTORY_SEPARATOR . 'no-slider';
    }
    
    function index () {
        $this->profile();
    }

    function dashboard () {

        $this->view->title = 'Dashboard';
        $this->view->description = 'Riepilogo delle tua attività';
        
        $this->view->item = $this->model->selone('SELECT code FROM ' . $this->user['prefix'] . '_list WHERE id = ' . $this->user['content']);

        $this->view->fields = $this->model->sel(
            'SELECT field_types.id, field_types.label, field_types.icon'
            . ' FROM field_types, item_types_fields'
            . ' WHERE item_types_fields.id_content_type = ' . $this->user['item_type_id']
            . ' AND item_types_fields.id_field_type = field_types.id'
            . ' ORDER BY `order`'
        );
        //echo '<pre>'; print_r($this->view->fields); echo '</pre>'; exit;
        
        $this->view->item['fields'] = $this->model->fields($this->model->item['type']['id'], $this->user['content']);
        //echo '<pre>'; print_r($this->view->item['fields']); echo '</pre>'; exit;

        $this->view->name = 'account/dashboard';
    }
    
    function orders () {
        
        $orders = array();
        
        $orders['type'] = $this->model->query(
            'SELECT id, prefix'
            . ' FROM item_types'
            . ' WHERE plural = "orders"'
        )->fetch(\PDO::FETCH_ASSOC) or die('order type select error');
        
        if (empty($orders['type']['prefix'])) { $orders['type']['prefix'] = 'items'; }
        
        $this->view->name = 'account' . DIRECTORY_SEPARATOR . 'orders';
        
        $this->view->title = 'Ordini';
        $this->view->description = 'Lista dei tuoi ordini';
        
        $this->view->orders = $this->model->select(
            'SELECT li.code, la.title, la.insert, s.value AS state'
            . ' FROM item_types AS t, ' . $orders['type']['prefix'] . '_list AS li, ' . $orders['type']['prefix'] . '_languages As la, item_states AS s'
            . ' WHERE t.plural = "orders"'
            . ' AND li.id_type = t.id'
            . ' AND li.id_user = :user_id'
            . ' AND la.id_content = li.id'
            . ' AND s.id = li.state'
            . ' ORDER BY li.id DESC', 
            array('user_id' => $this->user['id'])
        );
        
   }

    function profile () {
        
        $this->view->title = 'Profilo';
        $this->view->description = 'I tuoi dati anagrafici';
        
        $this->item['id'] = $this->user['content'];
        
        $this->view->fields = $this->model->sel(
            'SELECT field_types.*'
            . ' FROM field_types, item_types_fields'
            . ' WHERE item_types_fields.id_field_type IN('
                . 'SELECT id FROM field_types WHERE field_types.site = 1)'
            . ' AND item_types_fields.id_content_type = ' . $this->user['item_type_id']
            . ' AND item_types_fields.id_field_type = field_types.id'
            . ' ORDER BY `order`'
        );
        
        $this->view->item['fields'] = $this->model->fields($this->model->item['type']['id'], $this->user['content']);
        
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/jquery.validate.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/additional-methods.min.js';
        
        $this->view->name = 'account/profile';
       
    }

    function items ($type = null) {
        
        if (isset($_GET['type'])) { $type = $_GET['type']; }
        
        if (!empty($type)) {
            
            is_numeric($type)? $field = 'id': $field = 'plural';
            
            $this->item['type'] = $this->model->selectnoview(
                'SELECT *'
                . ' FROM item_types'
                . ' WHERE ' . $field . ' = :type', 
                ['type' => $type]
            );
            
            $cond = ' AND li.id_type = ' . $this->item['type']['id'];
            
        } else {
            $cond = null;
        }
        
        //print_r($this->view->item); die;
        $this->view->title = ucfirst(\leslie::translate($type));
        $this->view->description = \leslie::translate('List ' . $type);
        $this->view->name = 'account/' . $type;
        
        $this->view->items = $this->model->sel(
            'SELECT li.code, la.title, s.value AS state'
            . ' FROM items_list AS li, items_languages AS la, item_states AS s'
            . ' WHERE li.id IN('
                . 'SELECT id_content'
                . ' FROM items_joints'
                . ' WHERE id_joint = ' . $this->user['content']
            . ')'
            . $cond
            . ' AND la.id_content = li.id'
            . ' AND s.id = li.state'
        );
        
        /*
        $this->view->item['joints'] = $this->model->select(''
            . 'SELECT t.*'
            . ' FROM items AS i, item_types AS t'
            . ' WHERE i.documents = 1'
            . ' AND t.item = i.id'
            . ' AND t.id IN('.$this->view->item['joints'].')'
        );
        */
        //print_r($this->view->item['joints']); die;
        //echo '<pre>'; print_r($this->view->items); echo '</pre>';
        //$this->item = $this->model->selectnoview('SELECT items_list.* FROM users, items_list WHERE users.id = :user_id AND items_list.id = users.content', ['user_id' => $this->user['id']]);
        //$this->view->fields = $this->model->select('SELECT field_types.* FROM field_types, item_types_fields WHERE item_types_fields.id_content_type = :content_type_id AND item_types_fields.id_field_type = field_types.id ORDER BY `order`', array('content_type_id' => $this->item['id_type']));

    }

    function uploads ($item_code, $documents_type = null) {
       //echo $item_code . ', ' . $documents_type;
       $item = $this->model->selectnoview('SELECT items_languages.title, items_languages.intro, items_list.id FROM items_list, items_languages WHERE items_list.code = :code AND items_languages.id_content = items_list.id', ['code' => $item_code]);
       $this->view->document_type = $this->model->selectnoview('SELECT * FROM item_types WHERE plural = :type', ['type' => $documents_type]);
       //print_r($item_type); die;
       if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
          if(!is_dir($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $documents_type)){
             if (!mkdir($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $documents_type, 0775, true)) {
                die ('unable to create folder in specific path: ' . $path);
             }
          }
          $i = 'a';
          check_filename:
          if (file_exists($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $documents_type . DIRECTORY_SEPARATOR . $_FILES['file']['name'])) {
             list($filename, $filext) = explode('.', $_FILES['file']['name']);
             $_FILES['file']['name'] = $filename . '-' . $i++ . '.' . $filext;
             goto check_filename;
          }
          move_uploaded_file($_FILES['file']['tmp_name'], $GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $documents_type . DIRECTORY_SEPARATOR . $_FILES['file']['name']);
          $this->model->insert('items_list', [
              'id_type' => $this->view->document_type['id'],
              'id_user' => $this->user['id'],
              'code' => $this->model->generate_code(),
              'name' => $_FILES['file']['name']
          ]);
          $id = $this->model->lastInsertId();
          $this->model->insert('items_languages', [
              'id_language' => $this->model->language['id'],
              'id_content' => $id,
              'title' => $_FILES['file']['name'],
              'state' => 0,
              'insert' => date('Y-m-d H:i:s')
          ]);

          $this->model->insert('items_joints', [
              'id_joint' => $id,
              'id_content' => $item['id']
          ]);
          die;
       }

       $this->view->title = \leslie::translate($documents_type);
       $this->view->description = $item['title'];
       $this->view->items = $this->model->select('SELECT items_list.*, items_languages.title FROM items_list, items_languages WHERE items_list.id_type = :type AND items_list.id IN(SELECT id_joint FROM items_joints WHERE id_content = :item) AND items_languages.id_content = items_list.id', ['type' => $this->view->document_type['id'], 'item' => $item['id']]);
       //print_r($this->view->items); die;
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/dropzone/dropzone.min.js';
       $this->view->styles[] = FRAMEWORK_URL_PLUG . '/dropzone/dropzone.min.css';
       $this->view->styles[] = FRAMEWORK_URL_PLUG . '/datatables/css/dataTables.bootstrap.min.css';
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/jquery.dataTables.min.js';
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/dataTables.bootstrap.min.js';
       $this->view->name = 'account/uploads';

    }
    
    function delete ($item_code) {
       //echo $item_code; die;
       $item = $this->model->selectnoview('SELECT id FROM ' . $this->user['prefix'] . '_list WHERE code = :code AND id_user = :user_id', ['code' => $item_code, 'user_id' => $this->user['id']]);
       
       if (empty($item)) {
          \leslie::$alerts['error'] = "account delete error";
          unset($_REQUEST['location']);
          return;
       }
       
       $this->model->delete($this->user['prefix'] . '_joints', 'id_joint = ' . $item['id'] . ' OR id_content = ' . $item['id']);
       $this->model->delete($this->user['prefix'] . '_languages', 'id_content = ' . $item['id']);
       $this->model->delete('items_fields', 'id_content = ' . $item['id'] . ' AND (id_item_type = 0 OR id_item_type = ' . $this->user['item_type_id'] . ')');
       $this->model->delete('items_permalinks', 'id_item = ' . $item['id'] . ' AND (id_type = 0 OR id_type = ' . $this->user['item_type_id'] . ')');
       $this->model->delete($this->user['prefix'] . '_list', 'id = ' . $item['id']);
       
    }

    function email () {
        
       $this->view->title = 'Profilo';
       $this->view->description = 'I tuoi dati anagrafici';
       
       $this->item = $this->model->selectnoview('SELECT items_list.* FROM users, items_list WHERE users.id = :user_id AND items_list.id = users.content', ['user_id' => $this->user['id']]);
       $this->view->fields = $this->model->select('SELECT field_types.* FROM field_types, item_types_fields WHERE item_types_fields.id_content_type = :content_type_id AND item_types_fields.id_field_type = field_types.id ORDER BY `order`', array('content_type_id' => $this->item['id_type']));
       $this->view->item['fields'] = $this->model->fields($this->model->item['type']['id'], $this->item['id']);
       //echo '<pre>'; print_r($this->view->item['fields']); echo '</pre>';
       
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/jquery.validate.min.js';
       $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/additional-methods.min.js';

       $this->view->name = 'account/email';
       
    }

    function update () {

       //echo '<pre>'; print_r($_POST); echo '</pre>'; die;

       unset($_POST['action']);

       if (empty($_POST['items'])) {
          \leslie::$alerts['error'] = "invalid data";
          return;
       }

       if (!empty($_POST['items']['users'])) {
          foreach ($_POST['items']['users'] as $field => $value) {
             switch ($field) {
                case 'email':
                   if (!$email = filter_var($value, FILTER_SANITIZE_EMAIL)) {

                      \leslie::$alerts['danger'][] = "invalid email";
                      unset($_REQUEST['location']);
                      return;
                   }
                   if (!empty($this->model->selectnoview('SELECT email FROM users WHERE email = :email', ['email' => $email])['email'])) {
                      \leslie::$alerts['danger'][] = "existing email. require password";
                      unset($_REQUEST['location']);
                      return;
                   }
                   $message = '<a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index?action=confirm&param=' . $this->user['code'] . '">' . \leslie::translate('confirm') . '</a>';
                   $this->mail($email, 'Email confirm', $message);
                   $this->model->update('users', ['email' => $email, 'active' => 0, 'update' => date('Y-m-d H:i:s')], 'id = ' . $this->user['id']);
                   header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/account/out'); die;
                   
                case 'password':
                   break;
                default : break;

             }
          }
       }

       $fields = array();
       //echo '<pre>'; print_r($this->view->fields); echo '</pre>'; die;
       foreach ($this->view->fields as $key => $item) {
           
          if ($item['required'] && empty($_POST['items']['items_fields'][$key]['value'])) {
             \leslie::$alerts['error'] = 'invalid ' . $item['name'];
             return;
          }
          
          $fields[$_POST['items']['items_fields'][$key]['id_type']] = array(
              'value' => $_POST['items']['items_fields'][$key]['value']
          );
       }

        foreach ($fields as $type => $item) {
            $this->model->update('items_fields', $item, 
                'id_content = ' . $this->item['id'] . ''
                . ' AND (id_item_type =' . $this->user['item_type_id'] . ' OR id_item_type = 0)'
                . ' AND id_language = 1'
                . ' AND id_type = ' . $type
            );
        }
       
       unset($_POST['items']);
       \leslie::$alerts['success'][] = "updated";

    }

    function out($message = null) {
        
        @session_unset();
        @session_destroy();

        setcookie('user', null, -1, '/');
        unset($_COOKIE['user']);
        
        $location = $GLOBALS['PROJECT']['URL']['BASE'] . '/access?location=' . str_replace('/out', null, $this->view->url);
        
        if (isset($message)) {
            $location .=  '&alert=warning&message=' . urlencode($message);
        }

        header('location: ' . $location);
        exit;
        
    }
   
}
