<?PHP
	define('LISTITEMS', 'data/list.txt');
	function read_file($filename) {
	    $handle = fopen($filename, "r");
	    $contents = trim(fread($handle, filesize($filename)));
	    $contents_array = explode("\n", $contents);
	    fclose($handle);
	    return $contents_array;
 	}
 	
	function write_file($filename, $array) {
	    $handle = fopen($filename, "w");
        foreach ($array as $value) {
            fwrite($handle, $value . PHP_EOL);
        }
        fclose($handle);
    }
	
	
	
 	$items = read_file(LISTITEMS); 
	
	if (isset($_POST['added_items'])) {
		$items[] = $_POST['added_items'];
		write_file(LISTITEMS, $items);
	} 
	
	if (isset($_GET['remove_key'])) {
		unset($items[$_GET['remove_key']]);
		write_file(LISTITEMS, $items);
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
        $upload_items = read_file("uploads/" . $filename);
        $items = array_merge($items, $upload_items);
        write_file(LISTITEMS, $items);
    }
    
    var_dump($_FILES);
    
    // Check if we saved a file
	var_dump($_POST);
	var_dump($_GET);
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
