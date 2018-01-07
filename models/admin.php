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

class admin extends account {
    
    function item_type_states ($item_type_id) {
       $item_type_states = $this->select('SELECT * FROM item_states WHERE item = :item_type_id', ['item_type_id' => $item_type_id]);
       $output = array();
       foreach ($item_type_states as $state) {
          $output[$state['id']] = $state;
       }
       return $output;
    }

    function languages () {
       $languages = $this->select('SELECT * FROM languages');
       foreach ($languages as $lang) {
          $output[$lang['id']] = $lang;
       }
       return $output;
    }

    function get_items_with_language_by_type_id ($table, $id) {
       return $this->select('SELECT ' . $table . '.*, languages.sign AS lang FROM ' . $table . ', languages WHERE ' . $table . '.id_type = :id AND languages.id = ' . $table . '.id_language', array('id' => $id));
    }

    function get_content_language ($content_id, $language_id) {
       return $this->selectnoview("SELECT * FROM items_languages WHERE id_content = :content_id AND id_language = :language_id", array('content_id' => $content_id, 'language_id' => $language_id));
    }

    function get_item_types ($table) {
       $results = $this->get_rows($table);
       $types = array();
       foreach ($results as $result) {
          $types[$result['id']] = $result;
       }
       //print_r($types); exit;
       return $types;
    }

    function get_documents ($type_id, $content_id = null) {
       $sql = 'SELECT documents.*, languages.sign AS lang FROM languages, documents';
       if (!empty($content_id)) {
          $sql .= ' INNER JOIN contents_documents ON(contents_documents.id_document = documents.id AND contents_documents.id_content = ' . $content_id . ')';
       }
       $sql .= ' WHERE documents.id_type = :type_id AND languages.id = documents.id_language ORDER BY documents.order';
       return $this->select($sql, array('type_id' => $type_id));
    }

    function get_contents_ids_by_document_id ($document_id) {
       $result = $this->select('SELECT id_content FROM contents_documents WHERE id_document = :document_id', array('document_id' => $document_id));
       //print_r($result); exit;
       $contents = array();
       foreach ($result as $key => $val) {
          $contents[] = $val['id_content'];
       }
       return $contents;
    }
    
    function item_type_fields ($item_type_id) {
       return $this->sel('SELECT field_types.* FROM field_types, item_types_fields WHERE item_types_fields.id_content_type = ' . $item_type_id . ' AND item_types_fields.id_field_type = field_types.id ORDER BY field_types.`order`');
    }
    
    function generate_permalink ($string) {
       $suffix = null;
       $separator = null;
       generate_permalink:
       $permalink = \functions::string_to_url($string . $separator . $suffix);
       if (!empty($this->selectnoview('SELECT value FROM items_permalinks WHERE value = :permalink', array('permalink' => $permalink)))) {
          $separator = '-';
          $suffix++;
          goto generate_permalink;
       }
       return $permalink;
    }
   
}
