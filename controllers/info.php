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

class info extends base {
   
    function __construct($view, $model = '\\framework\\models\\base') {

        parent::__construct($view, $model);

        $this->view->template .= DIRECTORY_SEPARATOR . 'contents';
        $this->view->head[] = '<meta name="robots" content="noindex, nofollow">';

    }

    function privacy () {
        
        $this->view->title = 'Privacy';
        $this->view->description = 'Informativa sulla privacy';
        $this->view->breadcrumb[] = 'privacy';
        $this->view->name = 'info/privacy';
        
    }

    function cookie () {
        
        $this->view->title = 'Cookie';
        $this->view->description = 'Cookie policy';
        $this->view->breadcrumb[] = 'cookie';
        $this->view->name = 'info/cookie';
        
    }
    
    function php () {
        
        if ($GLOBALS['PROJECT']['DEBUG']) {
            echo phpinfo(); exit;
        }
        
    }
    
}