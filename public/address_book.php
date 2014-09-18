<?php

define('FILENAME', 'data/address_book.csv');

$address_book = [];

require_once 'class/address_data_store.php';

$address_table = new AddressDataStore();
$address_book = $address_table->read_address_book();

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
    !empty($_POST['name1']) &&
    !empty($_POST['address1']) &&
    !empty($_POST['city1']) &&
    !empty($_POST['state1']) &&
    !empty($_POST['zip1'])
) {
    $usable_phone = preg_replace('/\D*(\d{3})\D*(\d{3})\D*(\d{4})/', '$1$2$3', $_POST['phone1']);
    $new_values = [
        $_POST['name1'],
        $_POST['address1'], 
        $_POST['city1'],
        $_POST['state1'],
        $_POST['zip1'],
        $usable_phone
    ];
    $address_book[] = $new_values;
    $address_table->write_address_book($address_book);
}
if (isset($_GET['remove_key'])) {
        unset($address_book[$_GET['remove_key']]);
        $address_table->write_address_book($address_book);
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
        
        $upload_items = $newAds->read_address_book();
        $address_book = array_merge($address_book, $upload_items);
        $address_table->write_address_book($address_book);
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
        <table class="table">
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
        <p><label for="name1"></label>
        <input type="text" name="name1" id="name1" placeholder="name"></p>
        <p><label for='address1'></label>
        <input type="text" name="address1" id='address1' placeholder="address"></p>
        <p><label for='city1'></label>
        <input type="text" name="city1" id='city1' placeholder="city"></p>
        <p><label for='state1'></label>
        <input type="text" name="state1" id='state1' placeholder="state"></p>
        <p><label for='zip1'></label>
        <input type="text" name="zip1" id='zip1' placeholder="zip"></p>
        <p><label for='phone1'></label>
        <input type="text" name="phone1" id='phone1' placeholder="phone"></p>
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
