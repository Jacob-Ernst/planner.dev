<?php

define('FILENAME', 'data/address_book.csv');


$address_book = [
    ['The White House', '1600 Pennsylvania Avenue NW', 'Washington', 'DC', '20500', '63456'],
    ['Marvel Comics', 'P.O. Box 1527', 'Long Island City', 'NY', '11101', '56464'],
    ['LucasArts', 'P.O. Box 29901', 'San Francisco', 'CA', '94129-0901', '290438409'],
];
// if (isset($_POST['added_items'])) {
//         $items[] = htmlspecialchars(strip_tags($_POST['added_items']));
//         write_file(LISTITEMS, $items);
//     }
// foreach ($address_book as $fields) {
//     fputcsv($handle, $fields);
// }
function write_file($filename, $array) {
        $handle = fopen($filename, 'w');
        foreach ($array as $fields) {
            fputcsv($handle, $fields);
        }
        fclose($filename);
}
        
if (!empty($_POST)) {
    var_dump($_POST);
    $new_values = [
        $_POST['name1'],
        $_POST['address1'], 
        $_POST['city1'],
        $_POST['state1'],
        $_POST['zip1'],
        $_POST['phone1']
    ];
    $address_book[] = $new_values;
    write_file(FILENAME, $address_book);
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
            </tr>
            
            <?PHP foreach ($address_book as $key => $address_array):?>
                    <tr><?php foreach ($address_array as $key => $value):?>
                            <td><?=$value?></td>
                        <?php endforeach;?></tr>
            <?PHP endforeach;?>
        </table>
    </div>
    
    <form method="POST">
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
</body>
</html>
