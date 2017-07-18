<?php
if(isset($_SESSION['FirstName']) && isset($_SESSION['LastName'])){
	echo '<p>Hi, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>You are already logged in and good to go!</p>';
	return;
}
$time=time();
$givelogin=0;
$logine=$emaile=$passworde='';//default error messages
$email=$salt='';//default form values
$response='';//output for the user
//Make sure we have an authentication code
$requested=$_SERVER["REQUEST_URI"];
$authenticationcode=regexchecker($requested,'authenticationcode');
if(!$authenticationcode){
	echo '<p>Please check your email for the correct link to authenticate your account.</p>';
	return;
}
//Make sure the session variables that we'll use later are set.
if(!isset($_SESSION['LoginAttempt']) || !isset($_SESSION['LoginAttemptTime'])){
	$_SESSION['LoginAttempt']=0;
	$_SESSION['LoginAttemptTime']=$time;
}
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$_SESSION['LoginAttempt']++;
	$_SESSION['LoginAttemptTime']=$time;
	@extract($_POST);
	if(($email=spacecleaner($email))=='' || !regexchecker($email, 'email')){
		$givelogin++;
		$emaile=' Please provide a valid email address.';
	}
	if(($salt=spacecleaner($salt))=='' || !regexchecker($salt,'salt')){
		$givelogin++;
		$logine=' There was a problem with your request. Please make sure you have Javascript turned on and try again.';
	}
	if(($password=spacecleaner($password))=='' || !regexchecker($password, 'md5') || $password==md5('')){
		$givelogin++;
		$passworde=' Please enter your password and make sure Javascript is enabled.';
	}
	if(!$givelogin){//we haven't had any errors yet, so try logging in the user
    $rows=0;
		usedb();//clean everything before submission
		$email=mysql_real_escape_string($email);
		$password=mysql_real_escape_string($password);
		$authenticationcode=mysql_real_escape_string($authenticationcode);
		$rows=($result=mysql_query($sql="SELECT * FROM users WHERE Email='$email' LIMIT 1"))? mysql_num_rows($result) : 0;
		if($rows){//we have a user with that email address. Let's check that the password is correct
			$assoc=mysql_fetch_assoc($result);
			if(!verifypassword($salt, $password, $assoc['PassWord'])){
				mysql_close();
				$givelogin++;
				$passworde= 'The password you provided was incorrect.';
			}
			elseif($assoc['EID']==0){//The user has already authenticated, so log them in.
				mysql_close();
				setsessionvariables($assoc);
				echo '<p>Welcome back, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>You are now logged in!</p>';
				return;
			}
			elseif($assoc['EID']==1){//The user needs to authenticate their account
        if($assoc['ECode']==$authenticationcode){//they provided the right code remove the error from their account and log them in
          $result=mysql_query($sql="UPDATE users SET EID=0, ECode=NULL WHERE Email='$email' LIMIT 1");
					mysql_close();
					if($result){
            setsessionvariables($assoc);
						echo '<p>Congratulations, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'!</p><p>Your account has been authenticated and you have been logged in.</p>';
						return;
					}else{
            $givelogin++;
						$logine= 'We\'re sorry, but there was an error updating our records. Please try again. If this problem continues, please <a href="http://wknapp.com/mailform">email</a> me.';
						emailadminerror('authenticateupdateerror', array('EID'=>$assoc['EID'], 'Ecode'=>$assoc['ECode'], 'sql'=>$sql));
					}
				}else{//The authentication code was incorrect
          mysql_close();
					echo '<p>We\'re sorry, but we could not authenticate your account.</p><p>Please check your email and make sure you follow the link to authenticat your account.</p>';
					emailadminerror('authenticatewrongcodeerror', array('EID'=>$assoc['EID'], 'Ecode'=>$assoc['ECode'], 'sql'=>$sql));
					return;
				}
			}
			else{//There's some problem, but we haven't defined it send an email to admin and log the person in
        mysql_close();
				emailadminerror('authenticateunknownerror', array('EID'=>$assoc['EID'], 'Ecode'=>$assoc['ECode'], 'sql'=>$sql));
				setsessionvariables($assoc);
				echo '<p>Welcome back, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>You are now logged in!</p>';
				return;
			}
		}else{//we don't have a user with those credentials
      mysql_close();
			$givelogin++;
			$emaile=' This email address is not registered. Would you like to <a href="http://wknapp.com/register">register</a> it?';
			emailadminerror('authenticateerrornocredentialeduser', array('Email'=>$email, 'sql'=>$sql));
		}
	}
	if($givelogin && $logine=='')$logine=' Please correct the following errors and try again.';
}else $givelogin++;

//if we've had mutliple unsuccessful attempts present this so the user can't try again
//Put it here so we don't block them on their last try!
if($time-$_SESSION['LoginAttemptTime']<300 and $_SESSION['LoginAttempt']>3){//The've tried logging 3 times in in the last 5 minutes incorrectly
	echo '<p>Logging in has temporarily been blocked to protect our users from fraud.</p><p>Please wait 5 minutes before attempting to log in again.</p>';
	return;
}

if($givelogin){
$salt=substr(md5('william'.$time),10,10);
$response.=<<<END
<script type="text/javascript">
<!--
	function validateform(){
		var email=clean('whiteborder', document.getElementById('login.email').value);
		var salt=clean('whiteborder', document.getElementById('login.salt').value);
		var encryptedpassword = hex_md5(salt+(hex_md5(clean('whiteborder', document.getElementById('login.password').value))));
		var blankpass = hex_md5(salt+(hex_md5('')));
		var errors=0;
		//clean any previous error messages
		document.getElementById('login.error').innerHTML = '';
		document.getElementById('login.email.error').innerHTML = '';
		document.getElementById('login.password.error').innerHTML = '';
		
		if(email=='' || !validate('email', email)){
			document.getElementById('login.email.error').innerHTML = ' Please provide a valid email address.';
			errors++;
		}
		if(encryptedpassword==blankpass){
			document.getElementById('login.password.error').innerHTML = ' Please enter a password.';
			errors++;
		}
		
		if(errors){
			document.getElementById('login.error').innerHTML = ' Please fix the errors and try again.';
			return false;
		}
		else{
			document.getElementById('login.password').value=encryptedpassword;
			return true;
		}
	}
//-->
</script>
<p>Please enter your email and password to authenticate your account.<span id="login.error" class="incorrect">$logine</span>
<form id="login" action="http://wknapp.com/$authenticationcode/authenticate" onSubmit="return validateform();" method="post">
 <input id="login.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your email address.<span id="login.email.error" class="incorrect">$emaile</span></br>
 <input id="login.password" type="password" name="password" value="" size="35" maxlength="60"> Your password.<span id="login.password.error" class="incorrect">$passworde</span></br>
 <input id="login.salt" type="hidden" name="salt" value="$salt">
 <input type="submit" value="Log In">
</form>
</p>
END;
}
echo $response;
?>