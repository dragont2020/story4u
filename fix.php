<?php
define('__ZEN_KEY_ACCESS', 'DragonT');
include 'systems/includes/config/ZenDB.php';
$conn = new mysqli(__ZEN_DB_HOST, __ZEN_DB_USER, __ZEN_DB_PASSWORD, __ZEN_DB_NAME) or die("Error! Can't connect to mysql");
$conn->query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
if(isset($_POST['p'])){
	$s = $_POST['s'];
	$e = $_POST['e'];
	$p = $_POST['p'];
	$t = $_POST['t'];
	if(is_string($p)){
		for($a = $s; $a <= $e; $a++){
		$conn->query("UPDATE zen_cms_blogs SET $t = '$p' WHERE id=$a") or die('At '.$a.'<br>');
		echo "$a ($t => $p)<br>";
		}
	}else{
		for($a = $s; $a <= $e; $a++){
		$conn->query("UPDATE zen_cms_blogs SET $t = $p WHERE id=$a") or die('At '.$a.'<br>');
		echo "$a ($t => $p)<br>";
	}
	}
	
	
}else{
	echo'<form method="post">
		<label>Start:</label><input type="text" name="s" /><br>
		<label>End:</label><input type="text" name="e" /><br>
		<label>value:</label><input type="text" name="p" /><br>
		<label>Type:</label><input type="text" value="parent" name="t" /><br>
		<input type="submit" value="Ok" />
	</form>';
}


?>