<?php
class AddressDataStore {

     public $filename = '';
     
     function __construct($filename = FILENAME)
    {
        $this->filename = $filename;
    }
     
     
     function read_address_book() {
        $handle = fopen($this->filename, 'r');
        
        $address_book = [];
        while (!feof($handle)) {
            $row = fgetcsv($handle);
            if (!empty($row)) {
                $address_book[] = $row;
            }
        }
        
        fclose($handle);
        return $address_book;
    }

    function write_address_book($array) {
            $handle = fopen($this->filename, 'w');
            foreach ($array as $fields) {
                fputcsv($handle, $fields);
            }
            fclose($handle);
    }
    
 }



?>
