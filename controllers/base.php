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

class base extends \controller {
   
    function __construct($view, $model = '\\framework\\models\\base') {

        parent::__construct($view, $model);
        
        if (empty($this->model)) {
            return;
        }
        
        foreach ($this->model->sel(
            
            'SELECT *'
            . ' FROM item_types'
                
        ) as $item) {
            
            $this->view->types[$item['id']] = $item;
            
        }
        
        //echo '<pre>'; print_r($this->view->types); echo '<pre>'; exit;
        
        $this->view->joints = $this->get_joints();
        
        $this->view->joint_contents = $this->get_joint_contents();
        
        $this->view->unjoint_contents = $this->model->sel(
            'SELECT la.title, p.value AS permalink'
            . ' FROM items AS i, item_types AS t, items_list AS li, items_languages AS la, items_permalinks AS p'
            . ' WHERE i.contents = 1'
            . ' AND t.joint = 0'
            . ' AND t.item = i.id'
            . ' AND t.active = 1'
            . ' AND t.navigation = 1'
            . ' AND li.active = 1'
            . ' AND li.id_type = t.id'
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = ' . $this->model->language['id']
            . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = t.id AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
        );
        
        $this->view->last_contents = $this->model->sel(
                
            'SELECT t.singular, la.title, la.intro, p.value AS permalink, im.name AS image'
            . ' FROM items AS it, item_types AS t, items_list AS li'
            . ' LEFT JOIN items_list AS im ON im.id = (SELECT id_joint FROM items_joints WHERE id_content = li.id AND type IN(SELECT item_types.id FROM items, item_types WHERE items.documents = 1 AND item_types.item = items.id) LIMIT 0, 1)'
            . ', items_languages AS la, items_permalinks AS p'
            . ' WHERE it.contents = 1'
            . ' AND it.active = 1'
            . ' AND t.item = it.id'
            . ' AND t.`primary` = 1'
            . ' AND t.active = 1'
            . ' AND li.id_type = t.id'
            . ' AND li.active = 1'
            . ' AND (li.state = 0 OR li.state IN(SELECT id FROM item_states WHERE item = li.id_type AND view = 1))'
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = ' . $this->model->language['id']
            . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = t.id AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
            . ' ORDER BY la.`insert` DESC'
            . ' LIMIT 0, 4'
                
        );
        
        $this->view->popular_contents = $this->model->sel(
                
            'SELECT t.singular, la.title, la.intro, la.`insert`, p.value AS permalink, im.name AS image'
            . ' FROM items AS it, item_types AS t, items_list AS li'
            . ' LEFT JOIN items_list AS im ON im.id = (SELECT id_joint FROM items_joints WHERE id_content = li.id AND type IN(SELECT item_types.id FROM items, item_types WHERE items.documents = 1 AND item_types.item = items.id) LIMIT 0, 1)'
            . ', items_languages AS la, items_permalinks AS p'
            . ' WHERE it.contents = 1'
            . ' AND it.active = 1'
            . ' AND t.item = it.id'
            . ' AND t.`primary` = 1'
            . ' AND t.active = 1'
            . ' AND li.id_type = t.id'
            . ' AND li.active = 1'
            . ' AND (li.state = 0 OR li.state IN(SELECT id FROM item_states WHERE item = li.id_type AND view = 1))'
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = ' . $this->model->language['id']
            . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = t.id AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
            . ' ORDER BY la.`views` DESC'
            . ' LIMIT 0, 4'
                
        );
        
        $this->view->disclosures = $this->model->sel(
                
            'SELECT t.singular, la.title, la.intro, la.`insert`, p.value AS permalink'
            . ' FROM items AS it, item_types AS t, items_list AS li, items_languages AS la, items_permalinks AS p'
            . ' WHERE it.contents = 1'
            . ' AND it.active = 1'
            . ' AND t.item = it.id'
            . ' AND t.`primary` = 0'
            . ' AND t.active = 1'
            . ' AND li.id_type = t.id'
            . ' AND li.active = 1'
            . ' AND (li.state = 0 OR li.state IN(SELECT id FROM item_states WHERE item = li.id_type AND view = 1))'
            . ' AND la.id_content = li.id'
            . ' AND la.id_language = ' . $this->model->language['id']
            . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = t.id AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
            . ' ORDER BY li.`order` DESC'
            . ' LIMIT 0, 4'
                
        );
        //echo '<pre>'; print_r($this->view->disclosures); echo '<pre>'; exit;
        
        foreach ($this->model->query(
                'SELECT *'
                . ' FROM languages'
                . ' WHERE active = 1',
                \PDO::FETCH_ASSOC
        ) as $language) {
            $this->view->languages[$language['id']] = $language;
        }
        //print_r($this->view->languages); exit;

        //echo '<pre>'; print_r($this->view->joint_contents); echo '<pre>'; exit;
        
    }


    private function get_joint_contents () {
        
        $nav = array();
        
        $types = $this->model->sel(
            'SELECT t.id, t.plural, t.joints'
            . ' FROM items AS i, item_types AS t'
            . ' WHERE i.active = 1'
            . ' AND i.contents = 1'
            . ' AND t.item = i.id'
            . ' AND t.active = 1'
            . ' AND t.joint = 1'
            . ' AND t.navigation = 1'
        );
        
        //print_r($types); die;
        
        foreach ($types as $type) {
            
            $joints = $this->model->sel(
                'SELECT t.id AS type, t.plural, li.id, la.title, la.intro, p.value AS permalink'
                . ' FROM items AS i, item_types AS t, items_list AS li, items_languages AS la, items_permalinks AS p'
                . ' WHERE i.joints = 1'
                . ' AND t.item = i.id'
                . ' AND t.id IN(' . $type['joints'] . ')'
                . ' AND t.navigation = 1'
                . ' AND t.active = 1'
                . ' AND li.id_type = t.id'
                . ' AND li.active = 1'
                . ' AND (li.state = 0 OR li.state IN(SELECT id FROM item_states WHERE item = li.id_type AND view = 1))'
                . ' AND la.id_content = li.id'
                . ' AND la.id_language = ' . $this->model->language['id']
                . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = li.id_type AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
                . ' ORDER BY li.`order`'
            );
            
            foreach ($joints as $joint) {
                
                if ($joint['items'] = $this->model->query('SELECT COUNT(id_content) FROM items_joints WHERE id_joint = ' . $joint['id'] . ' AND id_content IN(SELECT id FROM items_list WHERE id_type = ' . $type['id'] . ')')->fetch(\PDO::FETCH_COLUMN)) {
                    
                    $nav[$type['id']][$joint['type']][$joint['id']] = $joint;
                    
                }
            }

        }
        
        //echo '<pre>'; print_r($nav); echo '</pre>'; exit;

        return $nav;
        
    }
    
    private function get_joints () {
        
        $joints = array();
        
        foreach ($this->model->query(
                
           'SELECT t.id, t.plural, t.multiple'
            . ' FROM items AS i, item_types AS t'
            . ' WHERE i.joints = 1'
            . ' AND t.item = i.id'
            . ' AND t.active = 1'
            . ' AND t.navigation = 1'
            . ' ORDER BY t.`order`',
            \PDO::FETCH_ASSOC
                
        ) as $type) {
            
            $joints[$type['plural']] = $type;
            $joints[$type['plural']]['items'] = $this->model->sel(
                'SELECT la.title, la.intro, p.value AS permalink, im.name AS image'
                . ' FROM items_list AS li'
                . ' LEFT JOIN items_list AS im ON im.id = (SELECT id_joint FROM items_joints WHERE id_content = li.id AND type IN(SELECT item_types.id FROM items, item_types WHERE items.documents = 1 AND item_types.item = items.id) LIMIT 0, 1)'
                . ', items_languages AS la, items_permalinks AS p'
                . ' WHERE li.id_type = ' . $type['id']
                . ' AND li.active = 1'
                . ' AND (li.state = 0 OR li.state IN(SELECT id FROM item_states WHERE item = li.id_type AND view = 1))'
                . ' AND la.id_content = li.id'
                . ' AND la.id_language = ' . $this->model->language['id']
                . ' AND p.id = (SELECT id FROM items_permalinks WHERE type = li.id_type AND item = la.id ORDER BY `order`, `insert` DESC LIMIT 0, 1)'
                . ' ORDER BY li.`order`'
            );
            
        }
        
        return $joints;
        
    }
   
   
}