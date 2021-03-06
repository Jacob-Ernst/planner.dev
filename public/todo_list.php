<?PHP
define('LISTITEMS', 'data/list.txt');

require_once '../inc/filestore.php';

$list_store = new Filestore(LISTITEMS);

class InvalidInputException extends Exception{
    
}
  
$items = $list_store->read(); 

if (isset($_POST['added_items'])) {
    try {
        if (empty($_POST['added_items'])){
            throw new InvalidInputException('Values must be provided for all fields');
        }
        if (strlen($_POST['added_items']) > 240 ) {
            throw new InvalidInputException('Item is longer than 240 characters');
        }
        
        $items[] = htmlspecialchars(strip_tags($_POST['added_items']));
        $list_store->write($items);
    } catch (InvalidInputException $e) {
        $errorMessage = $e->getMessage();
    }
} 

if (isset($_GET['remove_key'])) {
	unset($items[$_GET['remove_key']]);
	$list_store->write($items);
} 
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
    $upload_items = $list_store->read("uploads/" . $filename);
    $items = array_merge($items, $upload_items);
    $list_store->write($items);
}
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Todo list</title>
	<link rel="stylesheet" href="/css/todo.css">
</head>
<body>
	<h1>TODO List</h1>
    <?php if (isset($errorMessage)):?>
    <h2><?=$errorMessage?></h2>
    <? endif;?>
	<ol>
		<?PHP  if (isset($saved_filename)):?>
		    <p>You can download your file <a href='/uploads/<?=$filename?>'>here</a>.</p>
		<?PHP endif; ?>
		<?PHP foreach ($items as $key => $value):?>
				<li><?=$value?><a href='todo_list.php?remove_key=<?=$key?>'>Mark Complete!</a></li>
		<?PHP endforeach;?>
	</ol>
	<form method="POST">
		<p><h2>New Items</h2>
			<label for="added_items"><textarea id="added_items" name="added_items" placeholder="add item here"></textarea>Add Item</label></p>
		<input type="submit">
	</form>
	<form method="POST" enctype="multipart/form-data">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
	<a href=""></a>
</body>
</html>
