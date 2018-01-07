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

class model extends PDO {
   
    protected $sth;
    
    public $language;
      
    function __construct() {
        
        parent::__construct($GLOBALS['PROJECT']['DATABASE']['TYPE'] . ':host=' . $GLOBALS['PROJECT']['DATABASE']['HOST'] . ';dbname=' . $GLOBALS['PROJECT']['DATABASE']['NAME'], $GLOBALS['PROJECT']['DATABASE']['USER'], $GLOBALS['PROJECT']['DATABASE']['PASSWORD']);
        
        $this->language = $this->selectnoview('SELECT id FROM languages WHERE sign = :sign', ['sign' => \leslie::$lang]);
        
    }
   
    function sel ($sql, $fetchMode = PDO::FETCH_ASSOC) {
        
        $this->sth = $this->prepare($sql);
        $this->sth->execute() or $this->error();
        return $this->sth->fetchAll($fetchMode);
        
    }
    
    function selone ($sql, $fetchMode = PDO::FETCH_ASSOC) {
        
        $this->sth = $this->prepare($sql);
        $this->sth->execute() or $this->error();
        return $this->sth->fetch($fetchMode);
        
    }

    function select ($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        
        $this->sth = $this->prepare($sql);
        if(!empty($array)){
          foreach ($array as $key => $value) {
              $this->sth->bindValue("$key", $value);
          }
        }
        //echo $sql;
        $this->sth->execute() or $this->error();
        return $this->sth->fetchAll($fetchMode);
        
    }
    
    function selectnoview ($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        
        $this->sth = $this->prepare($sql);
        if(!empty($array)){
            
          foreach ($array as $key => $value) {
              $this->sth->bindValue("$key", $value);
          }
          
        }
        $this->sth->execute() or $this->error();
        return $this->sth->fetch($fetchMode);
        
    }
    
    function describe ($table) {
        
        $q = $this->prepare("DESCRIBE " . $table);
        $q->execute();
        $r = $q->fetchAll(PDO::FETCH_COLUMN);
        return array_map(create_function('$n', 'return null;'), array_flip($r));
        
    }

    function cell ($table, $column, $where) {
       $this->sth = $this->query('SELECT `' . $column . '` FROM `' . $table . '` WHERE ' . $where . ' LIMIT 0, 1');
       return $this->sth->fetchColumn();

    }
    
    function insert($table, $data) {
       ksort($data);

       $fieldNames = implode('`, `', array_keys($data));
       $fieldValues = ':' . implode(', :', array_keys($data));

       $this->sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

       foreach ($data as $key => $value) {
           $this->sth->bindValue(":$key", $value);
       }

       return $this->sth->execute() or $this->error();
    }

    function update ($table, $data, $where) {
        ksort($data);
        $fieldDetails = NULL;
        foreach($data as $key=> $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $this->sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        foreach ($data as $key => $value) {
            $this->sth->bindValue(":$key", $value);
        }

        return $this->sth->execute() or $this->error();
    }

    function delete ($table, $where, $limit = 0) {

       if($limit>0){
          $sql = "DELETE FROM $table WHERE $where LIMIT $limit";
       } else {
          $sql = "DELETE FROM $table WHERE $where";
       }
       $this->sth = $this->prepare($sql);
       return $this->sth->execute() or $this->error();
    }

    private function error() {
       \leslie::$logs['database']['error'] = $this->sth->errorInfo()[2];
       \leslie::$logs['database']['query'] = $this->sth->queryString;
       \leslie::log(\leslie::$logs['database']['error']);
       if ($GLOBALS['PROJECT']['DEBUG']) {
          die();
       }
    }
   
}
