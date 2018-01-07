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

class functions {
    
    static function string_to_url ($str) {
        $str = str_replace(array('à','è','é','ì','ò','ù'), array('a','e','e','i','o','u'), urldecode($str));
        $str = preg_replace('/([^A-Za-z0-9_])/', '-', $str);
        return strtolower(trim(preg_replace('/-{2,}/', '-', $str),'-'));
    }

    static function url_to_string ($url) {
        return str_replace(array('-','_'), ' ', urldecode($url));
    }
   
   static function antispam_contact ($string) {
      $split = str_split($string);
      return "<script> document.write('" . implode("' + '", $split) . "'); </script>";
   }
   
   static function js_split_contact ($string) {
      $split = str_split($string);
      return implode("' + '", $split);
   }
   
   static function generate_random_alphanumeric_string ($length = 8) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
   }
   
   static function random_numbers ($length = 8) {
      $random = '';
      for($i = 0; $i < $length; $i++) {
         $random .= mt_rand(0, 9);
      }
      return $random;
   }
   
	
	final public function ArrayMultiScan($value, $array, $return){
		foreach($array as $section){
			if(@array_intersect_key($section, array_flip($value))){
				if($return == 'key'){
					return $value;
				}else{
					foreach($value as $chv){
						$rtr[$chv] = $array[$return][$chv];
					}
					return $rtr;
				}
			}elseif($int = @array_intersect($section, $value)){
				$chv = array_keys($int);
				if($return == 'key'){
					return $chv;
				}else{
					$rtr = array();
					foreach($chv as $key){
						if(isset($array[$return][$key])){
							$rtr[$key] = $array[$return][$key];
						}
					}
					return $rtr;
				}
			}elseif(@array_key_exists($value, $section)){
				if($return == 'key'){
					return $value;
				}else{
					return $array[$return][$value];
				}
			}elseif($chv = array_search($value, $section)){
				if($return=='key'){
					return $chv;
				}else{
					return $array[$return][$chv];
				}
			}
		}
		return false;
	}
	
	final public function ArraySimpleScan($value, $array, $return){
		$result = false;
		foreach($array as $section){
			if(@array_key_exists($value, $section)){
				$return == 'key'? $result = $value: $result = $array[$return][$value];
			}elseif($key = array_search($value, $section)){
				$return == 'key'? $result = $key: $result = $array[$return][$key];
			}
		}
		return $result;
	}
	
	final public function GetLink($string, $return){
		if($return == 'hrf'){
			strpos($string, 'http') === false? $string = 'http://'.trim($string):$string=trim($string);
		}elseif($return == 'text'){
			$exp = explode('/', str_replace(array('http://','www.','https://'), NULL, $string));
			$string = 'www.'.$exp[0].'...';
		}
		return $string;
	}
	
	final public function UnsetCookie($name){
		unset($_COOKIE[$name]);
		setcookie($name, NULL, time()-3600);
	}
}

