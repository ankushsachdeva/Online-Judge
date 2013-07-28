<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<title>Online Judge</title>

<meta name="keywords" content="keywords, here" />
<meta name="robots" content="index, follow, noarchive" />
<meta name="googlebot" content="noarchive" />

<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />

</head>

<body class="index">

<!-- wrap starts here -->
<div id="wrap">
<?php include('settings.php'); ?>		
<?php include('header.php'); ?>

<!-- featured starts -->	
<!-- content -->
<div id="content-outer" class="clear"><div id="content-wrap">
<div id="content">
<div id="left">
<h3><strong>Recent Blog Entries</strong></h3>

<?php

//<script src="http://l.yimg.com/a/i/us/pps/mapbadge_1.5.js">{"pipe_id":"1b24d69045fbb81d728903f9054452f9","_btype":"map","pipe_params":{"JobTitle":"mechanic","location":"San Francisco, CA","Distance":"50"}}</script>


$cn = mysql_connect('localhost', $DBUSER, $DBPASS);
mysql_select_db($DBNAME, $cn);

$query = "select count(*) from blog";
$logged = mysql_query($query);
$logg = mysql_fetch_array($logged);
$count = $logg['count(*)'];

if($count>=5) $query = "select *from blog where blogID<=".$count."and blogID>".$count."-5";
else $query = "select * from blog";

$logged = mysql_query($query);
while($logg = mysql_fetch_array($logged)){
	echo"<div class='entry'>";
	echo"<h4>".$logg['blogName']."</h4>";
	$fread = fopen("blog/desc/".$logg['blogID'].".php","r");
	if($fread==NULL) echo"<p>No Blog description</p>";
	else include("blog/desc/".$logg['blogID'].".php");
	echo"<p><a class='more-link' href=blog.php?blogID=".$logg['blogID'].">Continue Reading</a></p>";
	echo"</div>";
}

?>			
</div>
<?php include("sidebar.php"); ?>
<!-- footer starts here -->	

<!-- wrap ends here -->
</div>

</body>
<?php include("footer.php"); ?>
</html>
