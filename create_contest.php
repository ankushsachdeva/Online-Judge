<html>
<head> 
<title>Online Judge</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/FreshPick.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<br>
<div id="wrap">
<?php
session_start(); 
if(strcmp($_SESSION['username'],'admin'))exit(0); 
include("header.php");
?>
<br>
<h2>Create Contest </h2>
</head>

<form  action="prob_stat.php?flag=1" method="post">

Name of the Contest : <input type="text" name="Name" >
<br>
Start Time : <input type="text" name="startTime" > <i>yyyy-mm-dd hh:mm:ss (eg- 2012-05-24 21:01:00)</i>
<br>
End Time : <input type="text" name="endTime" > <i>yyyy-mm-dd hh:mm:ss</i>
<br>
<input type="submit" value="Submit">
</form>

<?php include("footer.php"); ?>
</html>
