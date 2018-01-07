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

namespace framework\models;

class account extends base {
    
    public $item;
    
    function __construct() {
        
        parent::__construct();
        
        $this->item['type'] = $this->query('SELECT id, singular, prefix FROM item_types WHERE accounts = 1')->fetch(\PDO::FETCH_ASSOC);
        if (empty($this->item['type']['prefix'])) { $this->item['type']['prefix'] = 'items'; }
        
    }
   
    function user ($field, $value) {
       
        $user = $this->selectnoview(
            'SELECT u.id, u.code, u.email, u.content, u.type, t.admin, t.delete, t.super, l.title AS name, i.name AS image'
            . ' FROM users AS u'
                . ' LEFT JOIN ' . $this->item['type']['prefix'] . '_languages AS l ON id_content = u.content'
                . ' LEFT JOIN user_types AS t ON t.id = u.type'
                . ' LEFT JOIN items_list AS i ON i.id = (SELECT id_joint FROM items_joints WHERE id_content = u.content AND type IN(SELECT item_types.id FROM item_types, items WHERE items.documents = 1 AND item_types.item = items.id) LIMIT 1)'
            . ' WHERE u.`' . $field . '` = :value',
            ['value' => $value]
        );
        
        if (!empty($user) && !empty($this->item['type']['id'])) {
            $user['prefix'] = $this->item['type']['prefix'];
            $user['item_type_id'] = $this->item['type']['id'];
        }
        //echo '<pre>' . print_r($user, true) . '</pre>'; exit;
        
        return $user;
        
    }
    
    function fields ($type, $id, $lang = null) {
        
        if (empty($lang)) { $lang = $this->language['id']; }
        
        $fields = array();
        
        foreach ($this->select(
            'SELECT tf.id_field_type'
            . ' FROM item_types_fields AS tf'
            . ' WHERE tf.id_content_type = :type', 
            array('type' => $type)
        ) as $f) {
            
            $fields[$f['id_field_type']] = null;
            
        }
        
        foreach ($this->select(
            'SELECT f.id_type, f.value'
            . ' FROM items_fields As f'
            . ' WHERE f.id_content = :id'
            . ' AND f.id_language = :lang', 
            array('id' => $id, 'lang' => $lang)
        ) as $f) {
            
            $fields[$f['id_type']] = $f['value'];
            
        }
        
        return $fields;
        
    }

    function password ($uncrypt) {
        
        $context = hash_init('sha256', HASH_HMAC, $GLOBALS['PROJECT']['PASSWORD']['SALT']);
        hash_update($context, $uncrypt);
        return hash_final($context);
        
    }
    
}
