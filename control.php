<html>
<head><title>Message Board</title></head>
<body>
<?php
session_start();
if(!isset($_SESSION['usr_name']))
{
  $_SESSION['usr_name']="";
}
$usr ="";
$pwd ="";
$dbusr="";
$dbpass="";

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=sec","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  if(!empty($_POST['username']) && !empty($_POST['password']) )
  {
    $usr = $_POST['username'];
    $pwd = md5($_POST['password']);
  }

  $stmt = $dbh->prepare('select * from users WHERE username ="' .$usr.  '"  AND password ="'.$pwd.'"');
  $stmt->execute();
  print "<pre>";
  while ($row = $stmt->fetch()) {
    $dbusr = $row['username'];
    $_SESSION['usr_name'] = $row['username'];
    $dbpass = $row['password'];
  }
  if( ($dbusr == $usr) && ($dbpass == $pwd))
  {
    print "Validated";
    echo "<br> Welcome  ",$_SESSION['usr_name'];
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
<form action ="" method = "POST">
<div class = "file_insertion">
Insert File: <input type = "file" name = "input_file">
<br><br>
Description: <input type ="text"  name = "des"  style="width: 250px;height: 150px;" >
<br>
<button type = "submit">Upload</button> 
</div>
<?php
	$id = uniqid();
	if(isset($_POST['input_file'])  && !empty($_POST['input_file']))
	{
			$file = $_POST['input_file'];
			if(isset($_POST['des']))
			$description = $_POST['des'];
			$stmt = $dbh->prepare('Insert into post values (:id,now(),:usr,:file,:descp)');
			$stmt->bindParam(':id',$id);
		    $stmt->bindParam(':usr',$_SESSION['usr_name']);
		    $stmt->bindParam(':file',$file);
		    $stmt->bindParam(':descp',$description);
		  $stmt->execute();
	}	  
?>
<br><br><br>
<div class = "all_posts">
All posts:
<br>
<?php
	$stmt = $dbh->prepare("select * from post");
	$stmt->execute();
	while($row = $stmt->fetch())
	{	$did = $row['postid'];
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
		if ($_SESSION['usr_name'] == $row['created_by'])
		echo "<a href = 'control.php?deleteid=$did'>Delete</a>"; 
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
</div>
<button type = "submit" formaction="http://localhost/Secure/login.php" style =" position:fixed;   right:10px;   top:5px;">Logout</button>

</form>
</body>
</html>