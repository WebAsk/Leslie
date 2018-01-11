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

class contents extends base {
    
    protected $item = array();
   
    function __construct($view, $model = '\\framework\\models\\base') {
        
        parent::__construct($view, $model);
        
        $type = $this->model->selectnoview(
                
            'SELECT t.id, t.prefix, t.plural, t.singular, t.joints'
            . ' FROM items_permalinks AS p, item_types AS t'
            . ' WHERE p.value = :permalink'
            . ' AND t.id = p.type', 
            ['permalink' => end(\leslie::$requests)]
                
        );
        
        if (empty($type['prefix'])) { $type['prefix'] = 'items'; }
        
        $query = 'SELECT li.id, li.id_type, li.active, p.item AS item_lang_id, p.value AS permalink, i.contents, i.joints'
        . ' FROM items_permalinks AS p, ' . $type['prefix'] . '_languages AS la, ' . $type['prefix'] . '_list AS li, item_types AS t, items AS i'
        . ' WHERE p.value = :permalink'
        . ' AND la.id = p.item'
        . ' AND li.id = la.id_content';
        
        if (!isset($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'admin')) {
            
            $query .= ' AND li.active = 1'
            . ' AND (li.state = 0 OR li.state IN(SELECT id FROM item_states WHERE view = 1))';
            
        }
        
        $this->item = $this->model->selectnoview(
                
            $query 
            . ' AND t.id = li.id_type'
            . ' AND i.id = t.item', 
            [
                'permalink' => end(\leslie::$requests)
            ]
                
        );
        
        $this->view->template = 'default/contents';
        
        if (empty($this->item)) {
            return;
        }
        
        $this->item['type'] = $type;
        
        //print_r($this->item);
        
        $permalink = $this->model->selectnoview(
            'SELECT value FROM items_permalinks'
            . ' WHERE id = ('
                . 'SELECT id FROM items_permalinks'
                . ' WHERE item = :item_lang_id'
                . ' ORDER BY `order`, id DESC LIMIT 1'
            . ')',
            ['item_lang_id' => $this->item['item_lang_id']],
            \PDO::FETCH_COLUMN
        );
        //echo $permalink; die;
        
        if ($this->item['permalink'] != $permalink) {
           header("HTTP/1.1 301 Moved Permanently"); 
           header("Location: " . $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $permalink);
           exit;
        }
        
        $this->item['permalink'] = $permalink;

        if ($this->item['contents']) {
            
           $this->item($this->item['id']);
           \leslie::$logs['methods'][] = 'item';
           
        } else if ($this->item['joints']) {
            
           \leslie::$logs['methods'][] = 'items';
           $this->items($this->item['id']);
           
        }

    }

    function item ($id) {
        
        $this->view->item = $this->model->query(
                
            'SELECT li.id, li.code, la.title, la.intro, la.description, la.`insert`, la.`update`, t.plural, t.singular, p.value AS permalink'
            . ' FROM ' . $this->item['type']['prefix'] . '_list AS li, ' . $this->item['type']['prefix'] . '_languages AS la, item_types AS t, items_permalinks AS p'
            . ' WHERE li.id = ' . $this->item['id']
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = ' . $this->model->language['id']
            . ' AND t.id = li.id_type'
            . ' AND p.id = ('
                . 'SELECT id'
                . ' FROM items_permalinks'
                . ' WHERE item = la.id'
                . ' AND type = li.id_type'
                . ' ORDER BY `order`, `insert` DESC'
                . ' LIMIT 0, 1'
            . ')'
                
        )->fetch(\PDO::FETCH_ASSOC) or die('item language select error');
        
        $this->view->name = 'contents/item';
        
        $this->view->title = htmlentities($this->view->item['title']);
        $this->view->description = htmlentities($this->view->item['intro']);
        $this->view->url = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents'). '/' . \leslie::translate($this->view->item['plural']) . '/' . $this->view->item['permalink'];
        
        $this->view->breadcrumb[] = ucfirst(\leslie::translate($this->item['type']['plural']));
        $this->view->breadcrumb[] = $this->view->title;
        
        if ($this->model->selone(
                
            'SELECT id'
            . ' FROM item_types'
            . ' WHERE plural = "comments"'
            . ' AND id IN (' . $this->item['type']['joints'] . ')'
                
        )) {
            
            $this->view->comments = true;
            $this->view->styles[] = FRAMEWORK_URL_PLUG . '/jquery/validation/validate.css';
            $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/jquery.validate.min.js';
            $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/localization/messages_it.min.js';
            $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/additional-methods.min.js';
            $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/ckeditor/ckeditor.js';
            
        }
        
        /* Set Item Visit +1 If Not Setting Yet */
        
        if (!isset($_COOKIE['views'])) {
            
            $this->model->exec('UPDATE ' . $this->item['type']['prefix'] . '_languages SET views = views + 1 WHERE id = ' . $this->item['id']);
            setcookie('views', serialize(array($this->item['id'])));
            
        } else {
            
            $items = unserialize($_COOKIE['views']);
            if (!in_array($this->item['id'], $items)) {
                $this->model->exec('UPDATE ' . $this->item['type']['prefix'] . '_languages SET views = views + 1 WHERE id = ' . $this->item['id']);
                array_push($items, $this->item['id']);
                setcookie('views', serialize($items));
            }
            
        }
        
        $this->view->images = $this->model->sel(
            'SELECT li.name, t.plural, d.folder, MAX(d.width), la.title'
                //. ', d.folder'
            . ' FROM items AS i, item_types AS t, documents AS d'
                //. ', documents AS d'
                . ', items_list AS li'
                . ' LEFT JOIN items_languages AS la ON la.id_content = li.id'
            . ' WHERE i.documents = 1'
            . ' AND t.item = i.id'
                . ' AND d.item =  t.id'
            //. ' AND d.item = t.id'
            //. ' AND d.images = 1'
            . ' AND li.id_type = t.id'
            . ' AND li.id IN(SELECT id_joint FROM items_joints WHERE id_content = ' . $id . ')'
            //. ' GROUP BY d.item'
            . ' GROUP BY li.name'
            . ' ORDER BY li.`order`'
            //. ', d.width DESC'
        );
        //echo '<pre>'; print_r($this->view->images); echo '<pre>'; die;
        
        if (!empty($this->view->images) && file_exists($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . '/'.$this->view->images[0]['plural'].'/'.$this->view->images[0]['folder'].'/' . $this->view->images[0]['name'])) {
            $this->view->image = $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/'.$this->view->images[0]['plural'].'/'.$this->view->images[0]['folder'].'/' . $this->view->images[0]['name'];
            list($this->view->image_width, $this->view->image_height) = getimagesize($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . '/'.$this->view->images[0]['plural'].'/'.$this->view->images[0]['folder'].'/' . $this->view->images[0]['name']);
        }
        
        $this->view->attributes = $this->model->get_attributes($id);
        //echo '<pre>'; print_r($this->view->attributes); echo '<pre>'; die;
        
        if (!empty($this->item['type']['joints'])) {

            $this->view->items = $this->model->sel(
               'SELECT t.accounts, la.title, la.intro, la.description, la.`insert`, p.value AS permalink, d.name AS image'
                . ' FROM items_list AS li'
                    . ' LEFT JOIN items_list AS d ON d.id IN('
                        . 'SELECT j.id_joint'
                        . ' FROM items AS i, item_types AS t, items_joints AS j'
                        . ' WHERE i.documents = 1'
                        . ' AND t.item = i.id'
                        . ' AND j.type = t.id'
                        . ' AND j.id_content = li.id'
                    . ')'
                    
                . ', item_types AS t, items_languages AS la'
                    . ' LEFT JOIN items_permalinks AS p ON p.id = ('
                        . 'SELECT id'
                        . ' FROM items_permalinks'
                        . ' WHERE item = la.id'
                        //. ' AND type = li.id_type'
                        . ' ORDER BY `order`, `insert` DESC'
                        . ' LIMIT 0, 1'
                    . ')'
                . ' WHERE li.id IN('
                    . 'SELECT id_joint'
                    . ' FROM items_joints'
                    . ' WHERE id_content = ' . $this->view->item['id']
                . ')'
                . ' AND li.active = 1'
                . ' AND t.id = li.id_type'
                . ' AND t.`view` = 1'
                . ' AND la.id_content = li.id'
                . ' AND la.id_language = ' . $this->model->language['id']
                . ' ORDER BY li.`order`, la.`views` DESC, la.`insert` DESC'
                . ' LIMIT 0, 3'
            );
            //echo '<pre>'; print_r($this->view->items); echo '<pre>'; //exit;
            
            foreach ($this->view->items as $item) {
               if ($item['accounts']) {
                  $this->view->author = htmlentities($item['title']);
                  $this->view->head[] = '<meta name="author" content="'. $this->view->author .'">';
                  break;
               }
            }
            
            foreach ($this->model->query(
                
                'SELECT li.id, li.id_type, t.plural, li.name, li.code, la.title, la.intro, p.value AS permalink'
                . ' FROM item_types AS t, items_list AS li'
                . ', items_languages AS la'
                    . ' LEFT JOIN items_permalinks AS p'
                    . ' ON p.id = ('
                        . 'SELECT id'
                        . ' FROM items_permalinks'
                        . ' WHERE item = la.id'
                        //. ' AND type = li.id_type'
                        . ' ORDER BY `order`, `insert` DESC'
                        . ' LIMIT 0, 1'
                    . ')'
                . ' WHERE t.id IN(' . $this->item['type']['joints'] . ')'
                    . ' AND li.id_type = t.id'
                    . ' AND li.active = 1'
                    . ' AND li.id IN('
                        . 'SELECT id_joint'
                        . ' FROM items_joints'
                        . ' WHERE id_content = ' . $this->item['id']
                        . ' AND type IN(' . $this->item['type']['joints'] . ')'
                        . ' AND active = 1'
                    . ')'
                    . ' AND la.id_content = li.id'
                    . ' AND la.id_language = ' . $this->model->language['id'],
                    \PDO::FETCH_ASSOC
                    
            ) as $joint) {
                
                $this->view->item['joints'][$joint['id_type']][] = $joint;
                $this->view->keywords .= ', ' . strtolower(htmlentities($joint['title'])) ;
            
            }
            //echo '<pre>'; print_r($this->view->item['joints']); echo '<pre>'; exit;
            
        }        
        
        $this->view->item['documents'] = $this->model->sel(
            'SELECT li.name, la.title'
                . ', d.folder'
            . ' FROM items AS i, item_types AS t'
                . ', documents AS d'
                . ', items_list AS li, items_languages AS la'
            . ' WHERE i.documents = 1'
            . ' AND t.item = i.id'
            . ' AND d.item = t.id'
            . ' AND d.images = 0'
            . ' AND li.id_type = t.id'
            . ' AND li.id IN(SELECT id_joint FROM items_joints WHERE id_content = ' . $id . ')'
            . ' AND la.id_content = li.id'
            //. ' GROUP BY d.item'
            . ' ORDER BY li.`order`'
            //. ', d.width DESC'
        );
        
        $this->view->related_contents = $this->model->sel(
            'SELECT t.plural, la.title, la.intro, la.`insert`, p.value AS permalink, d.name AS image'
            . ' FROM items_list AS li'
                . ' LEFT JOIN items_list AS d ON d.id IN('
                    . 'SELECT j.id_joint'
                    . ' FROM items AS i, item_types AS t, items_joints AS j'
                    . ' WHERE i.documents = 1'
                    . ' AND t.item = i.id'
                    . ' AND j.type = t.id'
                    . ' AND j.id_content = li.id'
                . ')'
            . ', item_types AS t, items_languages AS la, items_permalinks AS p'
            . ' WHERE li.id_type = ' . $this->item['type']['id']
            . ' AND li.id != ' . $this->item['id']
            . ' AND li.active = 1'
                . ' AND (li.`state` = 0 OR li.`state` IN(SELECT id FROM item_states WHERE `view` = 1))'
            . ' AND t.id = li.id_type'
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = ' . $this->model->language['id']
            . ' AND p.id = (SELECT id FROM items_permalinks WHERE item = la.id AND type = li.id_type ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
            . ' ORDER BY la.`views` DESC, li.`order`, la.`insert` DESC'
            . ' LIMIT 0, 3'
        );
        //echo '<pre>'; print_r($this->view->related_contents); echo '<pre>'; die;
        
        //echo '<pre>'; print_r($this->view->item); echo '<pre>'; die;
        
    }
   
    function items ($joint_id) {

        $this->view->item = $this->model->selectnoview(
            'SELECT la.title, la.intro, la.description, t.plural, p.value AS permalink'
            . ' FROM ' . $this->item['type']['prefix'] . '_list AS li, item_types AS t, ' . $this->item['type']['prefix'] . '_languages AS la, items_permalinks AS p'
            . ' WHERE li.id = :content_id'
            . ' AND t.id = li.id_type'
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = :language_id'
            . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = li.id_type AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)', 
            [
                'content_id' => $joint_id, 
                'language_id' => $this->model->language['id']
            ]
        );
        
        $this->view->breadcrumb[] = ucfirst(\leslie::translate($this->view->item['plural']));
        $this->view->breadcrumb[] = htmlentities($this->view->item['title']);
        
        $this->view->url = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents'). '/' . \leslie::translate($this->view->item['plural']) . '/' . $this->view->item['permalink'];
        $this->view->title = $this->view->item['title'];
        $this->view->description = $this->view->item['intro'];
        $this->view->items = $this->model->select(
            "SELECT contents_view.*"
            . " FROM contents_view"
            . " WHERE FIND_IN_SET(:joint, contents_view.joints)", 
            [
                'joint' => $joint_id
            ]
        );
        //$this->view->joints = $this->model->get_joints ();
        //print_r($this->view->item); exit;
        $this->view->name = 'contents/items';

    }
   
    function search () {
        $this->view->title = 'Ricerca';
        $this->view->description = 'Risultati di ricerca per "' . $_GET['q'] . '"';
        $this->view->name = 'google' . DIRECTORY_SEPARATOR . 'search';
        $this->view->breadcrumb[] = 'Contenuti';
        $this->view->breadcrumb[] = 'Ricerca';
    }

    public function comment () {

       if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH'] && !$this->reCaptcha($_POST['g-recaptcha-response'])['success']) {
          \leslie::$alerts['danger'][] = "invalid captcha";
          unset($_REQUEST['location']);
          return;
       }

       if (empty($_POST['items']['items_languages']['title'])) {
          \leslie::$alerts['danger'][] = 'name empty';
          unset($_REQUEST['location']);
          return;
       }

       if (!$email = filter_var($_POST['items']['users']['email'], FILTER_VALIDATE_EMAIL)) {
          \leslie::$alerts['danger'][] = 'invalid email';
          unset($_REQUEST['location']);
          return;
       }

       if (empty($_POST['items']['items_languages']['description'])) {
          \leslie::$alerts['danger'][] = 'empty comment';
          unset($_REQUEST['location']);
          return;
       }

       $user = $this->model->selectnoview('SELECT id FROM users WHERE email = :email', ['email' => $email]);
       $user_name = htmlentities($_POST['items']['items_languages']['title']);
       if (empty($user['id'])) {
          $user_code = $this->model->generate_code('users');
          $this->model->insert(
             'users',
             [
                'email' => $email,
                'type' => 3,
                'password' => null,
                'code' => $user_code,
                'active' => 0,
                 'insert' => date('Y-m-d H:i:s')

             ]
          );

          
          $message = '<p>Ciao ' . $user_name . ',</br></br>';
          $message .= 'Ti ringraziamo per il commento su "' . $item_title . '".</br>';
          $message .= 'A breve sar&agrave; revisionato.</br>';
          $message .= 'Ti invieremo una email di notifica quando il tuo contributo sar&agrave; pubblicato.</p>';
          $message .= '<p>Nel frattempo <a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/access/index?action=confirm&param=' . $user_code. '">conferma il tuo indirizzo e-mail</a>.</p>';
          $this->mail($email, $item_title, $message, $_POST['items']['items_languages']['title']);


          $user_id = $this->model->lastInsertId();
       } else {
          $user_id = $user['id'];
       }

       $item_type = $this->model->selectnoview('SELECT id FROM item_types WHERE plural = :comments', ['comments' => 'comments']);

       $this->model->insert(
          'items_list',
          [
              'name' => $_POST['items']['items_languages']['title'] . ' su ' . $this->view->item['title'] . ' il ' . date('d M Y'),
              'id_type' => $item_type['id'],
              'id_user' => $user_id,
              'code' => $this->model->generate_code('items_list'),
              'state' => 0
          ]
       );

       $item_id = $this->model->lastInsertId();

       $this->model->insert(
          'items_languages',
          [
              'title' => $_POST['items']['items_languages']['title'],
              'id_content' => $item_id,
              'description' => $_POST['items']['items_languages']['description'],
              'id_language' => $this->model->language['id'],
              'insert' => date('Y-m-d H:i:s')
          ]
       );

       $this->model->insert(
          'items_joints',
          [
              'type' => $item_type['id'],
              'id_content' => $this->item['id'],
              'id_joint' => $item_id,
              'insert' => date('Y-m-d H:i:s'),
              'active' => 1
          ]
       );

       $this->mail(
          $GLOBALS['COMPANY']['EMAIL'], 
          'Nuovo commento su "' . $this->view->title . '"', 
          '<p>'.$user_name.' ha fatto un commento su "' . $this->view->title . '".</p>' . 
          '<p><a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/admin/item?id=' . $item_id . '">Revisionalo</a> e <a href="mailto:' . $email . '?subject=' . $this->view->title . '">rispondi a ' . $user_name . '</a>.</p>'
       );

       \leslie::$alerts['success'][] = 'comment successfully added';
       unset($_POST);

    }
   
   
   
}
