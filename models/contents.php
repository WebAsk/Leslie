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

class contents extends base {
   
   function item_joints_view ($item_type, $item_id) {
      $joints_list = $this->item_joints_ids($item_type, $item_id);
      //print_r($joints_list); die;
      $output = array();
      foreach ($joints_list as $joint_item) {
         if ($joint_data = $this->selectnoview('SELECT joints_view.*'
                 . ' FROM joints_view'
                 . ' WHERE joints_view.id IN(:joint_id)'
                 . ' AND joints_view.language = :lang_id',
                 ['lang_id' => $this->language['id'], 'joint_id' => implode(',', $joint_item)]
         )) {
            $output[$joint_data['plural']][] = $joint_data;
         }
      }
      return $output;
   }
   
    function get_attributes ($id) {

        $output = array();
        foreach ($this->query(
            'SELECT f.value, t.name'
            . ' FROM items_fields AS f, field_types AS t'
            . ' WHERE f.id_content = ' . $id . ''
            . ' AND t.id = f.id_type'
            . ' AND t.site = 1'
        ) as $attr) {
            $output[$attr['name']] = $attr['value'];
        }
        return $output;

    }
   
}
