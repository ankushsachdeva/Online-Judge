
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
<body class="about">
  <!-- wrap starts here -->
  <div id="wrap">			
    <?php include('header.php'); ?>
    <div id="content-outer" class="clear">
      
      <div id="left">						
        <div class="entry">
       		Online Judge developed as a part of <b><a href="http://www.pclub.in" target="_blank">Programming Club</a></b> IITK Summer project 2012.</br>
		By:<ul><li><a href="mailto:vijaykes@iitk.ac.in">Vijay Keswani</a></li><li><a href="mailto:sankush@iitk.ac.in">Ankush Sachdeva</a> </li><li><a href="mailto:irfanh@iitk.ac.in">Irfan Hudda</a></li></ul>
Click <a href="http://pclub.in/wiki/index.php/Onlinejudge" target="_blank"><b>here</b></a> for Wiki.

This Online Judge uses
<ul>
<li><a href="http://jquery.com/">Jquery</a></li>
<li><a href="http://www.styleshout.com/">styleshout:CSS</a></li>
</ul>
For further information<a href="http://www.pclub.in/index.php/contact-us">Contacts</a>
        </div>
      </div>
      
      <?php include("sidebar.php"); ?>
      	
      </div>
  </div>

</body>
<?php include("footer.php"); ?>
</html>
