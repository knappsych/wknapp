<?php
	$time=time();
	if(($sessionid=session_id())===''){
		session_start();//start the session
		$_SESSION['StartTime']=$time;
		$_SESSION['StartIP']=$_SERVER['REMOTE_ADDR'];
	}
	
	include '/homepages/41/d92908607/htdocs/wknapp/protected/private.php';
	if (isadmin()) include '/homepages/41/d92908607/htdocs/wknapp/protected/testfuncs.php';
	else include '/homepages/41/d92908607/htdocs/wknapp/protected/wknappfuncs.php';
	$page = givepage();
?>