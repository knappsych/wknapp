<?php
//If post see if the user provided matching passwords a valid email address and had the right code in ECode2

//If no post without a special URL and a logged in user, generate a code, save it, and send it to the user
//If no post without a special URL and an unlogged user, present form for email address
//If post    without a special URL check that the email is from a valid user then generate a code, save it, and send it to the user
//If no post with a special url present form for email address and two new passwords
//If post with a special URL check that the email address is associated with the code in the URL and then change the password if they're associated
$time=time();
$posted=0;
$giveform=0;
$resetpassworde=$emaile='';//default error messages
$email='';//default form values
$response='';
if($_SERVER['REQUEST_METHOD'] == "POST"){
	@extract($_POST);
	$posted=1;
}

//Check for an resetpassword code
$requested=$_SERVER["REQUEST_URI"];
$resetpasswordcode=regexchecker($requested, 'resetpasswordcode');
if($resetpasswordcode==false){//they need to get a reset password code and either need the form or submitted it
	if($posted==1){//user submitted the send email form. make sure the email exists
		if(($email=spacecleaner($email))=='' || !regexchecker($email, 'email')){
			$giveform++;
			$emaile=' Please provide a valid email address.';
		}
		else{//it's a valid email address, make sure it's in the database
			usedb();
			$email=mysql_real_escape_string($email);
			$resetpasswordcode=mysql_real_escape_string(substr(md5($email.$time.'moretohashforwknapp'),10,10));
			$rows=($result=mysql_query($sql="SELECT * FROM users WHERE Email='$email' LIMIT 1"))? mysql_num_rows($result) : 0;
			if($rows){//we have a user with that email address. So generate the code, save it, and send them an email.
				$assoc=mysql_fetch_assoc($result);
				if($result=mysql_query($sql="UPDATE users SET ECode2='$resetpasswordcode' WHERE Email='$email' LIMIT 1")){//it updated
					mysql_close();
					$displayname=$assoc['FirstName'].' '.$assoc['LastName'];
					emailpasswordresetcode($displayname, $email, $resetpasswordcode);
					echo '<p>Thanks, '.$displayname.'.</p><p>An email has been sent to '.$email.'.</p><p>Please follow the instructions to change your password.</p>';
					return;
				}
				else{//it didn't update
					mysql_close();
					$giveform++;
					$resetpassworde=' Sorry, there was a problem processing your request. Please try again and <a href="http://wknapp.com/mailform">contact</a> me if the problem continues';
					emailadminerror('resetpasswordupdateuserwithoutcodeerror', array('Email'=>$email, 'sql'=>$sql));
				}
			}else{//we don't have a user with those credentials
				mysql_close();
				$giveform++;
				$emaile=' This email address is not registered. Would you like to <a href="http://wknapp.com/register">register</a> it?';
				emailadminerror('resetpasswordselectuserwithoutcodeerror', array('Email'=>$email, 'sql'=>$sql));
			}
		}
		if($giveform && $resetpassworde=='')$resetpassworde=' Please correct the following errors and try again.';
	}
	else $giveform=1;//we need to present the email form
	if($giveform){//If we're here, then present present the email form
		$response.=<<<END
<script type="text/javascript">
<!--
	function validateform(){
		var email=clean('whiteborder', document.getElementById('resetpassword.email').value);
		//clean any previous error messages
		document.getElementById('resetpassword.error').innerHTML = '';
		document.getElementById('resetpassword.email.error').innerHTML = '';
		
		if(email=='' || !validate('email', email)){
			document.getElementById('resetpassword.email.error').innerHTML = ' Please provide a valid email address.';
			document.getElementById('resetpassword.error').innerHTML = ' Please fix the errors and try again.';
			return false;
		}
		else return true;
	}
//-->
</script>
<p>To change your password, please enter your email address.<span id="resetpassword.error" class="incorrect">$resetpassworde</span>
<form id="resetpassword" action"http://wknapp.com/resetpassword" onSubmit="return validateform();" method="post">
 <input id="resetpassword.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your valid email address.<span id="resetpassword.email.error" class="incorrect">$emaile</span></br>
 <input type="submit" value="Send Email">
</form>
</p>
END;
	}
}
else{//they have a code change their password or give the form to change it
	$password1e=$password2e='';
	if($posted==1){//user submitted the send email form. make sure the email exists with that code
		if(($email=spacecleaner($email))=='' || !regexchecker($email, 'email')){
			$giveform++;
			$emaile=' Please provide a valid email address.';
		}
		if(($password1=spacecleaner($password1))=='' || !regexchecker($password1, 'md5') || $password1==md5('')){
			$giveform++;
			$password1e=' Please enter your password and make sure Javascript is enabled.';
		}
		if(($password2=spacecleaner($password2))=='' || !regexchecker($password2, 'md5') || $password2==md5('')){
			$giveform++;
			$password2e=' Please enter your password and make sure Javascript is enabled.';
		}
		if($password1!=$password2){
			$giveform++;
			$password2e=' The passwords provided do not match.';
		}
		if(!$giveform){//everything's ok, check that the email and code are in the db
			usedb();
			$email=mysql_real_escape_string($email);
			$password1=mysql_real_escape_string($password1);
			$resetpasswordcode=mysql_real_escape_string($resetpasswordcode);
			$rows=($result=mysql_query($sql="SELECT * FROM users WHERE Email='$email' AND ECode2='$resetpasswordcode' LIMIT 1"))? mysql_num_rows($result) : 0;
			if($rows){//we have a user with that email address and code, so save their new password
				$assoc=mysql_fetch_assoc($result);
				if($result=mysql_query($sql="UPDATE users SET PassWord='$password1', ECode2=NULL WHERE Email='$email' LIMIT 1")){//it updated
					mysql_close();
					setsessionvariables($assoc);
					echo '<p>Welcome back, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>Your password has been changed and you are now logged in!</p>';
					return;
				}
				else{//it didn't update
					mysql_close();
					$giveform++;
					$resetpassworde='Sorry, there was a problem processing your request. Please try again and <a href="http://wknapp.com/mailform">contact</a> me if the problem continues';
					emailadminerror('resetpasswordupdateuserwithcodeerror', array('Email'=>$email, 'sql'=>$sql));
				}
			}else{//we don't have a user with those credentials
				mysql_close();
				$giveform++;
				$emaile=' This email address is not associated with this URL. Please check your email and try again.';
				emailadminerror('resetpasswordselectuserwithcodeerror', array('Email'=>$email, 'sql'=>$sql));
			}
		}
		if($giveform && $resetpassworde=='')$resetpassworde=' Please correct the following errors and try again.';
	}
	else $giveform=1;//we need to present the email form
	if($giveform){//If we're here, then present present the email form
		$response.=<<<END
<script type="text/javascript">
<!--
	function validateform(){
		var email=clean('whiteborder', document.getElementById('resetpassword.email').value);
		var encryptedpassword1 = hex_md5(clean('whiteborder', document.getElementById('resetpassword.password1').value));
		var encryptedpassword2 = hex_md5(clean('whiteborder', document.getElementById('resetpassword.password2').value));
		var blankpass = hex_md5('');
		var errors=0;
		//clean any previous error messages
		document.getElementById('resetpassword.error').innerHTML = '';
		document.getElementById('resetpassword.email.error').innerHTML = '';
		document.getElementById('resetpassword.password1.error').innerHTML = '';
		document.getElementById('resetpassword.password2.error').innerHTML = '';
		
		if(email=='' || !validate('email', email)){
			document.getElementById('resetpassword.email.error').innerHTML = ' Please use a valid email address.';
			errors++;
		}
		
		if(encryptedpassword1==blankpass){
			document.getElementById('resetpassword.password1.error').innerHTML = ' Please enter a password.';
			errors++;
		}
		
		if(encryptedpassword2==blankpass){
			document.getElementById('resetpassword.password2.error').innerHTML = ' Please enter a password.';
			errors++;
		}
		else if(encryptedpassword2!=encryptedpassword1){
			document.getElementById('resetpassword.password2.error').innerHTML = ' The passwords do not match.';
			errors++;
		}
		
		if(errors){
			document.getElementById('resetpassword.error').innerHTML = ' Please correct the following errors and try again.';
			return false;
		}
		else{
			document.getElementById('resetpassword.password1').value=encryptedpassword1;
			document.getElementById('resetpassword.password2').value=encryptedpassword2;
			return true;
		}
	}
//-->
</script>
<p>Please enter your email address and your new password.<span id="resetpassword.error" class="incorrect">$resetpassworde</span>
<form id="resetpassword" action"http://wknapp.com/$resetpasswordcode/resetpassword" onSubmit="return validateform();" method="post">
 <input id="resetpassword.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your email address.<span id="resetpassword.email.error" class="incorrect">$emaile</span></br>
 <input id="resetpassword.password1" type="password" name="password1" value="" size="35" maxlength="60"> A password.<span id="resetpassword.password1.error" class="incorrect">$password1e</span></br>
 <input id="resetpassword.password2" type="password" name="password2" value="" size="35" maxlength="60"> Your password, again.<span id="resetpassword.password2.error" class="incorrect">$password2e</span></br>
 <input type="submit" value="Reset Password">
</form>
</p>
END;
	}
}
echo $response;
?>