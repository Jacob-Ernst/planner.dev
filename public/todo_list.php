<?PHP
	var_dump($_POST);
	var_dump($_GET);
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Todo list</title>
</head>
<body>
	<h1>TODO List</h1>
	<ol>
		<li>Be awesome</li>
		<li>Keep being awesome</li>
		<li>Win at life</li>
	</ol>
	<form method="POST">
		<p><h2>New Items</h2>
			<label><textarea id="added_items" name="added_items" placeholder="add item here"></textarea>Add Item</label></p>
		<input type="submit">
	</form>
	

</body>
</html>