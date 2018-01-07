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

class checkout extends account {
    
    function __construct($view, $model = '\\framework\\models\\account') {
        
        parent::__construct($view, $model);
        
        if (!isset($_REQUEST['payment']) || !isset($_REQUEST['payment'])) {
            header('location: ' . \leslie::$href . '/cart'); exit;
        }
        
        $this->costumer['prefix'] = $this->model->selone('SELECT prefix FROM item_types WHERE accounts = 1', \PDO::FETCH_COLUMN);
        if (empty($this->costumer['prefix'])) { $this->costumer['prefix'] = 'items'; }
        
        $this->shipping['prefix'] = $this->model->selone('SELECT prefix FROM item_types WHERE plural = "shippings"', \PDO::FETCH_COLUMN);
        if (empty($this->shipping['prefix'])) { $this->shipping['prefix'] = 'items'; }
        
        $this->payment['prefix'] = $this->model->selone('SELECT prefix FROM item_types WHERE plural = "payments"', \PDO::FETCH_COLUMN);
        if (empty($this->payment['prefix'])) { $this->payment['prefix'] = 'items'; }
        
    }
   
    function index () {
        
        $this->view->costumer = array();
        $this->view->costumer['fields'] = $this->model->sel(
            'SELECT t.label, f.value'
            . ' FROM ' . $this->costumer['prefix'] . '_list AS li'
                . ', items_fields AS f'
                . ', field_types AS t'
            . ' WHERE li.id = ' . $this->user['content']
            . ' AND f.id_content = li.id'
            . ' AND (f.id_item_type = li.id_type OR f.id_item_type = 0)'
            . ' AND t.id = f.id_type'
            . ' AND t.site = 1'
            . ' ORDER BY t.order'
        );

        $this->view->payment = $this->model->selectnoview(
            'SELECT li.id, li.code, la.title'
            . ' FROM ' . $this->payment['prefix'] . '_list AS li,'
            . ' ' . $this->payment['prefix'] . '_languages AS la'
            . ' WHERE li.code = :payment'
            . ' AND la.id_content = li.id', 
            ['payment' => $_REQUEST['payment']]
        );
        
        $this->view->shipping = $this->model->selectnoview(
            'SELECT li.code, la.title, f.value AS cost'
            . ' FROM ' . $this->shipping['prefix'] . '_list AS li'
                . ' LEFT JOIN items_fields AS f ON f.id_content = li.id AND f.id_item_type = li.id_type'
            . ', ' . $this->shipping['prefix'] . '_languages AS la'
            . ' WHERE li.code = :shipping'
            . ' AND la.id_content = li.id',
            ['shipping' => $_REQUEST['shipping']]
        );

       $this->view->title = 'Checkout';
       $this->view->name = 'checkout';

    }
}

