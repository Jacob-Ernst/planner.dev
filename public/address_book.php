<?php

define('FILENAME', 'data/address_book.csv');
require_once '../inc/filestore.php';

class AddressDataStore extends Filestore {

     function __construct($filename){
       parent::__construct(strtolower($filename));
     }
     
     // function read_address_book()
     // {
     //     // TODO: refactor to use new $this->read_csv() method
     //    return $this->read_csv();
     // }

     // function write_address_book($addresses_array)
     // {
     //     // TODO: refactor to use new write_csv() method
     //    $this->write_csv($addresses_array);
     // }

 }


$address_book = [];


$address_table = new AddressDataStore(FILENAME);
$address_book = $address_table->read();

function format_phone($value){
    if(!empty($value)){
        $output_phone = '(' . substr($value, 0 , 3 ) . ')' . '-' . substr($value, 3 , 3 ) . '-' . substr($value, 6 , 4 );
    }
    else{
        $output_phone = '';
    }
    return $output_phone;
}
     
if (
    !empty($_POST) &&
    !empty($_POST['name']) &&
    !empty($_POST['address']) &&
    !empty($_POST['city']) &&
    !empty($_POST['state']) &&
    !empty($_POST['zip'])
) {
    foreach ($_POST as $key => $value) {
        if (strlen($value) > 125) {
            throw new Exception('A string under 125 characters is required for ' . $key);
        }
    }

    $usable_phone = preg_replace('/\D*(\d{3})\D*(\d{3})\D*(\d{4})/', '$1$2$3', $_POST['phone']);
    $new_values = [
        $_POST['name'],
        $_POST['address'], 
        $_POST['city'],
        $_POST['state'],
        $_POST['zip'],
        $usable_phone
    ];
    $address_book[] = $new_values;
    $address_table->write($address_book);
}
if (isset($_GET['remove_key'])) {
        unset($address_book[$_GET['remove_key']]);
        $address_table->write($address_book);
}
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
        // Set the destination directory for uploads
        $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
        // Grab the filename from the uploaded file by using basename
        $filename = basename($_FILES['file1']['name']);
        // Create the saved filename using the file's original name and our upload directory
        $saved_filename = $upload_dir . $filename;
        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
        
        $newAds = new AddressDataStore($saved_filename);
        
        $upload_items = $newAds->read();
        $address_book = array_merge($address_book, $upload_items);
        $address_table->write($address_book);
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Address Book</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class='container'>
        <div class="jumbotron">
            <h1>Addresses</h1>
        </div>
    </div>
    
    <div class="container">
        <table class="table table-striped">
            <tr>
                <th>name</th>
                <th>address</th>
                <th>city</th>
                <th>state</th>
                <th>zip</th>
                <th>phone</th>
                <th>Remove</th>
            </tr>
            
            <?php foreach ($address_book as $key => $address_array):?>
                <tr>
                    <?php foreach ($address_array as $num => $value):?>
                        <?php 
                            if ($num == 5){
                               $value = format_phone($value);
                            }
                        ?>
                        <td><?=$value?></td>
                    <?php endforeach;?>
                    <?php if (count($address_array) == 5 ):?>
                            <td></td>
                    <?php endif;?>
                    <td><a href="?remove_key=<?=$key?>">Remove</a></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <div class='container'>
    <form method="POST" action='address_book.php'>
        <h2>New addresses</h2>
        <p><label for="name"></label>
        <input type="text" name="name" id="name" placeholder="name"></p>
        <p><label for='address'></label>
        <input type="text" name="address" id='address' placeholder="address"></p>
        <p><label for='city'></label>
        <input type="text" name="city" id='city' placeholder="city"></p>
        <p><label for='state'></label>
        <input type="text" name="state" id='state' placeholder="state"></p>
        <p><label for='zip'></label>
        <input type="text" name="zip" id='zip' placeholder="zip"></p>
        <p><label for='phone'></label>
        <input type="text" name="phone" id='phone' placeholder="phone"></p>
        <input type="submit" value="Add">    
    </form>
    </div>
    <div class='container'>
    <form method="POST" enctype="multipart/form-data">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
    </div>
</body>
</html>
