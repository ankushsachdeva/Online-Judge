
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
<body class="blog">
<!-- wrap starts here -->
<div id="wrap">			
<?php include('header.php'); ?>
<div id="content-outer" class="clear">

<div id="left">						
<div class="entry">
<?php
$cn = mysql_connect($DB_IP, $DBUSER, $DBPASS);
mysql_select_db($DBNAME, $cn);

$query = "select count(*) from blog";
$logged = mysql_query($query);
$logg = mysql_fetch_array($logged);
$count = $logg['count(*)'];

if(isset($_GET['blogID'])) $current_blog=$_GET['blogID'];
else $current_blog=$count;
if($current_blog>1)
	echo "<a href='blog.php?blogID=".($current_blog-1)."'>Previous&nbsp;&nbsp;&nbsp;</a>";
if($current_blog<$count)
	echo "<a href='blog.php?blogID=".($current_blog+1)."'>&nbsp;&nbsp;&nbsp;&nbsp;Next </a>";


	if($current_blog > $count || $current_blog < 1){
		echo "<h4>Invalid Blog.<h4>";
	} 
else{
	$fwrite=fopen("blog/complete/".$current_blog.".php","r");

	$query="select blogName,time from blog where blogID=".$current_blog."";
	$logged = mysql_query($query);
	$logg = mysql_fetch_array($logged);
	echo "<h2><strong>".$logg['blogName']."</strong></h2>";
	if($fwrite==NULL) echo "<h4>Blog Not Found</h4>";
	else{
		echo"<p><h5>".date('jS F Y \a\t G:i', strtotime($logg['time']))."</h5></p>";
		include('blog/complete/'.$current_blog.".php");
	}
}
fclose($fwrite);
?>
</div>
</div>

<?php include("sidebar.php"); ?>
<?php include("footer.php"); ?>	
</div>
</div>

</body>
</html>
