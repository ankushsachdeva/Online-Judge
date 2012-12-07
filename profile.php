
<?php
session_start();
include('settings.php');
$cn=mysqli_connect($DB_IP,$DBUSER,$DBPASS,$DBNAME) ;

if(!isset($_GET['username'])) $username = $_SESSION['username'];						
else $username = mysqli_real_escape_string($cn,$_GET['username']);

if(!isset($username)){
	echo"No username selected....Redirecting to homepage";
	echo'<meta HTTP-EQUIV="REFRESH" content="3; url=index.php">';
	exit(0);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<title>Online Judge </title>

<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<meta name="description" content="Site Description Here" />
<meta name="keywords" content="keywords, here" />
<meta name="robots" content="index, follow, noarchive" />
<meta name="googlebot" content="noarchive" />

<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />

</head>

<body>

<!-- wrap starts here -->
<div id="wrap">			
<?php include('header.php');
$cn=mysql_connect($DB_IP,$DBUSER,$DBPASS) or die(mysql_error());
mysql_select_db($DBNAME,$cn);
if(isset($_GET['username'])){
	$query = "select * from users where username='$username'";
	$logged = mysql_query($query);
	$logg = mysql_fetch_array($logged);
	$lastname=$logg['lastname'];
	$firstname=$logg['firstname'];
	$college=$logg['college'];
	$newuserid=$logg['userID'];
}
else{
	$lastname=$_SESSION['lastname'];
	$firstname=$_SESSION['firstname'];
	$college=$_SESSION['college'];
	$newuserid=$_SESSION['userid'];
}
?>


<div id="content-outer" class="clear">
<div id="left">
<div id="featured" class="clear">
<div class="image-block" ><img src='images/2.jpg' ></img></div>
<?php echo "<div class='text-block' ><p></br></br>".$firstname." ".$lastname."</br></p><p>".$college."</p></div>"; ?>
<!-- graph comes here -->
</div>						
<div class="entry">
<?php



$query = "select count(*) from submissions where userID='$newuserid'";
$logged = mysql_query($query);
$logg = mysql_fetch_array($logged);
$count = $logg['count(*)'];


if(isset($_GET['page'])) $page = intval($_GET['page']);
else $page = 1;
if($page>=$count/10+1)$page=1;


echo" <h3>Recent Submissions for $username</h3>";
echo "<table border='0' width='100%'";
echo"<tr class='altrow'>";

$status = array("Accepted","Compilation-Error","Wrong-Answer","TLE","Illegal-File","Runtime-error","Waiting" );

for($i=-3;$i<4;$i++){
	$temp=$page+$i;
	echo("<td>");
	if($i==0) echo $temp;
	else if($temp > 0 && $temp < $count/10+1)
		echo "<a href=profile.php?page=".$temp."&&username=$username>".$temp."</a>";
	echo"</td>";
}
echo "</tr></table>";

$query = "select * from `submissions` where username='$username' order by submID desc limit ".(($page-1)*10).",10";
$logged = mysql_query($query);
echo <<<EOHTML

<p>
<table border='1' width='100%' >
<tr>
<th>Username</th><th>Problem</th><th>Run Time</th><th>Language</th><th>Status</th>
</tr>
EOHTML;
for($i=0;$logg = mysql_fetch_array($logged);$i++){

	echo "	
		<tr class='".$status[$logg['status']]."'>
		<td><a href='profile.php?username=".$logg['username']."'>".$logg['username']."</a></td>
		<td><a href='problem.php?problemID=".$logg['problemID']."'>".$logg['problemName']."</a></td>
		<td> ".$logg['runtime']." sec</td>
		<td> ".$logg['submlang']."</td><td><a href='view.php?submID=".$logg['submID']."'> ".$status[$logg['status']]."</a></td>

		</tr>";
}
echo   "</table>";	     
mysql_close($cn);

?>
</div>
<div class="entry" id="graph1" style="width: auto; height: 500px;"><?php include('graph.php'); ?></div>
</div>

<?php include("sidebar.php"); ?>

</div>
</div>
</body>
<?php include("footer.php"); ?>	
</html>
