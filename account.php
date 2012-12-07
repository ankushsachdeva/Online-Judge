<?php
session_start();
include('settings.php');
if($_SESSION['isloggedin']!=1)exit(0);
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
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript">
<!---
     $(document).ready(function(){
             
            $('.password').click(function(){
                     $('#pd').hide();
                     
                     $('#pdedit').show();
                     $('.password').attr('value','save'); 
                      $('.username').attr('class','saveclass');
                 });
            $('.firstname').click(function(){
                     $('#fn').hide();
                     
                     $('#fnedit').show();
                     $('.firstname').attr('value','save'); 
                      $('.username').attr('class','saveclass');
                 });
            $('.lastname').click(function(){
                     $('#ln').hide();
                     
                     $('#lnedit').show();
                     $('.lastname').attr('value','save'); 
                      $('.username').attr('class','saveclass');
                 });
             $('.college').click(function(){
                     $('#c').hide();
                     
                     $('#cedit').show();
                     $('.college').attr('value','save'); 
                      $('.username').attr('class','saveclass');
                 });
            $('.saveclass').click(function(){
                    
                
                   });
         });

-->
</script>		
	<?php include('header.php');
	        
	$conn= mysql_connect($DB_IP,$DBUSER,$DBPASS);
    mysql_select_db($DBNAME,$conn);
        
    ?>
	
	<div id="content-outer" class="clear">
		
			<div id="left">						
				<div class="entry">
	                <table border='1' width='100%' >
		            
	<?php           
	                echo"
	                <tr>
	                <td width= 30% ><b>Username</td>
	                <td><div id='un'>".$_SESSION['username']."</div><div id='unedit' style='display:none;'><input name='newun' type='text' /></div></td>
	                <td width=10%></td>
		            </tr>
		            <tr>
	                <td width= 30% ><b>Password</td>
	                <td><div id='pd'>******</div><div id='pdedit' style='display:none;'><input name='newpd' type='password' /><input name='cnewpd' type='password'  /></div></td>
	                <td width=10%><input class='password' name='pd' type='submit' value='edit' size='26%'></td>
		            </tr>          
                    <tr>
	                <td width= 30% ><b>Firstname</td>
	                <td><div id='fn'>".$_SESSION['firstname']."</div><div id='fnedit' style='display:none;'><input name='newfn' type='text' /></div></td>
	                <td width=10%><input class='firstname' name='fn' type='submit' value='edit' size='26%'></td>
		            </tr>
		            <tr>
	                <td width= 30% ><b>Lastname</td>
	                <td><div id='ln'>".$_SESSION['lastname']."</div><div id='lnedit' style='display:none;'><input name='newln' type='text' /></div></td>
	                <td width=10%><input class='lastname' name='ln' type='submit' value='edit' size='26%'></td>
		            </tr>
		            <tr>
	                <td width= 30% ><b>College</td>
	                <td><div id='c'>".$_SESSION['college']."</div><div id='cedit' style='display:none;'><input name='newc' type='text' /></div></td>
	                <td width=10%><input class='college' name='c' type='submit' value='edit' size='26%'></td>
		            </tr>
		            </table>
		            ";	         
	  ?>          
	            
                
	            </div>
			</div>
			<?php include("sidebar.php"); ?>
		
	<?php include("footer.php"); ?>	
    </div>
</div>

</body>
