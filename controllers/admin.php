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

class admin extends account {
   
    private $content_id;
    private $section;
    protected $item;
    protected $subquery = array(
        'where' => null,
        'order' => ' ORDER BY li.`order`, li.id DESC',
        'page' => 0,
        'limit' => ' LIMIT 0, 100'
    );

    function __construct ($view, $model) {
        
        parent::__construct($view, $model);
        //die(print_r($this->user));
        \leslie::$logs['exe'][] = 'admin start: ' . date('H:i:s');
        
        if ($this->user['admin'] != 1) { header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/account'); die; }
        
        unset($GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']);
        unset($GLOBALS['PROJECT']['GOOGLE']['TAG']);
        $this->view->cookie = false;

        $this->view->nav = $this->nav();
        $this->view->template = 'admin' . DIRECTORY_SEPARATOR . 'complete';
        
        foreach ($this->model->query(
                'SELECT *'
                . ' FROM languages'
                . ' WHERE active = 1',
                \PDO::FETCH_ASSOC
        ) as $language) {
            $this->view->languages[$language['id']] = $language;
        }
        //print_r($this->view->languages); exit;
        
        $this->default_language_id = $this->model->cell('languages', 'id', '`default` = 1');
        $GLOBALS['PROJECT']['STYLES'] = array();
        $GLOBALS['PROJECT']['SCRIPTS'] = array();
        array_unshift($GLOBALS['PROJECT']['SOURCES'], FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'gentellela');
        array_unshift($GLOBALS['PROJECT']['URLS'], FRAMEWORK_URL_TPL . '/gentellela');
        //print_r($this->view->styles); exit;

    }

    function index () {
        
       \leslie::$logs['exe'][] = 'admin index start: ' . date('H:i:s');
       $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'index';
       $this->view->title = 'Dashboard';
       $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'index';

    }
    
    function sessions () {
        
        $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'tables_dynamic';
        $this->view->title = 'Sessioni';
        $this->view->current = 'sessions';
        $this->view->description = 'sessions list';
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'sessions';
        
        $accounts_prefix = $this->model->query(
            "SELECT prefix"
            . " FROM item_types"
            . " WHERE accounts = 1"
            . " LIMIT 1"
        )->fetch(\PDO::FETCH_COLUMN);
        
        if (empty($accounts_prefix)) {
            $accounts_prefix = 'items';
        }
        
        $this->view->items = $this->model->sel(
            'SELECT s.*, l.name'
            . ' FROM sessions AS s, users AS u'
                . ' LEFT JOIN ' . $accounts_prefix . '_list AS l ON l.id = u.content'
            . ' WHERE u.id = s.user'
        );
        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/datatables/css/dataTables.bootstrap.min.css';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/jquery.dataTables.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/dataTables.bootstrap.min.js';
       
    }
    
    function users () {
        
        $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'tables_dynamic';
        $this->view->title = 'Users';
        $this->view->current = 'users';
        $this->view->description = 'users list';
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'users';
        
        $accounts_prefix = $this->model->query(
            "SELECT prefix"
            . " FROM item_types"
            . " WHERE accounts = 1"
            . " LIMIT 1"
        )->fetch(\PDO::FETCH_COLUMN);
        
        if (empty($accounts_prefix)) {
            $accounts_prefix = 'items';
        }
        
        $this->view->users = $this->model->sel(
            'SELECT u.*, l.name, t.name AS type'
            . ' FROM users AS u'
                . ' LEFT JOIN ' . $accounts_prefix . '_list AS l ON l.id = u.content'
            . ', user_types AS t'
            . ' WHERE t.id = u.type'
        );
        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/datatables/css/dataTables.bootstrap.min.css';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/jquery.dataTables.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/dataTables.bootstrap.min.js';
       
    }

    function user () {
        
        $this->view->item = $this->model->describe('users');
        $this->view->title = \leslie::translate('Insert user');
        $this->view->action = 'insert';
        
        if (!empty($_GET['id']) && is_int($this->item['id'] = (int) $_GET['id']) && $this->item['id'] > 0) {

            $this->view->item = $this->model->selone('SELECT content, email, disabled FROM users WHERE id = ' . $this->item['id']);
            $this->view->title = \leslie::translate('Update user');
            $this->view->action = 'update';

        }
        
        $this->item['type'] = $this->model->query('SELECT id, prefix FROM item_types WHERE accounts = 1')->fetch(\PDO::FETCH_ASSOC);
        if (empty($this->item['type']['prefix'])) { $this->item['type']['prefix'] = 'items'; }
        $this->view->contents = $this->model->sel('SELECT id, name FROM ' . $this->item['type']['prefix'] . '_list WHERE id_type = ' . $this->item['type']['id']);

        $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'form';
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'user';
        $this->view->current = 'users';

    }
   
    function search () {
        
        if (!empty($_GET['keywords'])) {
            $this->view->template = 'admin' . DIRECTORY_SEPARATOR . 'no-sidebar';
            $this->view->current = 'search';
            //print_r($this->view->content_type); exit;
            $this->view->title = \leslie::translate('Results of research for') . ': &quot;' . \leslie::translate($_GET['keywords']) . '&quot;';
            $this->view->items = $this->model->query("SELECT items_list.code, items_list.id_type, items_languages.*, languages.sign AS lang FROM items_list, items_languages, languages WHERE (items_languages.title LIKE '%" . $_GET['keywords'] . "%' OR items_languages.description LIKE '%" . $_GET['keywords'] . "%') AND items_list.id = items_languages.id_content AND languages.id = items_languages.id_language ORDER BY items_list.id_type, items_list.id DESC")->fetchAll(\PDO::FETCH_ASSOC);
            //print_r($this->view->items); die;
        } else {
            header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/admin'); die;
        }
       
    }
   
    function items ($type = null) {
        
        if (isset($_GET['type'])) { $type = $_GET['type']; }
        
        \leslie::$logs['exe'][] = 'admin items: ' . date('H:i:s');
        
        $this->item = $this->model->query('SELECT items.* FROM item_types, items WHERE item_types.id = ' . $type . ' AND items.id = item_types.item')->fetch(\PDO::FETCH_ASSOC) or die('item error');
        $this->item['type'] = $this->model->query('SELECT * FROM item_types WHERE id = ' . $type)->fetch(\PDO::FETCH_ASSOC) or die('item type error');
        if (empty($this->item['type']['prefix'])) { $this->item['type']['prefix'] = 'items'; }
        
        $this->view->item['documents'] = $this->item['documents'];
        $this->view->item['type']['id'] = $this->item['type']['id'];
        $this->view->item['type']['plural'] = $this->item['type']['plural'];
        $this->view->item['type']['permalink'] = $this->item['type']['permalink'];
        $this->view->item['type']['notice'] = $this->item['type']['notice'];
        $this->view->item['type']['prefix'] = $this->item['type']['prefix'];
        
        $this->view->title = $this->view->item['type']['plural'];
        $this->view->description = null;
        $this->view->current = $this->item['plural'];
        
        $this->view->columns_excluded = ['name', 'active', 'languages', 'user_type', 'id_user'];

        $this->view->order = true;
        $this->view->params['page'] = 1;
        $this->view->params['limit'] = 100;
        $this->view->params['string'] = null;
        $this->view->params['state'] = 0;
        $this->view->params['order'] = 'order';
        $this->view->limits = array(10 => 10, 20 => 20, 50 => 50, 100 => 100, '' => 'Tutti');

        $this->subquery['order'] = ' ORDER BY li.`order`, li.id DESC';

        if ($this->user['delete'] == 0) {
           $this->subquery['where'] .= ' AND li.active = 1';
        }

        if (!empty($_GET['string'])) {
           $this->subquery['where'] .= ' AND li.name LIKE "%' . $_GET['string'] . '%"';
           $this->view->params['string'] = $_GET['string'];
           $this->view->description .= '&raquo; contenente: &quot;<strong>' . $_GET['string'] . '</strong>&quot;';
        }
        
        $this->view->states[0]['value'] = 'allp';
        
        foreach (
            $this->model->query(
                'SELECT * FROM item_states'
                . ' WHERE item = ' . $this->item['type']['id']
                . ' ORDER BY `order`'
            ) as $s
        ) {
            
            $this->view->states[$s['id']] = $s;
            
            if (!empty($s['default'])) {
                
                $this->view->params['state'] = $s['id'];
                
            }
            
        }
        //echo '<pre>'; print_r($this->view->states); echo '</pre>'; exit;
        
        if (isset($_GET['state'])) {
            
            $this->view->params['state'] = $_GET['state'];
            
        }
        
        if ($this->view->params['state'] != 0) {
            $this->subquery['where'] .= ' AND li.state = ' . $this->view->params['state'];
            $this->view->description .= ' ' . \leslie::translate($this->view->states[$this->view->params['state']]['value']);
        }
        
        if (isset($_GET['order'])) {
           $this->subquery['order'] = ' ORDER BY li.`' . $_GET['order'] . '`, li.id DESC';
           $this->view->params['order'] = $_GET['order'];
        }

        if (!empty($_GET['page'])) {
           $this->view->params['page'] = $_GET['page'];
           $this->subquery['page'] = $_GET['page'] - 1;
        }
        
        $this->view->total = $this->model->query(
                
            'SELECT li.id'
            . ' FROM ' . $this->item['type']['prefix'] . '_list AS li'
            . ' WHERE li.id_type = ' . $type
            . $this->subquery['where']
                
        )->rowCount();
        
        if (isset($_GET['limit'])) {
           if ($_GET['limit'] == '') {
              $this->subquery['limit'] = null;
              $limit = $this->view->total;
           } else {
              $this->subquery['limit'] = ' LIMIT ' . $this->subquery['page'] * $_GET['limit'] . ', ' . $_GET['limit'];
              $limit = $_GET['limit'];
           }
           $this->view->params['limit'] = $_GET['limit'];
        } else {
            $limit = $this->view->params['limit'];
        }
        
        
        $this->view->params['limit'] < $this->view->total? $this->view->description .= ' &raquo; risultati: <strong>' . $limit . '</strong> su <strong>' . $this->view->total . '</strong>': $this->view->description .= ' &raquo; risultati: <strong>' . $this->view->total . '</strong>';
        $this->view->pages = ceil($this->view->total / $limit);
        $this->view->params['page'] < $this->view->pages? $this->view->description .= ' &raquo; pagina: <strong>' . $this->view->params['page'] . '</strong> di <strong>' . $this->view->pages . '</strong>': $this->view->description .= ' &raquo; pagina: <strong>' . $this->view->params['page'] . '</strong>';
        $this->view->description .= ' &raquo; ordine: &quot;<strong>' . \leslie::translate($this->view->params['order']) . '</strong>&quot;';

        $this->view->items = $this->model->select(
            'SELECT li.id, li.name, li.state, li.code, li.active, li.id_user, u.type AS user_type'
            . ' FROM ' . $this->item['type']['prefix'] . '_list AS li'
            . ', users AS u'
            . ' WHERE li.id_type = :type_id'
            . ' AND u.id = li.id_user'
            . ' AND u.type >= ' . $this->user['type']
            . $this->subquery['where']
            . $this->subquery['order']
            . $this->subquery['limit'], 
            ['type_id' => $type]
        );
        //echo '<pre>'; print_r($this->view->items); echo '</pre>'; exit;

        $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'tables_dynamic';

        if ($this->item['documents']) {
           $this->view->documents = $this->model->query('SELECT * FROM documents WHERE item = ' . $this->item['type']['id'] . ' ORDER BY width LIMIT 0, 1')->fetch(\PDO::FETCH_ASSOC);
        }
        
        foreach ($this->view->items as $key => $item) {
            $this->view->items_languages[$item['id']] = $this->model->sel(
                "SELECT langs.id"
                . " FROM ".$this->item['type']['prefix']."_languages AS la, languages AS langs"
                . " WHERE la.id_content = " . $item['id']
                . " AND langs.id = la.id_language"
                . " ORDER BY la.id_language"
                , \PDO::FETCH_COLUMN
            );
        }
        //print_r($this->view->items_languages); exit;
        
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'items';

        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/jquery/ui/1.12.0/jquery-ui.min.css';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/ui/1.12.0/jquery-ui.min.js';
        $this->view->scripts[] = 'admin/items';
        $this->view->styles[] = 'admin/items';
        //$this->view->styles[] = FRAMEWORK_URL_PLUG . '/datatables/css/dataTables.bootstrap.min.css';
        //$this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/jquery.dataTables.min.js';
        //$this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/dataTables.bootstrap.min.js';

    }
   
    function item ($type = null, $code = null) {
        
        \leslie::$logs['exe'][] = 'admin item: ' . date('H:i:s');
        
        if (!isset($type) && !isset($_GET['type'])) { die('type param error'); }
        
        if (isset($_GET['type'])) { $type = $_GET['type']; }
        if (isset($_GET['code'])) { $code = $_GET['code']; }
        
        $this->item = $this->model->query('SELECT items.* FROM item_types, items WHERE item_types.id = ' . $type . ' AND items.id = item_types.item')->fetch(\PDO::FETCH_ASSOC) or die('item error');
        //print_r($this->item); die;
        $this->item['type'] = $this->model->query('SELECT * FROM item_types WHERE id = ' . $type)->fetch(\PDO::FETCH_ASSOC) or die('item type error');
        $this->item['language']['id_language'] = $this->default_language_id;
        $this->item['id'] = 0;
        
        if (empty($this->item['type']['prefix'])) { $this->item['type']['prefix'] = 'items'; }
        
        $this->view->item['item_types']['id'] = $this->item['type']['id'];
        $this->view->item['item_types']['plural'] = $this->item['type']['plural'];
        $this->view->item['item_types']['intro'] = $this->item['type']['intro'];
        $this->view->item['item_types']['description'] = $this->item['type']['description'];
        
        if (!empty($code)) {
            
            $this->item['id'] = $this->model->query('SELECT id FROM ' . $this->item['type']['prefix'] . '_list WHERE code = "' . $code . '"')->fetch(\PDO::FETCH_COLUMN) or die('item code error');
            
            if (!empty($_GET['lang'])) { $this->item['language']['id_language'] = $_GET['lang']; }
            
            $this->item['language']['id'] = $this->model->selone(
                'SELECT id'
                . ' FROM ' . $this->item['type']['prefix'] . '_languages'
                . ' WHERE id_content = ' . $this->item['id']
                . ' AND id_language = ' . $this->item['language']['id_language'],
                \PDO::FETCH_COLUMN
            );
            
            $this->item['name'] = $this->model->query(
                'SELECT name'
                . ' FROM ' . $this->item['type']['prefix'] . '_list'
                . ' WHERE id = ' . $this->item['id']
            )->fetch(\PDO::FETCH_COLUMN) or die('item name error');
            
            $this->view->item['items_list']['name'] = $this->item['name'];
            
             $this->view->item['items_list']['code'] = $this->model->query(
                'SELECT code'
                . ' FROM ' . $this->item['type']['prefix'] . '_list'
                . ' WHERE id = ' . $this->item['id']
            )->fetch(\PDO::FETCH_COLUMN);
            
            $this->view->item['items_list']['state'] = $this->model->query(
                'SELECT state'
                . ' FROM ' . $this->item['type']['prefix'] . '_list'
                . ' WHERE id = ' . $this->item['id']
            )->fetch(\PDO::FETCH_COLUMN);
            
            $this->view->item['items_languages'] = $this->model->selone(
                'SELECT title, intro, description'
                . ' FROM ' . $this->item['type']['prefix'] . '_languages'
                . ' WHERE id_content = ' . $this->item['id']
                . ' AND id_language = ' . $this->item['language']['id_language']
            );

            $this->view->item['items_joints'] = $this->model->item_joints_ids($this->item['type']['id'], $this->item['id']);
            $this->view->item['items_fields'] = $this->model->fields($this->item['type']['id'], $this->item['id'], $this->item['language']['id_language']);
            //print_r($this->view->item['items_fields']); exit;
            //print_r($this->view->item['joints']); exit;
            
        }
        
        $this->view->states = $this->model->sel(
            'SELECT * FROM item_states'
            . ' WHERE active = 1'
                . ' AND permits >= ' . $this->user['type']
                . ' AND item = ' . $this->item['type']['id']
            . ' ORDER BY `order`'
        );
        
        if (!empty($this->item['type']['joints'])) {

            $this->view->joints = $this->model->sel(
                'SELECT t.id, t.multiple, t.singular, t.plural, i.plural AS item_plural'
                . ' FROM item_types AS t, items AS i'
                . ' WHERE t.id IN(' . $this->item['type']['joints'] . ')'
                . ' AND t.active = 1'
                . ' AND i.id = t.item'
            );
            
            //echo '<pre>'; print_r($this->view->joints); echo '</pre>'; die;

            foreach ($this->view->joints as $key => $joint) {
                
                if ($images = $this->model->query('SELECT images FROM documents WHERE item = ' . $joint['id'])) {
                    $this->view->joints[$key]['images'] = $images->fetch(\PDO::FETCH_COLUMN);
                }

                empty($joint['prefix'])? $prefix = 'items' : $prefix = $joint['prefix'];

                $sql = 'SELECT li.id, li.name, la.title'
                . ' FROM ' . $prefix . '_list AS li'
                    . ' LEFT JOIN ' . $prefix . '_languages AS la ON la.id_content = li.id AND la.id_language = ' . $this->model->language['id']
                . ' WHERE li.id_type = ' . $joint['id']
                . ' AND li.active = 1';
                //if (!empty($this->item['id'])) { $sql .= ' AND id != ' . $this->item['id']; }

                $this->view->joint_items[$joint['id']] = $this->model->sel($sql);
                
            }
            
            //echo '<pre>'; print_r($this->view->joint_items); echo '</pre>'; die;

        }
        
        $this->view->fields = $this->model->item_type_fields($this->item['type']['id']);
        //echo '<pre>'; print_r($this->view->fields); echo '</pre>'; die;
        
        foreach ($this->model->query(
           'SELECT d.item, d.folder'
           . ' FROM documents AS d'
           . ' WHERE d.width = (SELECT MIN(width) FROM documents WHERE item = d.item)'
        , \PDO::FETCH_ASSOC) as $f) {
            
           $this->view->folders[$f['item']] = $f['folder'];
           
        }
        
        //echo '<pre>'; print_r($this->view->folders); echo '</pre>'; exit;

        \leslie::$logs['item'] = $this->item;
        \leslie::$logs['exe'][] = 'admin item end: ' . date('H:i:s');
        
        /*
        echo '<pre>';
        print_r($this->view->item);
        echo '</pre>'; die;
        */
        
   }
   
    function handle () {
        
        \leslie::$logs['exe'][] = 'admin item handle start: ' . date('H:i:s');
        
        $this->view->current = $this->item['plural'];
        empty($this->item['language']['id'])? $this->view->action = 'insert': $this->view->action = 'update';
        $this->view->title = \leslie::translate($this->view->action . ' ' . $this->item['type']['singular']);

        $this->view->item['documents'] = $this->item['documents'];

        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/ckeditor/ckeditor.js';

        //$this->view->scripts[] = FRAMEWORK_URL_PLUG . '/masonry/masonry.pkgd.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/chosen/chosen.jquery.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/chosen/ImageSelect.jquery.js';

        //$this->view->styles[] = FRAMEWORK_URL_PLUG . '/bootstrap/validator/bootstrapValidator.min.css';
        //$this->view->scripts[] = FRAMEWORK_URL_PLUG . '/bootstrap/validator/bootstrapValidator.min.js';

        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/chosen/chosen.min.css';
        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/chosen/ImageSelect.css';

        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/jquery/validation/validate.css';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/jquery.validate.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/localization/messages_it.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/additional-methods.min.js';

        if ($this->item['documents']) {
            
            $this->view->documents = $this->model->sel('SELECT id, images, width, height, folder FROM documents WHERE item = ' . $this->item['type']['id'] . ' ORDER BY `order`');
            
            if ($this->view->documents[0]['images']) {
                $this->view->styles[] = FRAMEWORK_URL_PLUG . '/jquery/cropper/dist/cropper.min.css';
                $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/cropper/dist/cropper.min.js';
            }
            
        }
        
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'item';
        $this->view->template = 'admin' . DIRECTORY_SEPARATOR . 'no-sidebar';
        
        \leslie::$logs['exe'][] = 'admin item handle end: ' . date('H:i:s');
        //die;

    }
   
    function notice ($code = null) {
        
        if (!empty($_REQUEST['code'])) { $code = $_REQUEST['code']; }
        
        $item = $this->model->query(
            'SELECT li.id, item_types.singular, la.title'
            . ' FROM ' . $this->item['type']['prefix'] . '_list AS li, item_types, ' . $this->item['type']['prefix'] . '_languages AS la'
            . ' WHERE li.code = "' . $code . '"'
            . ' AND item_types.id = li.id_type'
            . ' AND la.id_content = li.id'
        )->fetch(\PDO::FETCH_ASSOC) or die('notice item error');
        
        $accounts = $this->model->selone('SELECT singular, prefix FROM item_types WHERE accounts = 1');
        if (empty($accounts['prefix'])) { $accounts['prefix'] = 'items'; }
        
        $users = $this->model->sel(
            'SELECT users.email, item_types.singular'
            . ' FROM item_types, ' . $accounts['prefix'] . '_list AS li, users'
            . ' WHERE item_types.accounts = 1'
            . ' AND li.id_type = item_types.id'
            . ' AND li.id IN(SELECT id_joint FROM ' . $this->item['type']['prefix'] . '_joints WHERE id_content = ' . $item['id'] . ')'
            . ' AND users.content = li.id'
        );
        //print_r($item); die;
        //print_r($users); die;

        foreach ($users as $user) {
           $message = 'Gentile ' . \leslie::translate($accounts['singular']) . ',<br /><br />';
           $message .= 'La presente per informarla della variazione dell\'' . \leslie::translate($item['singular']) . ' in oggetto.<br>';
           $message .= 'Si colleghi al portale per verificare: <a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '">' . $GLOBALS['PROJECT']['DOMAIN'] . '</a>.<br>';
           $this->mail($user['email'], $item['title'], $message);
        }
        
        \leslie::$alerts['success'] = 'notice sended'; return;
        
    }
   
    function permalinks () {
        
        $this->view->item['items_types']['id'] = $this->item['type']['id'];
        
        $this->view->item['items_languages'] = $this->model->sel(
            'SELECT id, title'
            . ' FROM items_languages'
            . ' WHERE id_content = ' . $this->item['id']
            . ' ORDER BY id'
        );

        $this->view->items = $this->model->sel(
            'SELECT p.*, la.id_language'
            . ' FROM items_languages AS la, items_permalinks AS p'
            . ' WHERE la.id_content = ' . $this->item['id']
            . ' AND p.item = la.id'
            . ' ORDER BY p.`order`, p.id DESC'
        );

        $this->view->title = $this->item['name'];
        $this->view->description = '';
        
        if ($this->user['super']) {
            $this->view->actions = '<button type="submit" name="action" value="restore_permalinks" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="">' . \leslie::translate('Restore') . '</button>';
            $this->view->actions .= '<button type="submit" name="action" value="regenerate_permalinks" class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="">' . \leslie::translate('Regenerate') . '</button>';
            $this->view->actions .= '<button type="submit" name="action" value="fix_permalinks" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="">' . \leslie::translate('Fix') . '</button>';
        }
        /*
        echo '<pre>';
        print_r($this->view->item);
        echo '</pre>'; die;
        */
      
        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/datatables/css/dataTables.bootstrap.min.css';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/jquery.dataTables.min.js';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/datatables/js/dataTables.bootstrap.min.js';
      
        $this->view->styles[] = FRAMEWORK_URL_PLUG . '/jquery/ui/1.12.0/jquery-ui.min.css';
        $this->view->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/ui/1.12.0/jquery-ui.min.js';
        $this->view->scripts[] = 'admin/permalinks';

        $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'tables_dynamic';
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'permalinks';
        $this->view->current = '';
      
   }
   
    function regenerate_permalinks () {
        
        $this->model->exec('TRUNCATE TABLE items_permalinks');
        
        $items = $this->model->sel(
            'SELECT la.id, la.title, li.id_type'
            . ' FROM item_types AS t, items_list AS li, items_languages AS la'
            . ' WHERE t.permalink = 1'
            . ' AND li.id_type = t.id'
            . ' AND la.id_content = li.id'
        );
        
        foreach ($items as $item) {
           $insert = [
                'type' => $item['id_type'],
                'item' => $item['id'],
                'value' => $this->model->generate_permalink($item['title']),
                'insert' => date('Y-m-d H:i:s'),
                'order' => 0
           ];
           $this->model->insert(
              'items_permalinks',
              $insert
           );
           $output[] = $insert;
        }
        
        echo '<pre>';
        print_r($output);
        echo '</pre>';
        die;
    }
    
    function fix_permalinks () {
        
        $items = $this->model->sel(
            'SELECT la.id AS l_id, li.id_type, p.id AS p_id'
            . ' FROM items_languages AS la, items_list AS li, items_permalinks AS p'
            . ' WHERE p.item = la.id_content'
            . ' AND li.id = la.id_content'
        );
        
        foreach ($items as $item) {
            
            $this->model->update(
                    
                'items_permalinks', 
                [
                    'item' => $item['l_id'],
                    'type' => $item['id_type']
                ], 
                'id = ' . $item['p_id']
                    
            );
            
            $output[] = $item;
            
        }
        
        echo '<pre>';
        print_r($output);
        echo '</pre>';
        exit;
    }
    
    function restore_permalinks () {
        
        $items = $this->model->sel(
            'SELECT p.id, l.id_type'
            . ' FROM items_permalinks AS p, items_list AS l, items_languages AS la'
            . ' WHERE la.id = p.item'
            . ' AND l.id = la.id_content'
        );
        
        foreach ($items as $item) {
            
            $this->model->update(
                    
                'items_permalinks', 
                [
                    'type' => $item['id_type']
                ], 
                'id = ' . $item['id']
                    
            );
            
            $output[] = $item;
            
        }
        
        echo '<pre>';
        print_r($output);
        echo '</pre>';
        die;
    }
   
    function view ($type = null, $code = null) {
        
        $this->view->item = $this->model->selone('SELECT * FROM ' . $this->item['type']['prefix'] . '_list WHERE id = ' . $this->item['id']);
        
        $this->view->item['type'] = $this->model->selone('SELECT * FROM item_types WHERE id = ' . $this->view->item['id_type']);
        
        if ($this->view->item['type']['accounts'] == 1) {
            $this->view->item['account'] = $this->model->query('SELECT email FROM users WHERE content = ' . $this->view->item['id'])->fetch(\PDO::FETCH_ASSOC);
        }
        
        $this->view->item['languages'] = $this->model->sel('SELECT * FROM ' . $this->item['type']['prefix'] . '_languages WHERE id_content = ' . $this->item['id']);
        $this->view->item['joints'] = array();
        
        //print_r($this->item['type']['joints']); die;
        
        $joints = $this->model->item_joints_ids($this->item['type']['id'], $this->item['id']);
        
        //print_r($joints); die;
        if (!empty($this->item['type']['joints'])) {
            foreach ($this->model->sel(
                'SELECT i.documents, t.id AS type, t.prefix, t.plural, t.singular, t.multiple, d.folder'
                . ' FROM item_types AS t'
                    . ' LEFT JOIN documents AS d ON d.id = ('
                        . 'SELECT id FROM documents WHERE item = t.id ORDER BY width LIMIT 1'
                    . ')'
                . ', items AS i'
                . ' WHERE t.id IN(' . $this->item['type']['joints'] . ')'
                . ' AND i.id = t.item'
                . ' ORDER BY i.`order`, t.`order`',
                \PDO::FETCH_ASSOC
            ) as $item) {

                if (!empty($joints[$item['type']])) {
                    if (empty($item['prefix'])) { $item['prefix'] = 'items'; }

                    $this->view->item['joints'][$item['type']] = $item;
                    $this->view->item['joints'][$item['type']]['items'] = $this->model->sel(
                        'SELECT li.name, la.title'
                        . ' FROM ' . $item['prefix'] . '_list AS li, ' . $item['prefix'] . '_languages AS la'
                        . ' WHERE li.id_type = ' . $item['type']
                        . ' AND li.id IN(' . implode(',', $joints[$item['type']]) . ')'
                        . ' AND la.id_content = li.id'
                    );
                }
            }
            //print_r($this->view->item['joints']); exit;
        }
        
        $this->view->item['fields'] = $this->model->sel(
            'SELECT f.value, t.label'
            . ' FROM item_types_fields AS tp, items_fields AS f, field_types AS t'
            . ' WHERE tp.id_content_type = ' . $this->item['type']['id']
            . ' AND f.id_type = tp.id_field_type'
            . ' AND f.id_content = ' . $this->item['id']
            . ' AND t.id = f.id_type'
            . ' ORDER BY t.`order`'
        );
        
        $this->view->item['type']['states'] = $this->model->item_type_states($this->item['type']['id']);

        //echo '<pre>'; print_r($this->view->item); echo '</pre>'; die;
        $this->view->template = null;
        $this->view->name = 'admin' . DIRECTORY_SEPARATOR . 'view';
        
    }
   
    function insert () {
       
        \leslie::$logs['exe'][] = 'admin insert start: ' . date('H:i:s');
      
        if (!empty($_POST['items'])) {
            
            $this->view->item = array_merge($this->view->item, $_POST['items']);

            if (isset($_FILES['document'])) {
                
                if (empty($_FILES['document']['tmp_name'])) {
                    
                    \leslie::$alerts['danger'] = 'file not uploaded';
                    unset($_REQUEST['location']);
                    return;
                    
                }
                //print_r($_FILES['document']); exit;
                $document['items_list']['name'] = $this->upload();
                //print_r($document['items_list']['name']); exit;
                $_POST['items'] = $document + $_POST['items'];
                
                unset($_FILES['document']);

            }

            scan_post:
            foreach ($_POST['items'] as $table => $values) {

                if (!empty($values)) {

                    switch ($table) {

                        case 'items_list':

                            if (empty($this->item['id'])) {

                                $items_list = $this->model->describe($table);
                                $values = array_intersect_key($values, $items_list);

                                if (empty($values['id_user'])) {
                                    $values['id_user'] = $this->user['id'];
                                }
                                
                                if (!empty($values['code'])) {
                                    
                                    if ($this->model->query(
                                        'SELECT id'
                                        . ' FROM ' . $this->item['type']['prefix'] . '_list'
                                        . ' WHERE code = "' . $values['code'] . '"'
                                    )->rowCount()) {
                                        unset($_REQUEST['location']);
                                        \leslie::$alerts['danger'] = 'code yet exist';
                                        return;
                                    }
                                    
                                } else {

                                    $values['code'] = $this->model->generate_code(
                                        $this->item['type']['prefix'] . '_list', 
                                        $GLOBALS['PROJECT']['ITEMS']['CODE']['TYPE'], 
                                        $GLOBALS['PROJECT']['ITEMS']['CODE']['LENGTH']
                                    );
                                }

                                if (!isset($values['order'])) {
                                    $values['order'] = 0;
                                }
                                
                                if (!isset($values['state'])) {
                                    $values['state'] = 0;
                                }

                                $values['id_type'] = $this->item['type']['id'];

                                $this->model->insert($this->item['type']['prefix'] . '_list', $values);
                                $this->item['id'] = $this->model->lastInsertId();

                            } else {

                                $this->model->update($this->item['type']['prefix'] . '_list', $values, 'id = ' . $this->item['id']);
                            }


                            unset($_POST['items']['items_list']);
                            goto scan_post;

                        case 'items_languages': 

                            $items_languages = $this->model->describe($table);
                             //print_r($items_languages); die;
                            $values = array_intersect_key($values, $items_languages);
                            //print_r($table_desc); die;
                            //print_r($values); die;
                            /*
                            if (!empty($values['title'])) {
                               $values['title'] = htmlentities($values['title'], 0, 'UTF-8');
                            }
                            if (!empty($values['intro'])) {
                               $values['intro'] = htmlentities($values['intro'], 0, 'UTF-8');
                            }
                            */
                            $datetime = date('Y-m-d H:i:s');
                            $values['id_content'] = $this->item['id'];
                            $values['id_language'] = $this->item['language']['id_language'];
                            $values['intro'] = substr($values['intro'], 0, 254);
                            $values['insert'] = $datetime;
                            $values['update'] = $datetime;

                            $this->model->insert($this->item['type']['prefix'] . '_languages', $values);
                            //print_r($values); exit;
                            if (!empty($this->item['type']['permalink'])) {
                                
                                $_POST['items']['items_permalinks']['item'] = $this->model->lastInsertId();
                                $_POST['items']['items_permalinks']['type'] = $this->item['type']['id'];
                                $_POST['items']['items_permalinks']['value'] = $values['title'];
                                
                            }
                            
                            unset($_POST['items']['items_languages']);
                            goto scan_post;

                        case 'items_fields':

                           foreach ($values as $val) {
                            if ($val['value'] != '') {
                              $val['id_content'] = $this->item['id'];
                              $val['id_language'] = $this->item['language']['id_language'];
                              $val['id_item_type'] = $this->item['type']['id'];
                              //print_r($val); exit;
                              $this->model->insert($table, $val);
                            }
                           }
                           unset($_POST['items']['items_fields']);
                           goto scan_post;

                        case 'items_permalinks':
                            
                            if (!empty($values['value'])) {
                                $values['order'] = 0;
                                $values['insert'] = date('Y-m-d H:i:s');
                                $values['value'] = $this->model->generate_permalink($values['value']);
                                $this->model->insert($table, $values);
                            }
                            unset($_POST['items']['items_permalinks']);
                            goto scan_post;

                        case 'items_joints':
                           //print_r($values); exit;

                           foreach ($values as $joint_type_id => $joints) {
                              //print_r($values); exit;
                              $item = $this->model->selectnoview('SELECT items.*, item_types.prefix FROM item_types, items WHERE item_types.id = :joint_type_id AND items.id = item_types.item', ['joint_type_id' => $joint_type_id]);
                              if (empty($item['prefix'])) { $item['prefix'] = 'items'; }

                              foreach ($joints as $joint_id) {

                                  $joint['insert'] = date('Y-m-d H:i:s');
                                  $joint['active'] = 1;

                                  if ($item['order'] < $this->item['order']) {
                                     $joint['id_joint'] = $this->item['id'];
                                     $joint['id_content'] = $joint_id;
                                     $joint['type'] = $this->item['type']['id'];
                                     $this->model->insert($item['prefix'] . '_joints', $joint);
                                  } else {
                                     $joint['id_content'] = $this->item['id'];
                                     $joint['id_joint'] = $joint_id;
                                     $joint['type'] = $joint_type_id;
                                     $this->model->insert($this->item['type']['prefix'] . '_joints', $joint);
                                  }
                              }
                           }
                           unset($_POST['items']['items_joints']);
                           goto scan_post;

                        default:

                           $items = $this->model->describe($table);
                           $values = array_intersect_key($values, $items);
                           //print_r($table_desc); die;
                           //print_r($values); exit;
                           $this->model->insert($table, $values);
                           unset($_POST['items'][$table]);

                    }

                }

            }
        }

        \leslie::$logs['exe'][] = 'admin insert end: ' . date('H:i:s');

    }
   
    function update () {
       
        \leslie::$logs['exe'][] = 'admin update start: ' . date('H:i:s');
        
        if (!empty($_POST['items'])) {
            
            $this->view->item = array_merge($this->view->item, $_POST['items']);
            
            if (!empty($this->item['type']['joints'])) {
                
                foreach (explode(',', $this->item['type']['joints']) as $joint_type_id) {

                    if ($item = $this->model->query('SELECT items.`order`, item_types.permanent, item_types.prefix FROM item_types, items WHERE item_types.id = ' . $joint_type_id . ' AND items.id = item_types.item')->fetch(\PDO::FETCH_ASSOC)) {

                        if (empty($item['prefix'])) { $item['prefix'] = 'items'; }

                        if ($item['order'] < $this->item['order'] && empty($this->item['type']['permanent'])) {
                            $this->model->delete($item['prefix'] . '_joints', 'active = 1 AND id_joint = ' . $this->item['id'] . ' AND (type = ' . $this->item['type']['id'] . ' OR (type = 0 AND id_content IN(SELECT id FROM ' . $item['prefix'] . '_list WHERE id_type = ' . $joint_type_id . ')))');

                        } else if (empty($item['permanent'])) {
                            $this->model->delete($this->item['type']['prefix'] . '_joints', 'active = 1 AND id_content = ' . $this->item['id'] . ' AND (type = ' . $joint_type_id . ' OR (type = 0 AND id_joint IN(SELECT id FROM ' . $item['prefix'] . '_list WHERE id_type = ' . $joint_type_id . ')))');

                        }

                    }

                }
            }
            
            if (!empty($_FILES['document']['tmp_name'])) {
                $document['items_list']['name'] = $this->upload();
                $_POST['items'] = $document + $_POST['items'];
                unset($_FILES['document']);
                
            }
            
            scan_post:
                
            foreach ($_POST['items'] as $table => $values) {

                if (!empty($values)) {
                    switch ($table) {
                        
                        case 'items_list':
                            
                            if (!empty($values['code'])) {
                                    
                                if ($this->model->query(
                                    'SELECT id'
                                    . ' FROM ' . $this->item['type']['prefix'] . '_list'
                                    . ' WHERE code = "' . $values['code'] . '"'
                                    . ' AND id != ' . $this->item['id']
                                )->rowCount()) {
                                    unset($_REQUEST['location']);
                                    \leslie::$alerts['danger'] = 'code exist yet';
                                    return;
                                }

                            } else {

                                $values['code'] = $this->model->generate_code(
                                    $this->item['type']['prefix'] . '_list', 
                                    $GLOBALS['PROJECT']['ITEMS']['CODE']['TYPE'], 
                                    $GLOBALS['PROJECT']['ITEMS']['CODE']['LENGTH']
                                );
                            }
                            
                            $this->model->update($this->item['type']['prefix'] . '_list', $values, 'id = ' . $this->item['id']);
                            \leslie::log($this->item['type']['singular'] . ' "' . $values['name'] . '" modificato da ' . $this->user['name']);
                            unset($_POST['items']['items_list']);

                            goto scan_post;
                           
                        case 'items_languages':

                            $values['update'] = date('Y-m-d H:i:s');
                            $values['intro'] = substr($values['intro'], 0, 254);
                            $this->model->update(
                                    $this->item['type']['prefix'] . '_languages',
                                    $values, 
                                    'id_content = ' . $this->item['id'] . ' AND id_language = ' . $this->item['language']['id_language']
                            );
                            
                            if (!empty($this->item['type']['permalink'])) {
                                
                                $permalink = $this->model->selone('SELECT value FROM items_permalinks WHERE item = ' . $this->item['language']['id'] . ' AND type = '.$this->item['type']['id'].' ORDER BY `order`, id DESC LIMIT 1', \PDO::FETCH_COLUMN);
                                $url_title = \functions::string_to_url($values['title']);

                                if (strpos($permalink, $url_title) === false) {

                                    $this->model->insert('items_permalinks', [
                                        'type' => $this->item['type']['id'],
                                        'item' => $this->item['language']['id'],
                                        'value' => $this->model->generate_permalink($url_title),
                                        'insert' => date('Y-m-d H:i:s'),
                                        'order' => 0
                                    ]);
                                    
                                }
                            }
                            unset($_POST['items']['items_languages']);
                            break;
                           
                        case 'items_joints':
                            //print_r($values); die;

                            foreach ($values as $joint_type_id => $joints) {
                               //print_r($values); exit;
                               $item = $this->model->query('SELECT items.`order`, item_types.permanent, item_types.prefix FROM item_types, items WHERE item_types.id = ' . $joint_type_id . ' AND items.id = item_types.item')->fetch(\PDO::FETCH_ASSOC) or die('joint type error');
                               if (empty($item['prefix'])) { $item['prefix'] = 'items'; }
                               //print_r($this->view->item); echo '<hr>';
                               //print_r($item); echo '<hr>';


                                foreach ($joints as $joint_id) {
                                    $joint['insert'] = date('Y-m-d H:i:s');
                                    $joint['active'] = 1;
                                    
                                    if ($item['order'] < $this->item['order']) {
                                        $joint['id_joint'] = $this->item['id'];
                                        $joint['id_content'] = $joint_id;
                                        $joint['type'] = $this->item['type']['id'];
                                        $this->model->insert($item['prefix'] . '_joints', $joint);
                                    } else {
                                        $joint['id_content'] = $this->item['id'];
                                        $joint['id_joint'] = $joint_id;
                                        $joint['type'] = $joint_type_id;
                                        $this->model->insert($this->item['type']['prefix'] . '_joints', $joint);
                                    }
                                }
                            }
                            //print_r($joint); die;
                            unset($_POST['items']['items_joints']);
                            goto scan_post;
                            
                        case 'items_fields':
                            //print_r($values); exit;
                            $this->model->delete($table, 
                                'id_content = ' . $this->item['id']
                                . ' AND id_language = ' . $this->item['language']['id_language']
                                . ' AND (id_item_type = '.$this->item['type']['id']
                                . ' OR (id_item_type = 0 AND id_type IN(SELECT id_field_type FROM item_types_fields WHERE id_content_type = ' . $this->item['type']['id'] . ')))'
                            );
                            
                            foreach ($values as $val) {
                               //print_r($val); exit;
                                if ($val['value'] != '') {
                                    $val['id_content'] = $this->item['id'];
                                    $val['id_language'] = $this->item['language']['id_language'];
                                    $val['id_item_type'] = $this->item['type']['id'];
                                    //print_r($val); exit;
                                    $this->model->insert($table, $val);
                                }
                            }
                            unset($_POST['items']['items_fields']);
                            goto scan_post;
                            
                        default:
                            //print_r($values); exit;
                            $this->model->update($table, $values, 'id = ' . $this->item['id']);
                            unset($_POST['items'][$table]);
                    }


                }



            }

        }

        \leslie::$logs['exe'][] = 'admin update end: ' . date('H:i:s');
   }
   
    function uploads ($type = null, $something = null) {
        //echo $item_type_id; die;
        $this->item['type'] = $this->model->query('SELECT * FROM item_types WHERE id = ' . $type)->fetch(\PDO::FETCH_ASSOC) or die ('item type error');
        if (empty($this->item['type']['prefix'])) { $this->item['type']['prefix'] = 'items'; }
        
        if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->model->insert($this->item['type']['prefix'] . '_list', [
                'name' => $this->upload('file'),
                'code' => $this->model->generate_code($this->item['type']['prefix'] . '_list'),
                'id_user' => $this->user['id'],
                'id_type' => $this->item['type']['id'],
                'active' => 1,
                'order' => 0
            ]);
            exit;
        }
        
        $this->view->template = 'gentellela' . DIRECTORY_SEPARATOR . 'form_upload';
        $this->view->title = 'Uploads';
        $this->view->current = 'documents';
        $this->view->name = null;

    }
   
    private function upload ($param = 'document') {
        
        if (!empty($_FILES[$param]['tmp_name'])) {
            //print_r($_FILES); print_r($_POST); die;

            $path = $GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $this->item['type']['plural'];
            if(!is_dir($path)){
                if (!mkdir($path, 0775, true)) {
                    die ('unable to create folder in specific path: ' . $path);
                }
            }
            if (strpos($_FILES[$param]['type'], 'image') !== false) {
                //echo var_dump(strpos($_FILES[$param]['type'], 'image')); die;
                $attributes = $this->model->select('SELECT * FROM documents WHERE item = :item_type_id', ['item_type_id' => $this->item['type']['id']]);
                foreach ($attributes as $attr) {

                    $image = new \image($_FILES[$param]['tmp_name']);
                    if (!empty($_POST['crop'][$attr['id']])) {
                        $image->crop($_POST['crop'][$attr['id']]['x'], $_POST['crop'][$attr['id']]['y'], $_POST['crop'][$attr['id']]['w'], $_POST['crop'][$attr['id']]['h']);
                    }
                    $image->resize($attr['width'], $attr['height']);
                    !empty($attr['folder'])? $folder = $path . DIRECTORY_SEPARATOR . $attr['folder']: $folder = $path;
                    $image->save($folder, $_FILES[$param]['name']);          
                    \leslie::$alerts['success'][] = 'file uploaded';

                }
                unlink($_FILES[$param]['tmp_name']);
                return $image->name;
            } else {
                move_uploaded_file($_FILES[$param]['tmp_name'], $path . DIRECTORY_SEPARATOR . $_FILES[$param]['name']);
                return $_FILES[$param]['name'];
            }
        }
        
    }
   
    function order () {
        
        if (!empty($_POST['items'])) {
            
            foreach ($_POST['items'] as $table => $fields) {
                $order = 1;
                foreach ($fields as $field => $items) {

                    foreach ($items as $item) {
                        
                        $this->model->update(
                            $table,
                            ['order' => $order],
                            $field . ' = "' . $item . '"'
                        );
                        $order++;
                        
                    }
                }
            }
        }
        
        \leslie::$alerts['success'] = 'items sorted';
        
    }
   
    function delete ($item_code = null) {
        
        if (!empty($_POST['items'])) {
            //print_r($_POST['items']);
            foreach ($_POST['items'] as $table => $fields) {
                
                foreach ($fields as $field => $items) {

                    foreach ($items as $item) {
                        //echo $table; exit;
                        //print_r($ids); exit;
                        if (strpos($table, '_list')) {

                            $item = $this->model->query(
                                'SELECT li.id, li.name'
                                . ' FROM ' . $this->item['type']['prefix'] . '_list AS li'
                                . ' WHERE li.' . $field . ' = "' . $item . '"'
                            )->fetch(\PDO::FETCH_ASSOC) or die('item select error');
                            //print_r($item); exit;

                            if ($this->user['delete'] == 1) {

                                foreach (
                                    $this->model->sel('SELECT prefix FROM item_types WHERE active = 1 AND prefix IS NOT NULL AND prefix != "" AND id IN(' . $this->item['type']['joints'] . ')', \PDO::FETCH_COLUMN)
                                    as $prefix    
                                ) {
                                    $this->model->delete($prefix . '_joints', 'id_joint = ' . $item['id'] . ' AND type = ' . $this->item['type']['id']);
                                }

                                $this->model->delete($this->item['type']['prefix'] . '_joints', 'id_content = ' . $item['id'] . ' OR id_joint = ' . $item['id']);
                                $languages = $this->model->sel('SELECT id FROM items_languages WHERE id_content = ' . $item['id'], \PDO::FETCH_COLUMN);
                                //print_r($languages); exit;
                                if ($this->item['type']['permalink']) {
                                    $this->model->delete('items_permalinks', 'type = ' . $this->item['type']['id'] . ' AND item IN(' . implode(',', $languages) . ')');
                                }
                                
                                $this->model->delete($this->item['type']['prefix'] . '_languages', 'id_content = ' . $item['id']);
                                $this->model->delete('items_fields', 'id_item_type = ' . $this->item['type']['id'] . ' AND id_content = ' . $item['id']);
                                $this->model->delete($this->item['type']['prefix'] . '_list', 'id = ' . $item['id']);

                                if ($this->item['documents']) {

                                    $docs = $this->model->sel(
                                        'SELECT name FROM ' . $this->item['type']['prefix'] . '_list',
                                        \PDO::FETCH_COLUMN
                                    );

                                    if (!in_array($item['name'], $docs)) {

                                        foreach (
                                            $this->model->sel('SELECT folder FROM documents WHERE item = ' . $this->item['type']['id'])
                                            as $doc
                                        ) {
                                            $file = $GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $this->item['type']['plural'] . DIRECTORY_SEPARATOR;
                                            if (!empty($doc['folder'])) { $file .= $doc['folder'] . DIRECTORY_SEPARATOR; }
                                            $file .= $item['name'];

                                            if (file_exists($file)) {
                                                unlink($file);
                                            } 

                                        }
                                        
                                        // try to delete file also if documents item not exist
                                        @unlink($GLOBALS['PROJECT']['PATHS']['DOCUMENTS'] . DIRECTORY_SEPARATOR . $this->item['type']['plural'] . DIRECTORY_SEPARATOR . $item['name']);
                                        
                                    }

                                }

                            } else {
                                
                                if (!empty($this->item['type']['joints'])) {

                                    foreach (
                                        $this->model->sel('SELECT prefix FROM item_types WHERE active = 1 AND prefix IS NOT NULL AND prefix != "" AND id IN(' . $this->item['type']['joints'] . ')', \PDO::FETCH_COLUMN)
                                        as $prefix    
                                    ) {
                                        $this->model->update($prefix . '_joints', ['active' => 0], 'id_joint = ' . $item['id'] . ' AND type = ' . $item['id_type']);
                                    }
                                }

                                $this->model->update($this->item['type']['prefix'] . '_list', ['active' => 0], 'id = ' . $item['id']);
                                $this->model->update($this->item['type']['prefix'] . '_joints', ['active' => 0], 'id_content = ' . $item['id']);
                            }
                        } else {
                            $this->model->delete($table, $field . ' = "' . $item . '"');
                        }
                    }
                }
            }
        }
        
    }
   
    function restore ($item_code = null) {
        
        if (!empty($_POST['items'])) {
            //print_r($_POST); exit;
            foreach ($_POST['items'] as $table => $fields) {
                
                foreach ($fields as $field => $items) {

                    foreach ($items as $item) {
                        //echo $table; exit;
                        $item_id = $this->model->query(
                            'SELECT li.id'
                            . ' FROM ' . $this->item['type']['prefix'] . '_list AS li'
                            . ' WHERE li.' . $field . ' = "'.$item.'"'
                        )->fetch(\PDO::FETCH_COLUMN) or die('item id select error');
                        //print_r($item); exit;
                        $this->model->update($this->item['type']['prefix'] . '_list', ['active' => 1], 'id = ' . $item_id);
                        $this->model->update($this->item['type']['prefix'] . '_joints', ['active' => 1], 'id_joint = ' . $item_id . ' OR id_content = ' . $item_id);

                        foreach (
                            $this->model->sel('SELECT prefix FROM item_types WHERE active = 1 AND prefix IS NOT NULL AND prefix != "" AND id IN(' . $this->item['type']['joints'] . ')', \PDO::FETCH_COLUMN)
                            as $prefix    
                        ) {
                            $this->model->update($prefix . '_joints', ['active' => 1], 'id_joint = ' . $item_id . ' AND type = ' . $this->item['type']['id']);
                        }
                    }
                }
                
            }
        }
        
    }
   
    function backup () {

        $db = new \DBBackup([
           'driver' => $GLOBALS['PROJECT']['DATABASE']['TYPE'],
           'host' => $GLOBALS['PROJECT']['DATABASE']['HOST'],
           'user' => $GLOBALS['PROJECT']['DATABASE']['USER'],
           'password' => $GLOBALS['PROJECT']['DATABASE']['PASSWORD'],
           'database' => $GLOBALS['PROJECT']['DATABASE']['NAME']
        ]);

        $backup = $db->backup();

        if(!$backup['error']){ 
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $GLOBALS['PROJECT']['DATABASE']['NAME'] . '_dbackup_' . date('d-m-Y') . '_' . date('H-i') . '.sql"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            echo $backup['msg']; die;
        } else {
            echo 'An error has ocurred.'; die;
        }

    }
   
    private function nav () {
        
        foreach ($this->model->query('SELECT id, icon, plural FROM items WHERE active = 1 ORDER BY `order`', \PDO::FETCH_ASSOC) as $key => $item) {
            $nav[$key] = $item;
            $nav[$key]['items'] = $this->model->sel('SELECT id, plural FROM item_types WHERE item = ' . $item['id'] . ' AND active = 1 ORDER BY `order`');
        }
        //print_r($nav); die;;
        return $nav;
        
    }
   
}