
<html>
<head><title>Message Board</title></head>
<body>
<?php
session_start();
if(!isset($_SESSION['usr_nam']))
{
  $_SESSION['usr_nam']="";
}

$usr ="";
$pwd ="";
$dbusr="";
$dbpass="";
try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=sec","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  print_r($dbh);
  if(!empty($_POST['username'])&&!empty($_POST['password']) )
  {
    $usr = $_POST['username'];
    $pwd = md5($_POST['password']);
  }
  $dbh->beginTransaction();
  
  (print_r($dbh->errorInfo(), true));
  $dbh->commit();

  $stmt = $dbh->prepare('select * from admin WHERE username = :auser AND password =:apwd');
  $stmt->bindParam(':apwd',$pwd);
  $stmt->bindParam(':auser',$_SESSION['usr_name']);
  $stmt->execute();
  print "<pre>";
  while ($row = $stmt->fetch()) {
    $dbusr = $row['username'];
    $_SESSION['usr_nam'] = $row['username'];
    $dbpass = $row['password'];
  }
  if( ($dbusr == $usr) && ($dbpass == $pwd))
  {
    print "Validated";
  }
  else
   {
    header("location: http://localhost/Secure/login.php");
    exit; 
  }
  print "</pre>";
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}

?>
<div class = "all_posts">
All posts:
<br>
<?php
  $stmt = $dbh->prepare("select * from post");
  $stmt->execute();
  while($row = $stmt->fetch())
  { $did = $row['postid'];
    echo "Created by: ",$row['created_by'];
    echo "&nbsp &nbsp";
    echo $row['time_date'];
    echo "&nbsp &nbsp";
    //header('Content-type:image/jpeg');

    echo"File:  ", $row['picture'];
    //echo '<img src="data:image/jpeg;base64,'.base64_encode($row['picture']).'"/>';
    echo "&nbsp &nbsp";
    echo "Description:  ",$row['description'];
    echo "&nbsp &nbsp";
    echo "<a href = 'board.php?deleteid=$did'>Delete</a>"; 
    echo"<br>";
  }

?>
<?php
if(isset($_GET["deleteid"]))
  {
    $delete_id = $_GET["deleteid"];
    $stmt = $dbh->prepare('delete from post where postid = :del');
    $stmt->bindParam(':del',$delete_id);
    $stmt->execute();
}
?>
<button type = "submit" formaction="http://localhost/Secure/login.php" style =" position:fixed;   right:10px;   top:5px;">Logout</button>


</body>
</html>
