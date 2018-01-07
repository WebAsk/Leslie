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

class image {
    
    private $types = array(1,2,3);
    public $path = NULL;
    private $file;
    public $quality = 100;
    public $x = 0;
    public $y = 0;
    public $folder;
    public $width;
    public $height;
    private $resource;
   
    function __construct($file) {
        
        if(!is_uploaded_file($file)){
            \leslie::$alerts[] = 'il server non è riuscito a caricare correttamente l\'immagine, riprova';
            return;
        }
        
        $this->file = $file;
        list($this->width, $this->height, $this->type) = getimagesize($this->file);
        if(!in_array($this->type, $this->types)){
            \leslie::$alerts[] = 'estensione immagini accettate: .jpeg .png .gif';
            return;
        }
        
        $this->create_from();
        
    }

    final public function save ($folder, $name) {
      
        if(!is_dir($folder)){
            if (!mkdir($folder, 0775, true)) {
              die ('unable to create folder in specific path: ' . $folder);
           }
        }
        $this->name = $name;
        
        switch ($this->type) {
           case 1:
              imagegif($this->resource, $folder. DIRECTORY_SEPARATOR . $this->name);
              break;
           case 2:
              imagejpeg($this->resource, $folder. DIRECTORY_SEPARATOR . $this->name, $this->quality);
              break;
           case 3:
              imagepng($this->resource, $folder. DIRECTORY_SEPARATOR . $this->name);
              break;
           default:
              die('invalid file type');
        }
      
    }
   
    function create_from () {

        switch ($this->type) {
            case 1:
                $this->resource = @imagecreatefromgif($this->file);
                break;
            case 2:
                $this->resource = @imagecreatefromjpeg($this->file);
                break;
            case 3:
                $this->resource = @imagecreatefrompng($this->file);
                break;
            default:
                die('invalid file type');
        }

    }

    function crop ($x, $y, $w, $h) {
        
       $image = $this->create_true_color($w, $h);
       
       imagecopy($image, $this->resource, 0, 0, $x, $y, $w, $h);
       //$this->resource = imagecrop($this->resource, ['x' => intval($x), 'y' => intval($y), 'width' => intval($w), 'height' => intval($h)]);
       $this->resource = $image;
       $this->width = $w; $this->height = $h;
       
    }

    function resize ($width, $height) {

        if($this->width > $width || $this->height > $height){
           $ratio = min($width / $this->width, $height / $this->height);
           $height = floor($ratio * $this->height);
           $width = floor($ratio * $this->width);
        }else{
           $height = $this->height;
           $width = $this->width;
        }

        $image = $this->create_true_color($width, $height);

        imagecopyresampled($image, $this->resource, 0, 0, $this->x, $this->y, $width, $height, $this->width, $this->height);
        
        $this->width = $width;
        $this->height = $height;
        $this->resource = $image;

    }
    
    private function create_true_color ($width, $height) {
        
        $image = imagecreatetruecolor($width, $height);
        
        if ($this->type == 3) {
            
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $trasp = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefill($image, 0, 0, $trasp);
            
        } else {

            $white = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $white);

        }
        
        return $image;
        
    }
    
}