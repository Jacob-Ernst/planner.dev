<?php
require_once '../db_connector.php';
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

$stmt = $dbc->prepare("SELECT name, location, area_in_acres, date_established, description FROM national_parks LIMIT :num_of OFFSET :offset");
$stmt->bindValue(':num_of', 4, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$parks = $stmt->fetchALL(PDO::FETCH_ASSOC);

$count = $dbc->query('SELECT count(*) FROM national_parks');
$number = $count->fetchColumn();

$valid = FALSE;
if (isset($_POST)) {
    foreach ($_POST as $value ) {
        if (empty($value)) {
            $valid = FALSE;
            break;
        }
        else {
            $valid = TRUE;
        }
    }
}

if($valid) {
    $stmt = $dbc->prepare('INSERT INTO national_parks (name, location, area_in_acres, date_established, description) VALUES (:name, :location, :area, :date_established, :description)');
    $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
    $stmt->bindValue(':location', $_POST['location'], PDO::PARAM_STR);
    $stmt->bindValue(':area', $_POST['area'], PDO::PARAM_STR);
    $stmt->bindValue(':date_established', $_POST['date'], PDO::PARAM_STR);
    $stmt->bindValue(':description', $_POST['descr'], PDO::PARAM_STR);
    $stmt->execute();
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Da PARKS YO</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class='container'>
        <div class="jumbotron">
            <h1>Nature Places</h1>
        </div>
    </div>
    <div class='container'>
        <table class='table'>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Area in Acres</th>
                <th>Date Established</th>
                <th>Description</th>
            </tr>
            <?php foreach ($parks as $parkInfo):?>
                <tr>
                        <td>
                            <?=$parkInfo['name']?>
                        </td>
                        <td>
                            <?=$parkInfo['location']?>
                        </td>
                        <td>
                            <?=number_format($parkInfo['area_in_acres'], 2)?>
                        </td>
                        <td>
                            <?php $date = new DateTime($parkInfo['date_established']);
                                 echo $date->format('l, j F Y');?>
                        </td>
                        <td>
                            <?= $parkInfo['description']?>
                        
                        </td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <div class='container'>
    <ul class="pager">
        <?php if($offset != 0):?>
            <li class="previous"><a href="?offset=<?=$offset-4?>" class='btn'>Previous</a></li>
        <?php endif;?>
        <?php if($offset + 4 < $number):?>
            <li class="next"><a href="?offset=<?=$offset + 4?>" class='btn'>Next</a></li>
        <?php endif;?>
    </ul>
    </div>
    <div class='container'>
    <form role="form" method="POST" action='national_parks.php'>
      <div class="form-group">
        <label for="name">Park</label>
        <input type="text" class="form-control" name='name' id="name" placeholder="name">
      </div>
      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control" name='location' id="location" placeholder="location">
      </div>
      <div class="form-group">
        <label for="area">Area</label>
        <input type="text" class="form-control" name='area' id="area" placeholder="area">
      </div>
      <div class="form-group">
        <label for="date">Date</label>
        <input type="text" class="form-control" name='date' id="date" placeholder="date">
      </div>
      <div class="form-group">
        <label for="descr">Description</label>
        <textarea name="descr" id='descr' class='form-control' placeholder='description'></textarea>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
    </div>
</body>
</html>

