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

class index extends base {
   
    function index () {
        
        $this->view->title = $GLOBALS['PROJECT']['NAME'];
        $this->view->description = $GLOBALS['PROJECT']['DESCRIPTION'];
        $this->view->keywords = $GLOBALS['PROJECT']['KEYWORDS'];
        $this->view->current = 'index';
        $this->view->name = null;
        $this->view->template = 'default/header-slider-footer';
        
        $this->view->styles[] = FRAMEWORK_URL_CSS . '/leslie.css';
        
        $this->view->header['class'] = 'container-fluid bg-dark text-white xs-pt-20 xs-pb-20';
        $this->view->nav['class'] = 'navbar navbar-inverse navbar-fixed-top';
        $this->view->slider['class'] = 'container-fluid';
        $this->view->footer['class'] = 'container-fluid text-right xs-pt-20 xs-pb-20';
        $this->view->sections['name'] = 'blocks';
        
        $this->view->slides = $this->model->sel(
            'SELECT d1.name, d1.title, d1.intro, d1.description, d2.folder, f.value AS icon'
            . ' FROM documents_view AS d1'
                . ' LEFT JOIN items_fields AS f ON f.id_content = d1.id AND f.id_type = 1'
            . ', documents AS d2'
            . ' WHERE d1.plural = "slides"'
            . ' AND d2.item = d1.type'
        );
        //print_r($this->view->slides); exit;

    }
   
}