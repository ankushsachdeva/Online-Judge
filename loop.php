<?php
include('settings.php');
session_start();
//if($_SESSION['username']!='admin')exit(0);
$cn = mysql_connect('localhost', $DBUSER, $DBPASS);
mysql_select_db($DBNAME, $cn);
function validate($a,$b,$submID,$username){
    $query = "SELECT DATE_FORMAT(`endTime`,'%Y-%m-%d %T') AS endTime,DATE_FORMAT(`startTime`,'%Y-%m-%d %T') AS startTime FROM contests WHERE contestID=$a";
    $res=mysql_query($query) ;
	$res=mysql_fetch_array($res);
    $etime = $res['endTime']; 
    $stime = $res['startTime'];
    $ctime = date('Y-m-d H:i:s');
    if(($ctime<$stime)){
        $query="UPDATE submissions SET status = 4,score=0,runtime=-1,runmem=0 where submID = $submID";
        $temp=mysql_query($query) ;
        $handle = fopen(".htaccess", "w+");
fwrite($handle,"SetEnv HTTP_coderunning 0");
fclose($handle);
        return -2;//future contest
    }
    elseif(($etime<$ctime) ){
        
        return -1;//past contest
        }
    else {
	    $sts=1;//current
	    $query="SELECT count(*) AS count from contest_$a where userID= $b";
	    $logged=mysql_query($query) ;
	    $logg=mysql_fetch_array($logged);
	    if($logg['count']==0){

		    $query="INSERT INTO contest_$a(userID,username) VALUES ($b,'".$username."')";
		    $logged=mysql_query($query);
	    }
    }
    return $sts;
}         
function rand_string( $length ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}
while(1){
$code=array(
		"C++"=>"cpp",
		"C"=>"c",
		"python"=>"py",
		"Zombie"=>"zm"
	   );
$cur_dir=getcwd();
if($_SERVER["HTTP_coderunning"]==1){

	sleep(1);
	continue;
}
else{
	$query="SELECT * from submissions where status=6 order by submID";
	$logged=mysql_query($query);
	$r=mysql_fetch_array($logged);
	
	
	if(!$r){
		
		$handle = fopen("$cur_dir/.htaccess", "w+");
		fwrite($handle,"SetEnv HTTP_coderunning 0");
		fclose($handle);
		sleep(3);
		continue;
	}

	$handle = fopen("$cur_dir/.htaccess", "w+");
	fwrite($handle,"SetEnv HTTP_coderunning 1");
	fclose($handle);
	$userid=$r['userID'];
	$username=$r['username'];	
	$ext=$code[$r['submlang']];
	$submID=$r['submID'];
	$problemID=$r['problemID'];
	$query="SELECT * from problems where problemID= ".$problemID;
	$logged=mysql_query($query);
	$logg=mysql_fetch_array($logged);
	$contestID=$logg['contestID'];
	$score=$logg['score'];
	$problemName=$logg['problemName'];
	$runtimelimit=$logg['timeLimit'];
	$runmemlimit=$logg['memoryLimit'];
	$accepted_sol=$logg['accepted'];
	$sts=validate($contestID,$userid,$submID,$username);
	if($sts==-2)continue;
}

$destfile="$cur_dir/submissions/$userid/$submID".".".$ext;
$correctout="$cur_dir/problems/".$problemID.'/out';
$correctin="$cur_dir/problems/".$problemID.'/in';


if(strcmp($ext,'cpp')==0)$cmd="g++ -pipe -lm -s -fomit-frame-pointer ";
elseif (strcmp($ext,'c')==0)$cmd="gcc -pipe -lm -s -fomit-frame-pointer ";
elseif (strcmp($ext,'java')==0)$cmd="javac ";

$current_score=0;
$runtime=-1;
$random=rand_string( 7 );
exec("sudo cp $destfile $chroot_dir/$submID".".".$ext);

if(!strcmp($ext,"c") || !(strcmp($ext,"cpp"))){

	//exec($cmd.$destfile." -o /var/www/newonj/submissions/$userid/exeble",$ouput,$verdict);


	exec("sudo $cur_dir/compiler $random $submID $ext",$verdict,$output);
	$verdict=file_exists("$chroot_dir/$random");
	var_dump($verdict);
	if(!$verdict)$verdict=1;//compilation error
	else{
		$destfile="/$random";
		exec("sudo cp $correctin $chroot_dir/$random.in");
		$correctin="/$random.in";

		exec("sudo $cur_dir/judge $destfile $correctin $ext $runtimelimit $runmemlimit $random.out", $output, $verdict);

		$runtime=(int)($output[0]);
		$runtime/=1000;
		$runmem=(int)($output[1]);
		$runmem/=1024;

		if($verdict==0){
			exec("sudo diff -w $chroot_dir/$random.out $correctout",$output,$verdict);

			if($verdict!=0)$verdict=2;

			else{$verdict=0;$current_score=$score;}
		}
		elseif($verdict==15){
			$verdict=3;
		}
		else $verdict=5;
	}
}
else if(!strcmp($ext,"py")){

	$runmemlimit*=2;//double memlimit for python
	$runtimelimit*=2;//double time limit for python
	$destfile="/$submID.py";
	exec("sudo cp $correctin $chroot_dir/$random.in");
	$correctin="/$random.in";    
	exec("sudo $cur_dir/judge $destfile $correctin $ext $runtimelimit $runmemlimit $random.out",$output,$verdict);
	var_dump ($output);
	$runtime=(int)($output[0]);
	$runtime/=1000;
	$runmem=(int)($output[1]);
	$runmem/=1024;


	if($verdict==0){
		exec("sudo diff -w $chroot_dir/$random.out $correctout",$output,$verdict);
		if($verdict!=0)$verdict=2;

		else{$verdict=0;$current_score=$score;}
	}
	elseif($verdict==15){
		$verdict=3;
	}
	else $verdict=5;
}
else{
        $runmemlimit*=2;//double memlimit for python
        $runtimelimit*=10;//double time limit for python

	$destfile="/$submID.zm";
	exec("sudo cp $correctin $chroot_dir/$random.in");
	$correctin="/$random.in";
        exec("sudo $cur_dir/judge $destfile $correctin $ext $runtimelimit $runmemlimit $random.out",$output,$verdict);
	var_dump($output);
	echo $random;
	var_dump($verdict);
	$runtime=(int)($output[0]);
        $runtime/=1000;
	$runmem=(int)($output[1]);
        $runmem/=1024;

	if($verdict==0){
                exec("sudo diff -w -B $chroot_dir/$random.out $correctout",$output,$verdict);
		echo "Veridt : ".$verdict;
		if($verdict!=0)$verdict=2;

                else{$verdict=0;$current_score=$score;}
        }
        elseif($verdict==15){
                $verdict=3;
        }
        else $verdict=5;


}
//exec("sudo rm $chroot_dir/$random $chroot_dir/$random.in $chroot_dir/$random.out $chroot_dir/$submID.$ext");
if($verdict==0){
	$query="UPDATE submissions SET status = $verdict,score=$current_score,runtime=$runtime,runmem=$runmem where submID = $submID";
	mysql_query($query);

	$query="update problems set submissions=submissions+1,accepted=accepted+1 where problemID=$problemID";		   
	mysql_query($query);
	if($sts==1){

		$query="select p$problemID as score from contest_$contestID where userID=$userid";
		$res=mysql_query($query);
		$res=mysql_fetch_array($res);

		$current_score=($res['score']<$current_score? $current_score-$res['score'] : 0);
		if($current_score!=0){
		    if($res['score']!=0)$accepted_sol=$res['penalty'];
		    if($late_penalty==0)$accepted_sol=0;
			$query="update contest_$contestID set p$problemID=p$problemID+$current_score, score=score+$current_score,penalty=$accepted_sol where userID=$userid";
			mysql_query($query);
			$query="update users set score=score+$current_score, penalty = penalty + $accepted_sol where userID=$userid";
			mysql_query($query);
		}
	}
}
else{
	$query="UPDATE submissions SET status = $verdict,score=$current_score where submID = $submID";
	mysql_query($query);

	$query="update problems set submissions=submissions+1 where problemID=$problemID"; 
	mysql_query($query);
}

$handle = fopen("$cur_dir/.htaccess", "w+");
fwrite($handle,"SetEnv HTTP_coderunning 0");
fclose($handle);

}
?>
