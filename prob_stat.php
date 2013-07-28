<html>
<head>
<title>Online Jugde</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/FreshPick.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
</head>

<?php

include("settings.php");
session_start(); 
if(strcmp($_SESSION['username'],'admin'))exit(0);
if($_GET['flag']==1)
{
	$cname = $_POST['Name'];
	$sTime = $_POST['startTime'];
	$eTime = $_POST['endTime'];

	$conn = mysql_connect('localhost',$DBUSER,$DBPASS);
	mysql_select_db($DBNAME,$conn);
	$query = "insert into contests (Name, startTime, endTime) values ('$cname', '$sTime', '$eTime')";
	$logged = mysql_query($query);
	$query="select last_insert_id() AS id";
	$logged = mysql_query($query);
	$r = mysql_fetch_array($logged);
	$newcontestid=$r['id'];
	$query="create table contest_".$newcontestid." (userID int primary key auto_increment,username varchar(50) NOT NULL,score int default 0,penalty int default 0) ";
	mysql_query($query);
	mysql_close($conn);

}

if(isset($_POST['statement']))
{

	$cname=  $_GET['Name'];
	$pname = $_POST['prob_name'];
	$stat = $_POST['statement'];
	$points = $_POST['points'];
	$time = $_POST['time'];
	$memory = $_POST['memory'];
	//$pid = $_GET['i'];
	$conn = mysql_connect('localhost',$DBUSER,$DBPASS);
	mysql_select_db($DBNAME,$conn);
	$sql = "select * from contests where Name='$cname'";
	$r = mysql_query($sql);
	$r1 = mysql_fetch_array($r);
	$r = $r1['contestID'];

	$query="insert into problems (contestID, problemName, statement, score,timeLimit,memoryLimit) values ($r, '$pname', '$stat', $points, $time, $memory)";

	mysql_query($query);
	$query="select last_insert_id() AS id";
	$logged = mysql_query($query);
	$logg = mysql_fetch_array($logged);
	$query="ALTER TABLE `contest_$r` ADD p".$logg['id']." INT DEFAULT 0;";
	mysql_query($query);
	mysql_close($conn);
	$curdir=getcwd();
	mkdir($curdir."/problems/".$logg['id']);
	$in = $curdir."/problems/".$logg['id']."/in";
	$fin = fopen($in,'w');
	$data = $_POST['in'];
	fwrite($fin,$data);
	$out = $curdir."/problems/".$logg['id']."/out";
	$fout = fopen($out,'w');
	$data = $_POST['out'];
	fwrite($fout,$data);
}
else
{
	$i=0;
	$cname = $_POST['Name'];
}
//$i=$pid+1;
if($_POST['Finish'])
{
	echo "<h1>Contest created successfully</h1>";
	echo "<h4>Go to the <a href='contests.php'>Contests</a> page to view the contest</h4>";
} 
else
{
	echo "<h1> Enter problems for $cname</h1>";
	echo"
		<br><br>
		<form action='prob_stat.php?flag=0&Name=$cname' method='post'>
		Problem Name : <input type='text' name='prob_name'>
		<br>
		Problem Statement :<br><textarea name='statement' cols='50' rows='20'></textarea>
		<br>
		Points : <input type='number' name='points'>
		<br>
		Time Limit(in seconds) : <input type='number' name='time'>
		<br>
		Memory Limit(in MB) : <input type='number' name='memory'>
		<br>
		Input test case :<br><textarea name='in' cols='30' rows='10'></textarea><br>
		Output for the above test case :<br><textarea name='out' cols='30' rows='10'></textarea>
		<br>
		<br>
		<input type='submit' name='Submit' value='Submit'>
		<input type='submit' name='Finish' value='Finish'>
		</form>
		";


}
include("footer.php");
?>
