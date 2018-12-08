<html>
<head><title>New User insertion</title></head>
<body>
<?php

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=sec","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  if(!empty($_POST['user_name'])&&!empty($_POST['pass_word']) )
  {
    $usr = $_POST['user_name'];
    $pwd = md5($_POST['pass_word']);
    $name = $_POST['full_name'];
  }
  $dbh->beginTransaction();
  
  $dbh->exec('insert into users values("'.$usr.'","' . $name. '","'.$pwd.'")');
  $dbh->commit();
  header("location: http://localhost/Secure/login.php");
 
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
?>
</body>
</html>
