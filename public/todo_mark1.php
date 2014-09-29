<?php
require_once '../addresses_db_connector.php';
require_once '../inc/filestore.php';
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;


     
if (isset($_GET['remove_key'])) {
    $remover = $dbc->prepare("DELETE FROM todo_list WHERE id = :id");
    $remover->bindValue(':id', $_GET['remove_key'], PDO::PARAM_INT);
    $remover->execute();
}

if (count($_FILES) > 0 && $_FILES['file']['error'] == UPLOAD_ERR_OK && $_FILES['file']['type'] == 'text/plain') {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file']['tmp_name'], $saved_filename);
    
    $external = new Filestore($saved_filename);
    $external_list = $external->read();
    
    $upload = $dbc->prepare('INSERT INTO todo_list (contents) VALUES (:contents)');

    foreach ($external_list as $key => $value) {
        $upload->bindValue(':contents', $value, PDO::PARAM_STR);
        $upload->execute();   
    }
     
}


$count = $dbc->query('SELECT count(*) FROM todo_list');
$number = $count->fetchColumn();

$valid = FALSE;
if (isset($_POST['contents'])) {
    $valid = TRUE;
}

if($valid) {
    $stmt = $dbc->prepare('INSERT INTO todo_list (contents) VALUES (:contents)');
    $stmt->bindValue(':contents', $_POST['contents'], PDO::PARAM_STR);
    $stmt->execute();
    $_POST = array();
}
$stmt = $dbc->prepare("SELECT id, contents FROM todo_list LIMIT :num_of OFFSET :offset");
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
    <title>TODO mockup</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class='container'>
        <div class="jumbotron">
            <h1>TODO LIST</h1>
        </div>
    </div>
    
    <div class='container'>
        <h2>Do these things</h2>
    </div>

    <div class='container'>
        <ol start="<?=$offset+1?>">
            <?php foreach ($items as $key => $value):?>
                <li><?=$value['contents']?><a href='?remove_key=<?=$value['id']?>' class='btn'>Mark Complete!</a></li>
            <?php endforeach;?>
        </ol>
    </div>
    
    <ul class="pager">
        <?php if($offset != 0):?>
            <li class="previous"><a href="?offset=<?=$offset-4?>" class='btn'>Previous</a></li>
        <?php endif;?>
        <?php if($offset + 4 < $number):?>
            <li class="next"><a href="?offset=<?=$offset + 4?>" class='btn'>Next</a></li>
        <?php endif;?>
    </ul>
    
    <?php  if (isset($saved_filename)):?>
         <p>You can download your file <a href='/uploads/<?=$filename?>'>here</a>.</p>
    <?php endif; ?>
    
    <div class='container'>
    <form role="form" method="POST" action='todo_mark1.php'>
      <div class="form-group">
        <label for="contents">contents</label>
        <textarea name="contents" id='contents' class='form-control' placeholder='content'><?= (isset($_POST['name'])) ? $_POST['name'] : '';?></textarea>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
    </div>
    <div class='container'>
        <form method="POST" enctype="multipart/form-data">
        <p>
            <label for="file">File to upload: </label>
            <input type="file" id="file" name="file">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
    
    </div>
</body>
</html>
