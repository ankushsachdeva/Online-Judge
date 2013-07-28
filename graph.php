<?php
//Print_r ($_SESSION);
include("settings.php");
$conn=mysql_connect('localhost',$DBUSER,$DBPASS);
$username=$_SESSION['username'];
mysql_select_db($DBNAME,$conn);

$php = "[['Contest', 'Score']";
$score=0;
$i=0;
$j=1;
$query="SELECT count(*) as c from contests";

$logged=mysql_query($query);
$r1=mysql_fetch_array($logged);
$contestcount=$r1['c'];
for($j=1;$j<=$contestcount;$j+=1)
{   $query="SELECT score from contest_$j where userID=".$newuserid;

	$logged=mysql_query($query);
	$r1=mysql_fetch_array($logged);

	if(!$r1)continue;
	$score=$r1['score'];
	$query="SELECT Name from contests where contestID=$j";
	$logged=mysql_query($query);
	$r2=mysql_fetch_array($logged);
	$named=$r2['Name'];
	$php.= ",['$named',$score]";

}

$php.="]";
echo $php;

?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {


	var data = google.visualization.arrayToDataTable(<?php echo $php; ?>);

	var options = {
title: 'Your Performance [Score vs Contest]'

	};

	var chart = new google.visualization.LineChart(document.getElementById('graph1'));
	chart.draw(data, options);
}
</script>

