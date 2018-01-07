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

class site extends base {
   
    function map () {
        
        $GLOBALS['PROJECT']['DEBUG'] = 0;
        header("Content-type: text/xml");
        $this->view->template = 'default' . DIRECTORY_SEPARATOR . 'xml';
        $this->view->name = 'site' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR . 'xml';
        $this->view->sitemap = $this->model->select(
            'SELECT *'
            . ' FROM contents_view'
            . ' WHERE `primary` = 1'
        );
        
    }
   
    function offline () {
        
        if (!$GLOBALS['PROJECT']['OFFLINE']['SWITCH']) { header('location: ' . $GLOBALS['PROJECT']['URL']['BASE']); die; }
        $this->view->template = 'WeBuild' . DIRECTORY_SEPARATOR . 'index';
        
    }
   
}

