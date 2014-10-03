<?php
 
require_once('Ad.class.php');
 
class AdManager {
 
    public $dataFile = "";
 
    public function __construct($dataFile = "ads.csv")
    {
        $this->dataFile = $dataFile;
    }
 
    public function loadAds()
    {
        $handle = fopen($this->dataFile, 'r');
 
        $ads = [];
 
        while(!feof($handle))
        {
            $csvRow = fgetcsv($handle);
 
            // make sure csv row had data
            if (!empty($csvRow[0]))
            {                
                $ad = new Ad($csvRow[0], $csvRow[1], $csvRow[2], $csvRow[3], $csvRow[4]);
                $ads[] = $ad;
            }
 
        }
 
        fclose($handle);
 
        return $ads;
    }
 
    public function saveAds($ads)
    {
        $handle = fopen($this->dataFile, 'w');
 
        foreach ($ads as $ad) {
            fputcsv($handle, $ad->toArray());
        }
 
        fclose($handle);
    }
}
