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

class view {
   
    public $main;
    public $styles = array();
    public $scripts = array();
    public $cookie = true;
    public $analytics;
    public $image;
    public $image_width = null;
    public $image_height = null;
    public $url;
    public $author;
    public $languages;
    public $head;
    public $body;
    public $actions;
    public $breadcrumb = array();
    public $current;
    
    public $header = array('name' => 'header', 'class' => null);
    public $nav = array('name' => 'nav', 'class' => null);
    public $slider = array('name' => 'slider', 'class' => null);
    public $footer = array('name' => 'footer', 'class' => null);
    public $sections = array('name' => 'sections');

    function __construct($view = null) {
        
       $this->template = $GLOBALS['PROJECT']['TEMPLATE'];
       $this->name = $view;
       $this->author = $GLOBALS['PROJECT']['NAME'];
       $this->url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
       $this->image = $GLOBALS['PROJECT']['LOGO'];
       $this->title = $GLOBALS['PROJECT']['NAME'];
       $this->description = $GLOBALS['PROJECT']['DESCRIPTION'];
       $this->keywords = $GLOBALS['PROJECT']['KEYWORDS'];
       $this->author = $GLOBALS['PROJECT']['NAME'];
       
    }

    final public function getStyles(){

        $output = array();

        $styles = array_merge($GLOBALS['PROJECT']['STYLES'], $this->styles);

        foreach($styles as $style) {
           if(strpos($style, 'http') === 0) {
              $output[] = '<link type="text/css" rel="stylesheet" href="' . $style . '"/>';

           }elseif (strpos($style, '<style') === 0) {
              $output[] = $style;

           }else{
              strpos($style, '.css')? $ext = NULL: $ext = '.css';
              foreach ($GLOBALS['PROJECT']['SOURCES'] as $key => $path) {
                 if (file_exists($path . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $style . $ext)) {
                    $output[] = '<link type="text/css" rel="stylesheet" href="' . $GLOBALS['PROJECT']['URLS'][$key] . '/styles/' . $style . $ext . '"/>';        
                    break;
                 }
              }            
           }
        }

        foreach ($GLOBALS['PROJECT']['SOURCES'] as $key => $path) {
           //echo $path . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $this->name . '.css';
           if (file_exists($path . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $this->name . '.css') && !in_array($this->name, $styles)) {
              $output[] = '<link type="text/css" rel="stylesheet" href="' . $GLOBALS['PROJECT']['URLS'][$key] . '/styles/' . $this->name . '.css"/>';        
              break;
           }
        }

        return implode(PHP_EOL, array_unique($output));
    }

    final private function getScripts() {
        $scripts = array_merge($GLOBALS['PROJECT']['SCRIPTS'], $this->scripts);
        $output = array();
        foreach($scripts as $script) {
           if(strpos($script, 'http') === 0) {
              $output[] = '<script type="text/javascript" src="' . $script . '"></script>';
           }elseif(strpos($script, '<script') === 0){
              $output[] = $script;
           }else{
              strpos($script, '.js')? $ext = NULL:$ext = '.js';
              foreach ($GLOBALS['PROJECT']['SOURCES'] as $key => $path) {
                 if (file_exists($path . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . $script . $ext)) {
                    $output[] = '<script type="text/javascript" src="' . $GLOBALS['PROJECT']['URLS'][$key] . '/scripts/' . $script . $ext . '"></script>';           
                    break;
                 }
              }           
           }
        }
        foreach ($GLOBALS['PROJECT']['SOURCES'] as $key => $path) {
           if (file_exists($path . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . $this->name . '.js')) {
              $output[] = '<script type="text/javascript" src="' . $GLOBALS['PROJECT']['URLS'][$key] . '/scripts/' . $this->name . '.js"></script>';           
              break;
           }
        }

        if ($this->cookie) {
            $output[] = '<script type="text/javascript">
            window.cookieconsent_options = {
            "message":"Questo sito utilizza i cookie di terze parti. Proseguendo la navigazione se ne accetta l\'utilizzo.",
            "dismiss":"Accetto",
            "learnMore":"Maggiori informazioni",
            "link":"' . $GLOBALS['PROJECT']['URL']['BASE'] . '/info/cookie",
            "theme":"dark-bottom"
            };
            </script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>';
        }
        return implode(PHP_EOL, array_unique($output));

    }

    function build () {

        $this->head[] = '<meta charset="utf8">';
        $this->head[] = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
        $this->head[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $this->head[] = '<title itemprop="name" property="og:title">' .  $this->title . ' - ' . $GLOBALS['PROJECT']['NAME'] . '</title>';
        $this->head[] = '<meta name="description" itemprop="description" property="og:description" content="' .  $this->description . '"/>';
        $this->head[] = '<meta name="keywords" itemprop="keywords" content="' .  preg_replace("/&#?[a-z0-9]+;/i", "", $this->keywords) . '"/>';
        $this->head[] = '<meta itemprop="image" property="og:image" content="' .  $this->image . '"/>';
        $this->head[] = '<meta property="og:image:width" content="' .  $this->image_width . '" />';
        $this->head[] = '<meta property="og:image:height" content="' .  $this->image_height . '" />';
        $this->head[] = '<meta property="og:url" content="' .  $this->url . '" />';
        $this->head[] = '<meta property="og:type" content="article" />';
        $this->head[] = '<meta property="og:site_name" content="' .  $GLOBALS['PROJECT']['NAME'] . '" />';
        $this->head[] = '<link rel="canonical" href="' .  $this->url . '" />';
        $this->head[] = "<script>var FRAMEWORK_URL = '" . FRAMEWORK_URL . "'; var PROJECT_URL = '" . $GLOBALS['PROJECT']['URL']['BASE'] . "'</script>";
        
        if (
            $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['TRACK']
            && $GLOBALS['PROJECT']['ONLINE']
            && !$GLOBALS['PROJECT']['USER']['ADMIN']
        ) {
            $this->head[] = "<!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['PROPERTY'] . "\"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '" . $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['PROPERTY'] . "');
            </script>";
        }
        
        if (
            $GLOBALS['PROJECT']['ONLINE']
            && !empty($GLOBALS['PROJECT']['GOOGLE']['TAG'])
        ) {
            $this->head[] = "<!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','" . $GLOBALS['PROJECT']['GOOGLE']['TAG'] . "');</script>
            <!-- End Google Tag Manager -->";
        }

        if (count($this->languages) > 1) {
            foreach ($this->languages as $language) {
                if ($language['default']) {
                    $this->head[] = '<link rel="alternate" hreflang="x-default" href="' .  $GLOBALS['PROJECT']['URL']['BASE'] . '" />';
                } else {
                    $this->head[] = '<link rel="alternate" hreflang="' .  $language['sign'] . '" href="' .  $GLOBALS['PROJECT']['URL']['BASE'] . '/' .  $language['sign'] . '" />';
                }
                    
            }
        }

        $this->head = implode(PHP_EOL, $this->head);
        
        $this->body = array();
        if (
            $GLOBALS['PROJECT']['ONLINE']
            && !empty($GLOBALS['PROJECT']['GOOGLE']['TAG'])
        ) {
            $this->body[] = '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $GLOBALS['PROJECT']['GOOGLE']['TAG'] . '"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
        }
        $this->body = implode(PHP_EOL, $this->body);

        if (file_exists($this->name)) {
            
            ob_start();
            //echo $path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->name . '.php'; die;
            include_once $this->name;
            $this->main .= ob_get_clean();
            
        } else {
            
            check_view: {
                foreach ($GLOBALS['PROJECT']['SOURCES'] as $path) {
                    if (file_exists($path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->name . '.php')) {               
                        ob_start();
                        //echo $path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->name . '.php'; die;
                        include_once $path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->name . '.php';
                        $this->main .= ob_get_clean();            
                        break;
                    }
                }
            }
            //echo $this->main; exit;
            if (empty($this->main)) {
                $this->title = '404';
                $this->description = 'Page not found';
                $this->name = 'errors/404';
                goto check_view;
            }
            
        }
        
        leslie::$logs['view'] = $this->name;
        leslie::$logs['template'] = $this->template;

    }
   
    function output () {

        $buffer = null;
        
        if (!empty($this->template)) {
            
            foreach ($GLOBALS['PROJECT']['SOURCES'] as $path) {
                
                $template = $path . DIRECTORY_SEPARATOR . 'templates';
                /*
                if (!empty($GLOBALS['PROJECT']['TEMPLATE'])) {
                    $template .= DIRECTORY_SEPARATOR . $GLOBALS['PROJECT']['TEMPLATE'];
                }
                */
                $template .= DIRECTORY_SEPARATOR . $this->template . '.php';
                //echo $template . '<hr>';
                if (file_exists($template)) {
                    //echo template; die;
                    ob_start();
                    include_once $template;
                    $buffer = ob_get_clean();
                    break;

                }
            }
            
        } else {
            
            $buffer = $this->main;
            
        }

        if ($GLOBALS['PROJECT']['MINIFY']) {
            include_once FRAMEWORK_PATH_LIB . DIRECTORY_SEPARATOR . 'minify.php';
            echo minify_html($buffer);
        } else {
            echo $buffer;
        }

    }
   
}
