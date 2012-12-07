<div id="right">

<div class="sidemenu">
<?php
if($_POST['signup']){
	echo "<meta http-equiv='Refresh' content='0; URL=register.php'/>";
	exit(0);
}
session_start();
if(isset($_POST['username'])and $_SESSION['isloggedin']!=1){
	$cn = mysql_connect($DB_IP, $DBUSER, $DBPASS);
	mysql_select_db($DBNAME, $cn);
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$query = "select * from `users` where `username` = '".$username."' and `password` = '".$password."'";
	$logged = mysql_query($query);
	$logged = mysql_fetch_array($logged);
	mysql_close($cn);
	if($logged['userID']){

		$_SESSION['isloggedin'] = 1;
		$_SESSION['username'] = $username;		
		$_SESSION['password'] = $password;
		$_SESSION['userid'] = $logged['userID'];
		$_SESSION['firstname']=$logged['firstname'];
		$_SESSION['lastname']=$logged['lastname'];
		$_SESSION['college']=$logged['college'];
		echo "<meta http-equiv='Refresh' content='0;'/>";
		exit(0);
	}
	else
		echo"Invalid Username or Password";								
}

if($_SESSION['isloggedin']){
	echo "<div class='entry'><p align='center'><b>Welcome, ".$_SESSION['username']."</b></p>";
	echo "<p align='center' ><a href='profile.php'>My profile</a></p>";
	echo "<p align='center' ><a href='account.php'>Account Settings</a></p>";
	echo "<p align='center' ><a href='logout.php'>Sign Out</a></p>";
	if(!strcmp($_SESSION['username'],'admin'))echo "<p align='center' ><a href='create_contest.php'>Add Contest</a></p></div>";
}
else{
	echo'
		<form style="position: relative; margin-left: 0px; margin-right: 0px;  background-color: #FFFFFF;" value="refresh page" method="post">						
		<label>Username<br/></label>
		<input name="username" size="30%" type="text" /><br/>
		<label>Password<br/></label>
		<input name="password" size="30%" type="password" /></br>
		<div align="center"><input id="loginbutton" class="button" type="submit" name="login" value="Login" />
		<input id="loginbutton" class="button" type="submit" name="signup" value="Sign Up" /></div>
		</form>							
		';	


};

?>	
<!-- Leader Board -->

</div>
<h3>Overall Rankings</h3>
<table border="1" width="100%" >
<tr>
<th>Score</th><th>Username</th>
</tr>

<?php			
$cn = mysql_connect($DB_IP, $DBUSER, $DBPASS);
mysql_select_db(onj, $cn);
$query = "select username,score from `users` order by score DESC limit 5 ";
$logged = mysql_query($query);

for($i=0;$logg = mysql_fetch_array($logged);$i++){

	echo "	
		<tr ".($i%2==0?"class= 'altrow'":"").">
		<td > ".$logg['score']."</td>	<td ><a href='profile.php?username=".$logg['username']."'>".$logg['username']."</a></td>
		</tr>";
}
mysql_close($cn);

?>
</table>

</div>		



<!-- content end -->	

