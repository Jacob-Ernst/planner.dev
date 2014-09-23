<?php

 class Filestore {

     protected $filename = '';
     protected $is_csv = FALSE ;

     public function __construct($filename = '')
     {
         $this->filename = $filename;
         if (substr($filename, -3) == 'csv') {
             $this->is_csv = TRUE;
         }
     }

     /**
      * Returns array of lines in $this->filename
      */
     protected function read_lines() {
        $handle = fopen($this->filename, "r");
        $contents = trim(fread($handle, filesize($this->filename)));
        $contents_array = explode("\n", $contents);
        fclose($handle);
        return $contents_array;
     }

     /**
      * Writes each element in $array to a new line in $this->filename
      */
     
     protected function write_lines($array) {
        $handle = fopen($this->filename, "w");
        foreach ($array as $value) {
        fwrite($handle, $value . PHP_EOL);
        }
        fclose($handle);
     }

     /**
      * Reads contents of csv $this->filename, returns an array
      */
     protected function read_csv(){
        $handle = fopen($this->filename, 'r');
        
        $array = [];
        while (!feof($handle)) {
            $row = fgetcsv($handle);
            if (!empty($row)) {
                $array[] = $row;
            }
        }
        fclose($handle);
        return $array;
    }

     /**
      * Writes contents of $array to csv $this->filename
      */
     protected function write_csv($array){
        $handle = fopen($this->filename, 'w');
            foreach ($array as $fields) {
                fputcsv($handle, $fields);
            }
            fclose($handle);
    }
    
    public function read(){
        if ($this->is_csv == TRUE) {
           return $this->read_csv();
        }
        else {
            return $this->read_lines();
        }
    }
    
    public function write($array){
        if ($this->is_csv == TRUE) {
            $this->write_csv($array);
        }
        else {
            $this->write_lines($array);
        }
    }
    
 }
