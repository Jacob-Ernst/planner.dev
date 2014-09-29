<?php
require_once '../addresses_db_connector.php';
require_once '../inc/filestore.php';


$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;


function format_phone($value){
    if(!empty($value)){
        $output_phone = '(' . substr($value, 0 , 3 ) . ')' . '-' . substr($value, 3 , 3 ) . '-' . substr($value, 6 , 4 );
    }
    else{
        $output_phone = '';
    }
    return $output_phone;
}
     
     
if (isset($_GET['remove_key'])) {
    $remover = $dbc->prepare("DELETE FROM names WHERE name_id = :id");
    $remover->bindValue(':id', $_GET['remove_key'], PDO::PARAM_INT);
    $remover->execute();
}


$count = $dbc->query('SELECT count(*) FROM names');
$number = $count->fetchColumn();


$valid = FALSE;
$formatted_phone = '';

try {
    if (
        !empty($_POST['name']) &&
        !empty($_POST['address']) &&
        !empty($_POST['city']) &&
        !empty($_POST['state']) &&
        !empty($_POST['zip']) &&
        !empty($_POST['phone']) 
    ) 
    {
        $valid = TRUE;
        $formatted_phone = preg_replace('/\D*(\d{3})\D*(\d{3})\D*(\d{4})/', '$1$2$3', $_POST['phone']);
    }
    else {
        throw new Exception("Please fill in all fields");
    }
} catch (Exception $e) {
    $errorMessage = 'Please fill in all fields';
}


try {
    if (isset($_POST['name']) && strlen($_POST['name']) > 125) {
        throw new Exception("Name must be no longer than 125 characters long");
    }
} catch (Exception $e) {
    $name_error = "Name must be no longer than 125 characters long";
    $valid = FALSE;
}

try {
    if (isset($_POST['address']) && strlen($_POST['address']) > 125) {
        throw new Exception("Address must be no longer than 125 characters long");
    }
} catch (Exception $e) {
    $address_error = "Address must be no longer than 125 characters long";
    $valid = FALSE;
}

try {
    if (isset($_POST['city']) && strlen($_POST['city']) > 125) {
        throw new Exception("City must be no longer than 125 characters long");
    }
} catch (Exception $e) {
    $city_error = "City must be no longer than 125 characters long";
    $valid = FALSE;
}

try {
    if (isset($_POST['state']) && strlen($_POST['state']) != 2) {
        throw new Exception("State must fit the two letter abbreviation");
    }
} catch (Exception $e) {
    $state_error = "State must fit the two letter abbreviation";
    $valid = FALSE;
}

try {
    if (isset($_POST['zip']) && strlen($_POST['zip']) != 5) {
        throw new Exception("Zip code must be no longer than 5 characters");
    }
} catch (Exception $e) {
    $zip_error = "Zip code must 5 characters";
    $valid = FALSE;
}

try {
    if (isset($_POST['phone']) && strlen($_POST['phone']) != 10) {
        throw new Exception("Phone must be no longer than 10 characters");
    }
} catch (Exception $e) {
    $phone_error = "Phone must be 10 characters";
    $valid = FALSE;
}

if($valid) {
    $stmt1 = $dbc->prepare('INSERT INTO names (name, phone) VALUES (:name, :phone)');
    $stmt1->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
    $stmt1->bindValue(':phone', $formatted_phone, PDO::PARAM_STR);    
    $stmt1->execute();
    $name_id = $dbc->lastInsertId();
    
    try {
        $stmt2 = $dbc->prepare('INSERT INTO addresses (address, city, state, zip) VALUES (:address, :city, :state, :zip)');
        $stmt2->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
        $stmt2->bindValue(':city', $_POST['city'], PDO::PARAM_STR); 
        $stmt2->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
        $stmt2->bindValue(':zip', $_POST['zip'], PDO::PARAM_STR);   
        $stmt2->execute();
        $address_id = $dbc->lastInsertId();
    } catch (Exception $e) {
        $stmt2 = $dbc->prepare("SELECT address_id FROM addresses WHERE address = :address AND city = :city AND state = :state AND zip = :zip ");
        $stmt2->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
        $stmt2->bindValue(':city', $_POST['city'], PDO::PARAM_STR); 
        $stmt2->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
        $stmt2->bindValue(':zip', $_POST['zip'], PDO::PARAM_STR); 
        $address_id = $stmt2->execute();
    }
   
    $stmt3 = $dbc->prepare("INSERT INTO addresses_names (name_id, address_id) VALUES (:name_id, :address_id)");
    $stmt3->bindValue(':name_id', $name_id, PDO::PARAM_INT);
    $stmt3->bindValue(':address_id', $address_id, PDO::PARAM_INT);  
    $stmt3->execute();  
}


$stmt = $dbc->prepare
(
    "SELECT     names.name, addresses.address, addresses.city, addresses.state, addresses.zip, names.phone, names.name_id 
     FROM       names 
     LEFT JOIN  addresses_names 
     ON         names.name_id              = addresses_names.name_id
     INNER JOIN addresses
     ON         addresses_names.address_id = addresses.address_id
     ORDER BY   names.name ASC
     LIMIT      :num_of 
     OFFSET     :offset"
);

$stmt->bindValue(':num_of', 4, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADDRESSES mockup</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class='container'>
        <div class="jumbotron">
            <h1>ADDRESS BOOK</h1>
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
            <?php foreach ($items as $key => $address_array):?>
                <tr>
                    <?php foreach ($address_array as $num => $value):?>
                            <?php if ($num == 'phone'):?>
                                <?php $filtered_value = format_phone($value);?>
                                <td><?=$filtered_value?></td>
                            <?php elseif ($num == 'name_id'):?> 
                                  <?php  $remove_id = $value ?>                     
                            <?php else:?>
                                <td><?=$value?></td>
                            <?php endif;?>
                    <?php endforeach;?>
                    <td><a href="?remove_key=<?=$remove_id?>">Remove</a></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    
     <ul class="pager">
        <?php if($offset != 0):?>
            <li class="previous"><a href="?offset=<?=$offset-4?>" class='btn'>Previous</a></li>
        <?php endif;?>
        <?php if($offset + 4 < $number):?>
            <li class="next"><a href="?offset=<?=$offset + 4?>" class='btn'>Next</a></li>
        <?php endif;?>
    </ul>
    <?php if (
        isset($errorMessage) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$errorMessage?></h2>
        </div>
    <?php endif;?>
    
    <?php if (
        isset($name_error) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$name_error?></h2>
        </div>
    <?php endif;?>
    
    <?php if (
        isset($address_error) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$address_error?></h2>
        </div>
    <?php endif;?>
    
    <?php if (
        isset($city_error) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$city_error?></h2>
        </div>
    <?php endif;?>
    
    <?php if (
        isset($state_error) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$state_error?></h2>
        </div>
    <?php endif;?>
    
    <?php if (
        isset($zip_error) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$zip_error?></h2>
        </div>
    <?php endif;?>
    
    <?php if (
        isset($phone_error) &&
        isset($_POST['name']) &&
        isset($_POST['address']) &&
        isset($_POST['city']) &&
        isset($_POST['state']) &&
        isset($_POST['zip']) &&
        isset($_POST['phone']) 
    ):?>
        <div class='containder'>
            <h2><?=$phone_error?></h2>
        </div>
    <?php endif;?>
    
    <div class='container'>
    <form method="POST" action='address_book_mark1.php'>
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
</body>
</html>
