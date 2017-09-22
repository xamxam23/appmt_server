<?php

$cmd = isset($_POST["cmd"])? $_POST["cmd"] : (isset($_GET["cmd"])? $_GET["cmd"]: "");

function get($key){
    return isset($_POST[$key])? $_POST[$key] : (isset($_GET[$key])? $_GET[$key] : null);
}

$arr=null;
if ($cmd == "code"){
    $language=get("language");
    $source=get("source");
    unlink("Test.class");
    file_put_contents('Test.class', '');
    $fp = fopen("Test.java", "w");
    fwrite($fp, $source);
    fclose($fp);
    echo exec('"C:\Program Files\Java\jdk1.7.0_79\bin\javac" Test.java > testc.txt 2>&1');
    echo exec('"C:\Program Files\Java\jdk1.7.0_79\bin\java" Test > testr.txt 2>&1');
    $msg = file_get_contents("testc.txt", true)."\n\n".file_get_contents("testr.txt", true);
    $arr = array("message"=>$msg, "ok"=>true, "source"=>"source code", "lang"=>"language");
} else if ($cmd == "updatecv"){
    require_once("db.php"); //$con
    $user = get("user");
    $cv = get("cv");
    
	$result = mysqli_query($con, "UPDATE users set cv='$cv' WHERE user='$user' ");
    
    $arr = array("message"=>"cv of $user successfully updated", "ok"=>true, "result"=>$result);
    mysqli_close($con);
} else if ($cmd == "getusers"){
    require_once("db.php"); //$con
    $data = array();
    $result = mysqli_query($con, "SELECT * FROM users");
    while($row=mysqli_fetch_array($result, MYSQL_ASSOC)){
	   $data[] = $row;
    }
    $arr = array("message"=>"get users", "ok"=>true, "data"=>$data); 
    mysqli_close($con);
} else if ($cmd == "register"){
    require_once("db.php");
    
    $name=get("name");
    $user=get("user");
    $pass=get("pass");
    
	$sql = "INSERT INTO users (name, user, pass) VALUES ('$name', '$user', '$pass')";
	$result = mysqli_query($con, $sql);
	$id = mysqli_insert_id($con);
	mysqli_close($con);
    
    $arr = array("message"=>"success insert id ".$id, "ok"=>($id>0));
} else if ($cmd == "login") {
    require("register.php");
    $user=get("user");
    $pass=get("pass");
    $ok = login($user, $pass);
         
    $arr = array("message"=>"login", "ok"=>$ok);
} else {
   $arr = array("message"=>"unknown command", "ok"=>false);
}
echo json_encode($arr);
?> 