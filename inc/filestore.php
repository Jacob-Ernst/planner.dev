<?php

 class Filestore {

     public $filename = '';

     function __construct($filename = '')
     {
         $this->filename = $filename;
     }

     /**
      * Returns array of lines in $this->filename
      */
     function read_lines() {
        $handle = fopen($this->filename, "r");
        $contents = trim(fread($handle, filesize($this->filename)));
        $contents_array = explode("\n", $contents);
        fclose($handle);
        return $contents_array;
     }

     /**
      * Writes each element in $array to a new line in $this->filename
      */
     
     function write_lines($array) {
        $handle = fopen($this->filename, "w");
        foreach ($array as $value) {
        fwrite($handle, $value . PHP_EOL);
        }
        fclose($handle);
     }

     /**
      * Reads contents of csv $this->filename, returns an array
      */
     function read_csv(){
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
     function write_csv($array){
        $handle = fopen($this->filename, 'w');
            foreach ($array as $fields) {
                fputcsv($handle, $fields);
            }
            fclose($handle);
    }

 }
