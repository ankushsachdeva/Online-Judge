<?php
session_start();
include('settings.php');
$cn = mysql_connect('localhost', $DBUSER, $DBPASS);
mysql_select_db($DBNAME, $cn);

if(isset($_POST['comment']) && isset($_POST['problemID'])){
	$query="select * from problems where problemID=".$_POST['problemID'];
	$res = mysql_query($query);
	$res = mysql_fetch_array($res);
	if($res==NULL) echo "<meta http-equiv='Refresh' content='0; URL=problem.php'/>";

	else{

		$str = htmlentities($_POST['comment']);
		$file = "comments/problem/".$_POST['problemID'].".php";
		$fp = fopen($file,'a');
		if($fp == NULL) $fp=fopen($file,'w') or die("can't open file");;

		$rand = rand(1,1000000000);

		$str = "\n".$str."\n";
		fwrite($fp,'');
		fwrite($fp,"<code class='statement'>");
		$curr_date = date('m/d/Y h:i:s a', time());

		$temp = "<strong>".$_SESSION['username']."</strong><br />".$curr_date."<br />";
		fwrite($fp,$temp);
		fwrite($fp,$str);

		fwrite($fp,"</code>\n");

		fclose($fp);

		echo "<meta http-equiv='Refresh' content='0; URL=problem.php?problemID=".$_POST['problemID']."'/>";

	}
}
else{

	if(!isset($_GET['problemID'])) 
		echo"<meta http-equiv='Refresh' content='0; URL=problem.php'/>";
	else{
		echo"<form method='POST' action='comment.php'>";
		echo"Comment:</br>";
		echo"<textarea name='comment' cols='65' rows='7'></textarea>";
		echo"<input name='problemID' type='hidden' value=".$_GET['problemID']." />";
		echo"<input name='submit' type='submit' class='button' value='submit' />";
		echo"</form>";

	}

}
?>
