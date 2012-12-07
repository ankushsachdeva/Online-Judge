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
</head>
<body class="contest">
<div id="wrap">	
<script type="text/javascript" src="countdown.js">
</script>		
<?php include('header.php');
$conn= mysql_connect($DB_IP,$DBUSER,$DBPASS);
mysql_select_db($DBNAME,$conn);
?>

<div id="content-outer" class="clear">

<div id="left">						
<div class="entry">

<?php

if(!isset($_GET['contestID'])){
	$query = "SELECT DATE_FORMAT(`startTime`,'%Y-%m-%d %T') AS startTime,DATE_FORMAT(`endTime`,'%Y-%m-%d %T') AS endTime,contestID,Name FROM contests ORDER BY endTime DESC ";
	$logged = mysql_query($query);
	$temp="<table width=100%><tr><th width=30%>Name</th><th width=35%>Start Time</th><th>End Time</th></tr>";
	$fut="<h2>Future Contests</h2>";
	$cur="<h2>Running Contests</h2>";
	$past="<h2>Past Contests</h2>";

	$fut=$fut.$temp;
	$cur=$cur.$temp;
	$past=$past.$temp;

	$countf=0;$countp=0;$countc=0;
	while($r= mysql_fetch_array($logged))
	{
		$d1 = new DateTime($r['startTime']);
		$current = new DateTime(date('Y-m-d H:i:s'));//yyyy-mm-dd hh:mm:ss
		if($d1>$current){
			$fut=$fut."<tr><td><a href='contests.php?contestID=".$r['contestID']."'>".$r['Name']."</a></td><td>".date('jS F Y \a\t G:i', strtotime($r['startTime']))."</td><td>".date('jS F Y \a\t G:i', strtotime($r['endTime']))."</td></tr>";
			$countf=1;
			continue;
		}
		$d1 = new DateTime($r['endTime']); 
		if($d1>$current){
			//date_diff($d1, $current)->format('%a days %H hrs %i minutes');
			$cur=$cur."<tr><td><a href='contests.php?contestID=".$r['contestID']."'>".$r['Name']."</a><a href='rank.php?contestID=".$r['contestID']."' style='color: #00F000;font-size: .9em;'></br>(Rankings)</a></td><td>".date('jS F Y \a\t G:i', strtotime($r['startTime']))."</td><td>".date('jS F Y \a\t G:i', strtotime($r['endTime']))."</td></tr>";
			$countc=1;
			continue;
		}
		else{
			$past=$past."<tr><td><a href='contests.php?contestID=".$r['contestID']."'>".$r['Name']."</a>
				<a href='rank.php?contestID=".$r['contestID']."' style='color: #00F000;font-size: .9em;'></br>(Rankings)</a></td><td>".date('jS F Y \a\t G:i', strtotime($r['startTime']))."</td><td>".date('jS F Y \a\t G:i', strtotime($r['endTime']))."</td></tr>";
			$countp=1;
			break;
		}    
	}
	$temp="</table>";
	$fut=$fut.$temp;
	$cur=$cur.$temp;

	if($countf!=1)$fut.="No future contest scheduled";   
	if($countc!=1)$cur.="No contest running";        

	echo $fut;
	echo $cur;

	if($countp!=1){

		$past=$past.$temp."No past contests</p></code>";
		echo $past;
	}
	else
	{
		echo $past;
		while($r= mysql_fetch_array($logged))
		{
			echo "<tr><td><a href='contests.php?contestID=".$r['contestID']."'>".$r['Name']."</a><a href='rank.php?contestID=".$r['contestID']."' style='color: #00F000;font-size: .9em;'></br>(Rankings)</a></td><td>".date('jS F Y \a\t G:i', strtotime($r['startTime']))."</td><td>".date('jS F Y \a\t G:i', strtotime($r['endTime']))."</td></tr>";
		}
	}
	echo "</table>";
}
else
{   

	if(is_numeric($_GET['contestID'])!=1)
	{
		echo "Invalid ContestID";
		echo '<meta http-equiv="REFRESH" content="2;url=contests.php">';
		exit(0);
	}
	$query = "SELECT DATE_FORMAT(`startTime`,'%Y-%m-%d %T') AS startTime,Name FROM contests WHERE contestID=".$_GET['contestID'];
	$logged=mysql_query($query);
	$r=mysql_fetch_array($logged);
	$d1 = new DateTime($r['startTime']); 
	$current = new DateTime(date('Y-m-d H:i:s'));
	if((mysql_num_rows($logged) == 0))
	{
		echo "Invalid ContestID";
		echo '<meta http-equiv="REFRESH" content="2;url=contests.php">';
		exit(0);
	}
	elseif($d1>$current)
	{
		echo "Contest not yet started";
	}
	else
	{
		echo "<h2>".$r['Name']."</h2>";
		$query="SELECT * from problems where contestID=".$_GET['contestID'];
		$logged=mysql_query($query);
		echo <<<EOHTML123
			<table border="1" width="100%" >
			<tr>
			<th>Problem</th><th>Total Submissions</th><th>Accepted Submissions</th>
			</tr>
EOHTML123;
		$i=0;
		while($r=mysql_fetch_array($logged))
		{
			echo "	
				<tr ".($i%2==0?"class= 'altrow'":"").">
				<td><a href ='problem.php?problemID=".$r['problemID']."'> ".$r['problemName']."</a></td>	<td >".$r['submissions']."</td> <td >".$r['accepted']."</td>
				</tr>";
			$i+=1;
		} 
		echo "</table>";
	}         	               
}


?>
</div>
</div>
<?php include("sidebar.php"); ?>


</div>
</div>
<?php include("footer.php"); ?>	
</body>
