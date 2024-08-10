<!DOCTYPE html> 
<html lang='en'> 
	<head> 
		<link rel="stylesheet" href="index.css">
		<title>Add New Invoice</title>
	</head> 

	<a href="index.php" target="MainClicked"><img class="img2" src="assets/main_ui_element10.png"/></a>
</html> 
 
<?php 
	echo '<img src="assets/main_ui_element10.png" onclick="MainClicked()" width="100" height="100"/>';

	if(array_key_exists('MainClicked', $_POST)) { 
		MainClicked(); 
	}
	function MainClicked(){
		echo 'button 1 is selected';
	}
?> 
