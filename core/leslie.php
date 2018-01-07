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

class leslie {
   
    static $requests;
    static $language;
    static $lang;
    public static $locale;
    public static $href;
    public static $logs = array();
    public static $alerts = array();
    
    private $locales = array(
        'en' => 'en_GB.utf8',
        'it' => 'it_IT.utf8'
    );
    
    private $system = array();

    function __construct() {
        
        $this->system['locales'] = explode("\n", shell_exec('locale -a'));

        //echo ini_get("session.gc_maxlifetime");
        //ini_set("session.gc_maxlifetime", $GLOBALS['PROJECT']['SESSION']['TIME']);
        //echo ini_get("session.gc_maxlifetime");
        //ini_set("session.cookie_lifetime", $GLOBALS['PROJECT']['SESSION']['TIME']);
        //echo '<pre>'; print_r(session_get_cookie_params()); echo '</pre>';
        
        session_start();
        
        if (!isset($_COOKIE['cookie'])) { @setcookie('cookie', 1); }

        $request = trim(str_replace(PROJECT_FOLDER, null, $_SERVER['REQUEST_URI']), '/');

        if ($GLOBALS['PROJECT']['OFFLINE']['SWITCH'] && !strstr($request, 'site/offline') && !isset($_SESSION['code'])) {
            header('location: ' . $GLOBALS['PROJECT']['URL']['BASE'] . '/site/offline'); die;
        }

        @list($request, $params) = explode('?', $request);
        $request = trim($request, '/');
        $request = explode('/', $request);
        $request = array_filter($request);
        self::$requests = $request;
        //print_r($request); die;
        
        self::$href = $GLOBALS['PROJECT']['URL']['BASE'];

        if (isset(self::$requests[0]) && file_exists(FRAMEWORK_PATH_LANG . DIRECTORY_SEPARATOR . self::$requests[0] . '.ini')) {
           
            self::$language = parse_ini_file(FRAMEWORK_PATH_LANG . DIRECTORY_SEPARATOR . self::$requests[0] . '.ini');
            leslie::$logs['language'] = self::$requests[0];
            self::$lang = self::$requests[0];
            self::$locale = $this->locales[self::$requests[0]];
            self::$href .= '/' . self::$requests[0];
            array_shift(self::$requests);
           
        } else {
            
            self::$language = parse_ini_file(FRAMEWORK_PATH_LANG . DIRECTORY_SEPARATOR . $GLOBALS['PROJECT']['LANGUAGE']['SIGN'] . '.ini');
            self::$logs['language'] = $GLOBALS['PROJECT']['LANGUAGE']['SIGN'];
            self::$lang = $GLOBALS['PROJECT']['LANGUAGE']['SIGN'];
            self::$locale = $this->locales[$GLOBALS['PROJECT']['LANGUAGE']['SIGN']];
           
        }
        
        //echo self::$locale; exit;
        
        leslie::$logs['request'] = self::$requests;
        
        if (in_array(self::$locale, $this->system['locales'])) {
            
            putenv('LANG=' . self::$locale);

            setlocale(LC_ALL, self::$locale);
            
        }

        self::$logs['exe'][] = 'Leslie start: ' . date('H:i:s');

        if (!empty(self::$requests)) {

           //print_r(self::$requests); exit;

            $view = NULL;
            $view_dir = NULL;
            $controller_dir = NULL;

            self::$logs['exe'][] = 'router start: ' . date('H:i:s');
            
            //scansiono la richiesta
            foreach (self::$requests as $key => $param) {
                
                // scompongo le parole della richiesta eliminando il trattino
                $param_phrase = str_replace('-', ' ', $param);
                // traduco la richiesta dalla lingua impostata a quella di default
                $param_trans = self::etalsnart($param_phrase);
                // ricompongo il parametro della richiesta tradotto col trattino per renderlo compatibile ai nomi dei files
                $view_name = str_replace(' ', '-', $param_trans);
                // ricompongo il parametro della richiesta tradotto con underscore per renderlo compatibile ai nomi delle classi
                $param_underscore = str_replace(' ', '_', $param_trans);
                

                if (empty($controller)) {
                    
                    if (!$controller = self::set_class('controller', $param_underscore . '\\' . self::request_param_to_class_name(next(self::$requests)))) {
                        
                        $controller = self::set_class('controller', $param_underscore);
                        
                    }
                    
                }

                if (empty($model)) {
                    
                   if (!$model = self::set_class('model', $param_underscore . '\\' . self::request_param_to_class_name(next(self::$requests)))) {
                        
                        $model = self::set_class('model', $param_underscore);
                        
                    }
                }

                if (!empty($this->methods) && !is_callable(array($controller, $param))) {
                    $this->params[] = $param;
                }

                if (!empty($controller)) {
                    
                    //echo 'controller: ' . $controller . ', method: ' . $param_underscore . ' = ' . var_dump(is_callable(array($controller, $param_underscore))) . '<hr>';
                    
                    if (is_callable(array($controller, $param_underscore))) {
                       $this->methods[] = $param_underscore;
                    }
                    
                }

                self::$logs['exe'][] = 'view start: ' . date('H:i:s');

                foreach ($GLOBALS['PROJECT']['SOURCES'] as $path) {
                    //echo $path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view_dir . $view_name . '.php <hr>';
                    if (file_exists($path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view_dir . $view_name . '.php')) {
                       $view = $view_dir . $view_name;
                       break;
                    } else if (is_dir($path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view_dir . $view_name)) {
                       $view_dir .= $view_name . DIRECTORY_SEPARATOR;
                       break;
                    }
                }

                self::$logs['exe'][] = 'view end: ' . date('H:i:s');
            }
            
            self::$logs['exe'][] = 'router end: ' . date('H:i:s');

        } else {
            
            $controller = self::set_class('controller', 'index');
            $model = self::set_class('model', 'index');
            $view = 'index';
            
        }
        
        if (empty($controller)) {
            $controller = self::set_class('controller', 'errors');
        }

        if (empty($this->methods) && is_callable(array($controller, 'index'))) {
            $this->methods[] = 'index';
        }

        $this->view = new view();
        
        if (!empty($view)) {
            
            $this->view->name = $view;
            
            bindtextdomain($view, FRAMEWORK_PATH . DIRECTORY_SEPARATOR . 'languages');

            bind_textdomain_codeset($view, 'UTF-8');

            textdomain($view);
            
        }
        
        self::$logs['exe'][] = 'MVC start: ' . date('H:i:s');
        if (!empty($model)) {
            
            $this->model = new $model();
            leslie::$logs['model'] = $model;
            $this->controller = new $controller($this->view, $this->model);
            
        } else {

            $this->controller = new $controller($this->view);
            
        }
        
        self::$logs['exe'][] = 'MVC end: ' . date('H:i:s');

        self::$logs['exe'][] = 'call methods start: ' . date('H:i:s');
        $this->call_methods();
        self::$logs['exe'][] = 'call methods end: ' . date('H:i:s');
        
        //print_r($_REQUEST); exit;

        if (!empty($_REQUEST['action']) && is_callable(array($this->controller, $_REQUEST['action']))) {

            \leslie::$logs['exe'][] = 'action start: ' . date('H:i:s');

            if (!empty($_REQUEST['param'])) {
                if (is_array($_REQUEST['param'])) {

                    @list($param1, $param2, $param3) = $_REQUEST['param'];
                    $this->controller->{$_REQUEST['action']}($param1, $param2, $param3);

                } else {
                    $this->controller->{$_REQUEST['action']}($_REQUEST['param']);
                }
            } else {
                $this->controller->{$_REQUEST['action']}();
            }

            self::$logs['methods'][] = $_REQUEST['action'];
            unset($_REQUEST['action']);

            if (isset($_REQUEST['location'])) {
               self::$logs['location'] = $_REQUEST['location'];
               self::$logs['exe'][] = 'location start: ' . date('H:i:s');
               header('location: ' . $_REQUEST['location']);
               unset($_REQUEST['location']);
               die;
            }


            if (!empty($_REQUEST['refresh'])) {
                $this->call_methods();
            }

        }

        if (!empty($_REQUEST['alert'])) {
            self::$alerts[$_REQUEST['alert']][] = urldecode($_REQUEST['message']);

        }
        
        \leslie::$logs['exe'][] = 'core view build start: ' . date('H:i:s');
        $this->view->build();
        \leslie::$logs['exe'][] = 'core view buid end: ' . date('H:i:s');

        if ($GLOBALS['PROJECT']['DEBUG']) {
           $this->view->styles[] = 'debug';
        };
        
        \leslie::$logs['exe'][] = 'core view out start: ' . date('H:i:s');
        
        $this->view->output();
        \leslie::$logs['exe'][] = 'core view out end: ' . date('H:i:s');
        
        self::$logs['exe'][] = 'Leslie end: ' . date('H:i:s');

    }
    
    private function request_param_to_class_name ($param) {
        
        return str_replace(' ', '_', self::etalsnart(str_replace('-', ' ', $param)));
        
    }

    private function call_methods () {
        
        if (!empty($this->methods)) {

            foreach ($this->methods as $method) {
                
                self::$logs['methods'][] = $method;
                
                //$method_info = new ReflectionMethod($this->router->controller, $method);
                //$params = $method_info->getParameters();
                //print_r($method); exit;

                if (!empty($this->params)) {
                    leslie::$logs['params'] = $this->params;
                    call_user_func_array(array($this->controller, $method), $this->params);
                } else {
                    $this->controller->{$method}();
                }
            }
        }

    }

    static function set_class ($type, $name) {
        
        if (class_exists('project\\' . $type . 's\\' . $name)) {
            
            self::$logs[$type] = 'project\\' . $type . 's\\' . $name;
            self::$logs['exe'][] = $type . ' set: ' . date('H:i:s');
            return 'project\\' . $type . 's\\' . $name;
            
        } else if (class_exists('framework\\' . $type . 's\\' . $name)) {
            
            self::$logs[$type] = 'framework\\' . $type . 's\\' . $name;
            self::$logs['exe'][] = $type . ' set: ' . date('H:i:s');
            return 'framework\\' . $type . 's\\' . $name;
            
        } else {
            return false;
        }
        
    }
    
    static function alert ($message, $degree = 1) {
        
        self::$alerts['danger'][] = $message;
        
    }

    static function log($value) {
        if ($GLOBALS['PROJECT']['LOGS']) {
            $logs_folder = $GLOBALS['PROJECT']['PATHS']['ROOT'] . DIRECTORY_SEPARATOR . 'logs';
            if (!is_dir($logs_folder)) {
               mkdir($logs_folder, 0775);
            }
            $log_file_prefix = $logs_folder . DIRECTORY_SEPARATOR . \functions::string_to_url($GLOBALS['PROJECT']['NAME']) . '-log-';
            $old_log_file = $log_file_prefix . date('d-m-Y', strtotime('-1 month')) . '.csv';
            //die($old_log_file);
            if (file_exists($old_log_file)) {
               unlink($old_log_file);
            }
            $log_file =  $log_file_prefix . date('d-m-Y') . '.csv';
            $file_line = date('d/m/Y H:i:s') . ';' . $value . PHP_EOL;
            file_put_contents($log_file, $file_line, FILE_APPEND);
        }
        return;
    } 

    static function translate ($string) {
        $matches = preg_match("/^([A-Z])/", $string);
        $string = strtolower($string);
        if(isset(self::$language[$string])) {
            $res = self::$language[$string];
        }else{
            $words = explode(' ', $string);
            foreach ($words as $word) {
                $suffix = null;
                if (preg_match("/[\.,:;]/", $word, $match)) { $word = substr($word, 0, -1); $suffix = $match[0]; }
                isset(self::$language[$word]) ? $output[] = self::$language[$word].$suffix : $output[] = $word.$suffix;
            }
            $res = implode(' ', $output);
        }

        if ($matches) {
            return ucfirst($res);
        } else {
            return $res;
        }
    }

    static function etalsnart ($string) {
        return ($trans = array_search($string, self::$language)) ? $trans : $string ;
    }

    function __destruct() {
        if ($GLOBALS['PROJECT']['DEBUG']) {
            $debug = '<pre>' . PHP_EOL;
            $debug .= 'Leslie log' . PHP_EOL;
            $debug .= @print_r(leslie::$logs, true);
            foreach ($GLOBALS as $key => $global) {
                $debug .= 'GLOBALS ' . $key . PHP_EOL;
                $debug .= @print_r($global, true);
            }
            $debug .= 'SYSTEM:' . PHP_EOL;
            $debug .= @print_r($this->system, true);
            $debug .= 'SERVER' . PHP_EOL;
            $debug .= @print_r($_SERVER, true);
            $debug .= 'GET' . PHP_EOL;
            $debug .= @print_r($_GET, true);
            $debug .= 'POST' . PHP_EOL;
            $debug .= @print_r($_POST, true);
            $debug .= 'FILES' . PHP_EOL;
            $debug .= @print_r($_FILES, true);
            $debug .= 'REQUEST' . PHP_EOL;
            $debug .= @print_r($_REQUEST, true);
            $debug .= 'SESSION' . PHP_EOL;
            $debug .= @print_r($_SESSION, true);
            $debug .= 'COOKIE' . PHP_EOL;
            $debug .= print_r($_COOKIE, true);
            
            $debug .= '</pre>' . PHP_EOL;
            die($debug);
        };
    }
}


