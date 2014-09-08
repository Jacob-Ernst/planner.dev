<?PHP
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
		<?PHP
			$items = ['Be awesome', 'Keep being awesome', 'Win at life'];
			foreach ($items as $key => $value) {
				echo "<li>$value</li>";
			}
		?>
	</ol>
	<form method="POST">
		<p><h2>New Items</h2>
			<label for="added_items"><textarea id="added_items" name="added_items" placeholder="add item here"></textarea>Add Item</label></p>
		<input type="submit">
	</form>
	

</body>
</html>
