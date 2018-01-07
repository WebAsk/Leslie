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

class cart extends base {
   
    function index () {
        
        $this->view->title = 'Carrello';
        $this->view->description = 'Lista dei prodotti che hai messo nel carrello';
        $this->view->name = 'cart';
        
        $this->view->payments = $this->model->select(
            'SELECT li.code, la.title, la.intro'
            . ' FROM item_types AS t, items_list AS li, items_languages AS la'
            . ' WHERE t.active = 1'
            . ' AND t.plural = "payments"'
            . ' AND li.active = 1'
            . ' AND li.id_type = t.id'
            . ' AND la.id_content = li.id'
            . ' ORDER BY li.`order`'
        );
        //echo '<pre>'; print_r($this->view->payments); echo '</pre>'; exit;
        
        $this->view->shippings = $this->model->select(
            'SELECT li.code, la.title, la.intro'
            . ' FROM item_types AS t, items_list AS li, items_languages AS la'
            . ' WHERE t.active = 1'
            . ' AND t.plural = "shippings"'
            . ' AND li.active = 1'
            . ' AND li.id_type = t.id'
            . ' AND la.id_content = li.id'
            . ' ORDER BY li.`order`'
        );
        //echo '<pre>'; print_r($this->view->shippings); echo '</pre>'; exit;
        
        //echo '<pre>'; print_r(unserialize($_COOKIE['cart'])); echo '</pre>'; exit;
        
    }
           
    function add () {

        if (!empty($_POST['product'])) {

            if (!empty($_COOKIE['cart'])) {
               $cart = unserialize($_COOKIE['cart']);
               unset($_COOKIE['cart']);
               setcookie('cart', null, -1, '/');
            } else {
               $cart = array();
            }
            //print_r($cart); die;
            
            $item = $this->model->selectnoview(
                'SELECT c.code, c.title, c.image, c.permalink, f.value AS price'
                . ' FROM contents_view AS c, items_fields AS f'
                . ' WHERE c.code = :code'
                . ' AND f.id_type = 1'
                . ' AND f.id_content = c.id',
                array('code' => $_POST['product']['code'])
            );
            
            $item['quantity'] = 1;
            $item['size']['code'] = $_POST['product']['size']['code'];
            
            $item['size']['title'] = $this->model->selectnoview(
                'SELECT la.title'
                . ' FROM items_list AS li, items_languages AS la'
                . ' WHERE li.code = :code'
                . ' AND la.id_content = li.id'
                . ' AND la.id_language = :language', 
                [
                    'code' => $item['size']['code'],
                    'language' => $this->model->language['id']
                ],
                \PDO::FETCH_COLUMN
            );
            
            $item['color']['code'] = $_POST['product']['color']['code'];
            
            $item['color']['title'] = $this->model->selectnoview(
                'SELECT la.title'
                . ' FROM items_list AS li, items_languages AS la'
                . ' WHERE li.code = :code'
                . ' AND la.id_content = li.id'
                . ' AND la.id_language = :language',
                [
                    'code' => $item['color']['code'],
                    'language' => $this->model->language['id']
                ],
                \PDO::FETCH_COLUMN  
            );
            //print_r($item); die;
            
            $cart[] = $item;
            setcookie('cart', serialize($cart), time() + (3 * 24 * 60 * 60), '/');
        }
         
        header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart'); exit;
         
    }
   
    function update ($index, $key, $val) {

         //die;

         $cart = unserialize($_COOKIE['cart']);

         unset($_COOKIE['cart']);
         setcookie('cart', null, -1, '/');

         $cart[$index][$key] = $val;

         setcookie('cart', serialize($cart), time() + (3 * 24 * 60 * 60), '/');

         header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/cart'); die;

     }

    function remove ($code) {
       //echo $code; die;
       if (!empty($_COOKIE['cart'])) {

          $cart = unserialize($_COOKIE['cart']);
          //print_r($cart); die;
          unset($_COOKIE['cart']);
          setcookie('cart', null, -1, '/');
          $finded = false;
          foreach ($cart as $key => $val) {
             if ($val['code'] == $code && $cart[$key]['quantity'] > 1) {
                 $cart[$key]['quantity'] = $val['quantity'] - 1;
             }
          }
          setcookie('cart', serialize($cart), time() + (3 * 24 * 60 * 60), '/');
       }
       header('location: ' . $_SERVER['HTTP_REFERER']);
    }

    function delete () {

        if (!empty($_COOKIE['cart']) && !empty($_POST['product'])) {

            $cart = unserialize($_COOKIE['cart']);

            unset($_COOKIE['cart']);
            setcookie('cart', null, -1, '/');
            foreach ($cart as $key => $val) {
               if ($val['code'] == $_POST['product']['code'] && $val['size']['code'] = $_POST['product']['size']['code']) {
                   unset($cart[$key]);
                   break;
               }
            }
            setcookie('cart', serialize($cart), time() + (3 * 24 * 60 * 60), '/');
        }
        
        header('location: ' . $_SERVER['HTTP_REFERER']);
        
    }
   
   
}
