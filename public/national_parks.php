<?php
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'codeup', 'codeuprocks');
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $dbc->query("SELECT name, location, area_in_acres, date_established FROM national_parks LIMIT 4 OFFSET $offset");
$parks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = $dbc->query('SELECT count(*) FROM national_parks');
$number = $count->fetchColumn();
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
                            <?=$parkInfo['area_in_acres']?>
                        </td>
                        <td>
                            <?=$parkInfo['date_established']?>
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
        <?php if($offset + 4 <= $number):?>
            <li class="next"><a href="?offset=<?=$offset + 4?>" class='btn'>Next</a></li>
        <?php endif;?>
    </ul>
    </div>
</body>
</html>

