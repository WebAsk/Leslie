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

class base extends \model {
    
    function item_joints_ids ($item_type_id, $item_id) {
        
        $return = array();
        
        $item = $this->query('SELECT i.`order`, t.id, t.joints, t.prefix FROM item_types AS t, items AS i WHERE t.id = ' . $item_type_id . ' AND i.id = t.item')->fetch(\PDO::FETCH_ASSOC) or die('item joints select error');
        
        if (!empty($item['joints'])) {
            
            if (empty($item['prefix'])) { $item['prefix'] = 'items'; }

            foreach (
                explode(
                    ',', 
                    $item['joints']
                )
                as $joint_type_id
            ) {

                $joint = $this->selone('SELECT i.`order`, t.id, t.prefix FROM item_types AS t, items AS i WHERE t.id = ' . $joint_type_id . ' AND i.id = t.item');

                if (!empty($joint)) {
                    if (empty($joint['prefix'])) { $joint['prefix'] = 'items'; }

                    if ($joint['order'] < $item['order']) {
                        $return[$joint['id']] = $this->sel('SELECT id_content FROM ' . $joint['prefix'] . '_joints WHERE id_joint = ' . $item_id . ' AND (type = ' . $item['id'] . ' OR (type = 0 AND id_content IN(SELECT id FROM ' . $joint['prefix'] . '_list WHERE id_type = ' . $item['id'] . ')))', \PDO::FETCH_COLUMN);
                    } else {
                        $return[$joint['id']] = $this->sel('SELECT id_joint FROM ' . $item['prefix'] . '_joints WHERE id_content = ' . $item_id . ' AND (type = ' . $joint['id'] . ' OR (type = 0 AND id_joint IN(SELECT id FROM ' . $joint['prefix'] . '_list WHERE id_type = ' . $joint['id'] . ')))', \PDO::FETCH_COLUMN);
                    }
                }
            }
            //print_r($return); die;
        }
        return $return;
    }

    function generate_code ($table = 'items_list', $type = 'alphanumeric', $length = 8) {
       code_gen:
       switch ($type) {
          case 'numeric':
             $code = \functions::random_numbers($length);
             break;
          default:
             $code = \functions::generate_random_alphanumeric_string($length);
       }
       if (!empty($this->selectnoview('SELECT code FROM ' . $table . ' WHERE code = :code', array('code' => $code)))) {
          goto code_gen;
       }
       return $code;
    }

    function get_rows ($table, $limit = null, $order = 'id DESC', $where = array('id > 0'), $fields = array('*')) {
       $sql = 'SELECT ' . $table . '.' . implode(',' . $table . '.', $fields) . ' FROM ' . $table . ' WHERE ' . $table . '.' . implode(' AND ' . $table . '.', $where) . '  ORDER BY ' . $table . '.' . $order;
       if (!empty($limit)) {
          $sql .= ' LIMIT ' . $limit;
       }
       return $this->select($sql);
    }

    function get_row ($table, $where = array('id > 0'), $fields = array('*')) {
       return $this->selectnoview('SELECT ' . $table . '.' . implode(',' . $table . '.', $fields) . ' FROM ' . $table . ' WHERE ' . $table . '.' . implode(' AND ' . $table . '.', $where) . ' LIMIT 0, 1');
    }

    function get_row_by_id ($table, $id) {
       return $this->selectnoview('SELECT ' . $table . '.* FROM ' . $table . ' WHERE id = :id', array('id' => $id));
    }

}
