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
<body class="problem">
<div id="wrap">	
<script type="text/javascript" src="countdown.js"></script>		
<?php include('header.php');

$conn= mysql_connect('localhost',$DBUSER,$DBPASS);
mysql_select_db($DBNAME,$conn);?>

<div id="content-outer" class="clear">

<div id="left">						
<div class="entry">

<?php
if(!isset($_GET['problemID']))
{
	if(!isset($_GET['page']))$page=1;
	else $page=intval($_GET['page']);
	echo'<table border="1" width="100%" >
		<tr><th>Contest</th>
		<th>Problem</th><th>Total Submissions</th><th>Accepted Submissions</th>
		</tr>';   
	$page=($page-1)*10;
	$query = "SELECT * FROM problems WHERE problemID > ".$page.";";

	$logged = mysql_query($query);
	if(mysql_num_rows($logged)==0)echo '<meta http-equiv="REFRESH" content="0;url=problem.php">';
	$i=0;
	while($r= mysql_fetch_array($logged))
	{
	    $query = "SELECT * FROM contests WHERE contestID = ".$r['contestID'].";";
    	$res = mysql_query($query);
    	$res= mysql_fetch_array($res);
    	$ctime = new DateTime(date('Y-m-d H:i:s'));
    	if($res['startTime'] > date('Y-m-d H:i:s')) continue; 
		echo "	
			<tr ".($i%2==0?"class= 'altrow'":"")."><td><a href='contests.php?contestID=".$res['contestID']."'>".$res['Name']."</td>
			<td><a href ='problem.php?problemID=".$r['problemID']."'> ".$r['problemName']."</a></td>	<td >".$r['submissions']."</td> <td >".$r['accepted']."</td>
			</tr>";
		$i+=1;

	}   
	echo "</table>";
}


else
{
	if(is_numeric($_GET['problemID'])!=1)
	{
		echo "Invalid problemID";
		echo '<meta http-equiv="REFRESH" content="1;url=problem.php">';
		exit(0);
	}
	$query = "SELECT * from problems where problemID=".$_GET['problemID'];
	$logged=mysql_query($query);
	if((mysql_num_rows($logged) == 0))
	{
		echo "Invalid ProblemID";
		echo '<meta http-equiv="REFRESH" content="2;url=problem.php">';
		exit(0);
	}
	$r=mysql_fetch_array($logged);
	$query = "SELECT * FROM contests WHERE contestID = ".$r['contestID'].";";
    $res = mysql_query($query);
   	$res= mysql_fetch_array($res);
   	$res= mysql_fetch_array($res);
        $d1 = new DateTime($res['startTime']);
        $current = new DateTime(date('Y-m-d H:i:s'));
if($d1>$current){  
   	    echo "<meta http-equiv='Refresh' content='4; URL=problem.php' />	";
   	    exit(0);
   	}
	echo"<h2>".$r['problemName']."</h2><p id='statementpanel'><code class='statement'>";
	echo $r['statement'];
	echo "</p></code>
		<p id='statementpanel'><code class='statement'>
		<b>Time Limit:".$r['timeLimit']." seconds </b></br>
		<b>Memory Limit:".$r['memoryLimit']." MB </b></br>
		</p></code>
		";
	if($_SESSION['isloggedin']==1){

		echo '<form action="insert.php" method="post"
			enctype="multipart/form-data">
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file" />
			<select name="language">
			<option value="c">C</option>
			<option value="cpp">C++</option>
			<option value="py">Python</option>
			</select>
			<input type="hidden" value='.$_GET['problemID'].' name="problemID">
			<input class="button" type="submit" name="submit" value="Submit" />
			</form>'; 

		echo'<div class="entry"><h4>Comments</h4><p>';
		include('comments/problem/'.$_GET['problemID'].".php");
		include('comment.php');
		echo'</p></div>';

	}
	else{
		echo "<b>Please login to submit</b>";
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
