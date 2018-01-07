
<?php

require_once FRAMEWORK_PATH_CORE . DIRECTORY_SEPARATOR . 'view.php';

class WviewDatabase extends Wview {
   
   public function setOutput(){
      $this->title = "WDatabase";
       if($this->model->checkDatabase()){
          $this->output .= '<h2>' . $this->model->db_name . '</h2>' . "\r\n";
          foreach ($this->model->tables as $table => $fields) {
             $this->output .= "<table class=\"table table-striped table-bordered table-condensed\">\r\n";
             $this->output .= "<caption>" . $table . "</caption>\r\n";
             $this->output .= "<thead>\r\n";
             $this->output .= "<tr><th>field name</th><th>field type</th></tr>\r\n";
             $this->output .= "</thead>\r\n";
             $this->output .= "<tbody>\r\n";
             foreach ($fields as $name => $type) {
                
                $this->output .= "<tr><td>" . $name . "</td><td>" . $type . "</td></tr>\r\n";
             }
             $this->output .= "</tbody>";
             $this->output .= "</table>";
             
          }
          
       }else{
          $this->output .= '<p>Database Assente: <a href="?action=init" class="btn btn-primary btn-sm">crea il database</a>.</p>' . "\r\n";
          
       }
      
   }
   
}

