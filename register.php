<?php
function register($name, $user, $pass){// return id
	require_once("db.php");
	$sql = "INSERT INTO users (name, user, pass) VALUES ('$name', '$user', '$pass')"; // default 100
	$result = mysqli_query($con, $sql);
	
	$id = mysqli_insert_id($con);
	
	mysqli_close($con);

	return $id;
}

function login($user, $pass){// return id
	require_once("db.php");
	$sql = "SELECT * FROM users WHERE user='$user' AND pass='$pass' "; // default 100
	$result = mysqli_query($con, $sql);
    
    $ok = ($result->num_rows > 0)? true: false;
	
	mysqli_close($con);

	return $ok;
}
?>