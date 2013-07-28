
<?php
session_start();
include('settings.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Online Judge</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<script type="text/javascript" src="jquery.js"></script>
</head>

<body class="status">
<!-- wrap starts here -->
<div id="wrap">			
<?php include('header.php'); ?>

<div id="content-outer" class="clear">
<div id="left">						
<div class="entry">

<?php

$cn=mysql_connect(localhost,$DBUSER,$DBPASS) or die(mysql_error());
mysql_select_db($DBNAME,$cn);

$query = "select count(*) from submissions";
$logged = mysql_query($query);
$logg = mysql_fetch_array($logged);
$count = $logg['count(*)'];

if(isset($_GET['page'])) $page = intval($_GET['page']);
else $page = 1;

$count1 = (int)($count/10)+1;
if($count1<$_GET['page']) $page=1;

echo" <h3>Recent Submission</h3>";
echo "<table border='0' width='100%'";
echo"<tr class='altrow'>";

$status = array("Accepted","Compilation-Error","Wrong-Answer","TLE","Illegal-File","Runtime-error","Waiting" );

for($i=-3;$i<4;$i++){
	$temp=$page+$i;
	echo("<td>");
	if($i==0) echo $temp;
	else if($temp > 0 && $temp <= $count/10+1)
		echo "<a href=status.php?page=".$temp.">".$temp."</a>";
	echo"</td>";
}
echo "</tr></table>";

$query = "select * from `submissions` where submID>".($count-($page)*10)." and submID<=".($count-($page-1)*10)." order by submID desc";
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
</div>

<?php include("sidebar.php"); ?>

</div>
</div>
</body>
<?php include("footer.php"); ?>	
</html>
