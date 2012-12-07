
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
<body class="rank">
<!-- wrap starts here -->
<div id="wrap">			
<?php include('header.php'); ?>
<div id="content-outer" class="clear">

<div id="left">						
<div class="entry">
<?php
if(!isset($_GET['contestID'])){
	echo '
		<h2>Overall Rankings</h2>
		<table border="1" width="100%" >
		<tr>
		<th>Rank</th><th>Username</th><th>Score</th>
		</tr>';

	$cn = mysql_connect($DB_IP, $DBUSER, $DBPASS);
	mysql_select_db($DBNAME, $cn);
	$query = "select username,score from `users` order by score DESC , penalty ASC";
	$logged = mysql_query($query);

	for($i=0;$logg = mysql_fetch_array($logged);$i++){

		echo "	
			<tr ".($i%2==0?"class= 'altrow'":"").">
			<td > ".($i+1)."</td>	<td ><a href='profile.php?username=".$logg['username']."'>".$logg['username']."</a></td>
			<td>".$logg['score']."</td>
			</tr>";
	}
	mysql_close($cn);
}
else{   
	$contestID=intval($_GET['contestID']);
	$cn = mysql_connect($DB_IP, $DBUSER, $DBPASS);
	mysql_select_db($DBNAME, $cn);
	$query = "select Name from `contests` where contestID=$contestID ";
	$logged = mysql_query($query);
	$logg = mysql_fetch_array($logged);
	if(isset($logg['Name'])){
		echo '
			<h2>Rankings for '.$logg['Name'].'</h2>
			<table border="1" width="100%" >
			<tr>
			<th>Rank</th><th>Username</th><th>Score</th>
			</tr>';
	}
	else{
		echo 'Not available';
	}


	$query = "select username,score from `contest_$contestID` order by score DESC ,penalty ASC ";
	$logged = mysql_query($query);
	for($i=0;$logg = mysql_fetch_array($logged);$i++){

		echo "	
			<tr ".($i%2==0?"class= 'altrow'":"").">
			<td > ".($i+1)."</td>	<td ><a href='profile.php?username=".$logg['username']."'>".$logg['username']."</a></td>
			<td>".$logg['score']."</td>
			</tr>";
	}
	mysql_close($cn);
}

?>
</table>
</div>
</div>

<?php include("sidebar.php"); ?>

</div>
</div>

</body>
<?php include("footer.php"); ?>
</html>
