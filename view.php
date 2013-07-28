
<?php
session_start();
include('settings.php');
?>

<?php
$cn = mysql_connect('localhost', $DBUSER, $DBPASS);
mysql_select_db($DBNAME, $cn);

function TERMINATE() { echo "<meta http-equiv='Refresh' content='0; URL=status.php' />"; exit(0); }

if(!isset($_GET['submID']) or !is_numeric($_GET['submID'])) TERMINATE();
else{
	$query = "select * from submissions where submID=".$_GET['submID'];
	$res = mysql_query($query);
	$res = mysql_fetch_array($res);
	if($res==NULL) TERMINATE();
	else{
		$lang = $res['submlang'];
		$problemID = $res['problemID'];
		$submID = $res['submID'];
		$user_id=$res['userID'];
	}
	$query = "select visible from problems where problemID=".$problemID;
	$res = mysql_query($query);
	$res = mysql_fetch_array($res);
	if($_SESSION['userid']!=$user_id and !($res['visible']) ) TERMINATE();

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Online Judge</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<script type="text/javascript" src="syn_scripts/XRegExp.js"></script>
<script type="text/javascript" src="syn_scripts/shCore.js"></script>

<link type="text/css" rel="stylesheet" href="syn_styles/shCore.css"/>
<link type="text/css" rel="Stylesheet" href="syn_styles/shThemeDefault.css" />
<?php
if($lang=="C" || $lang=="C++"){
	echo '<script type="text/javascript" src="syn_scripts/shBrushCpp.js"></script>';
	if($lang == "C") $ext = "c";
	if($lang == "C++") $ext = "cpp";
}
else if($lang=="python"){
	echo '<script type="text/javascript" src="syn_scripts/shBrushPython.js"></script>';
	$ext = "py";
}
else if($lang=="unnown"){
	echo 'Unknown Language';
}
?>
<script type="text/javascript">SyntaxHighlighter.all();</script>

<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<script type="text/javascript" src="jquery.js"></script>

<style type="text/css">
code{display:inline  !IMPORTANT;}
</style>

</head>
<body>
<!-- wrap starts here -->
<div id="wrap">			
<?php include('header.php'); ?>
<div id="content-outer" class="clear">

<div id="left">						
<div class="entry" style="display:inline;">
<?php
echo'<script type="syntaxhighlighter" class="brush:'.$ext.';"><![CDATA[';
include('submissions/'.$user_id.'/'.$submID.'.'.$ext);
echo']]></script>';
//echo 'submissions/'.$problemID.'/'.$submID.'.'.$ext;
?>

</div>
</div>

<?php include("sidebar.php"); ?>
<?php include("footer.php"); ?>	
</div>
</div>

</body>
</html>
