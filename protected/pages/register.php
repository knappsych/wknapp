<?php
if(isset($_SESSION['FirstName']) && isset($_SESSION['LastName'])){
	echo '<p>Hi, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>You are already logged in and good to go!</p>';
	return;
}
$time=time();
$giveregister=0;
$registere=$emaile=$firste=$laste=$displaye=$password1e=$password2e='';//default error messages
$email=$first=$last=$display=$password1=$password2='';//default form values
$response='';//output for the user

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$posted=1;
	@extract($_POST);
	if(($email=spacecleaner($email))=='' || !regexchecker($email, 'email')){
		$giveregister++;
		$emaile=' Please provide a valid email address.';
	}
	if(($first=spacecleaner($first))==''){
		$giveregister++;
		$firste=' Please provide your first name.';
	}elseif(regexchecker($first, 'taboo')){
		$giveregister++;
		$firste=' No profanity please.';
	}
	
	if(($last=spacecleaner($last))==''){
		$giveregister++;
		$laste=' Please provide your last name.';
	}elseif(regexchecker($last, 'taboo')){
		$giveregister++;
		$laste=' No profanity please.';
	}
	if(($password1=spacecleaner($password1))=='' || !regexchecker($password1, 'md5') || $password1==md5('')){
		$giveregister++;
		$password1e=' Please enter your password and make sure Javascript is enabled.';
	}
	if(($password2=spacecleaner($password2))=='' || !regexchecker($password2, 'md5') || $password2==md5('')){
		$giveregister++;
		$password2e=' Please enter your password and make sure Javascript is enabled.';
	}
	if($password1!=$password2){
		$giveregister++;
		$password2e=' The passwords provided do not match.';
	}
	if(!$giveregister){//We haven't hand any errors yet so try to register the user
		usedb();//clean everything before submission
		$email=mysql_real_escape_string($email);
		$first=mysql_real_escape_string($first);
		$last=mysql_real_escape_string($last);
		$password1=mysql_real_escape_string($password1);
		$time=mysql_real_escape_string($time);
		$ecode=mysql_real_escape_string(substr(md5($email.$first.$last.$password1.$time),10,10));
		if($result=mysql_query($sql="SELECT ID FROM users WHERE Email='$email' LIMIT 1")){
			if(mysql_num_rows($result)==1){//user exists
				mysql_close();
				$emaile = ' There is already a user with this email address.';
				$giveregister++;
			}
			else{//there's no user with that name or email add the user
				if(mysql_query($sql="INSERT INTO users (PassWord, Email, FirstName, LastName, EID, ECode) VALUES ('$password1', '$email', '$first', '$last', '1', '$ecode')")){
					$insertid=mysql_insert_id();
					mysql_close();
					if($insertid>0){
						sendauthenticationemail("$first $last", $email, $ecode);
						echo '<p>Thanks for registering.</p><p>Please check your email and follow the instructions to authenticate your account.</p>';
						return;
					}
					else{
						emailadminerror('mysqlerror', array('location'=>"noinsertidfor$email", 'query'=>$sql));
						$registere= ' There was a problem processing your request.  Please try again and alert us if the problem continues.';
						$giveregister++;
					}
				}
				else{
					mysql_close();
					emailadminerror('mysqlerror', array('location'=>"insertintousers$email", 'query'=>$sql));
					$registere= ' There was a problem processing your request.  Please try again and alert us if the problem continues.';
					$giveregister++;
				}
			}
		}else{//there was an error with looking up the user, please alert admin
			emailadminerror('mysqlerror', array('location'=>"selectfromusers$email", 'query'=>$sql));
			$registere= ' There was a problem processing your request.  Please try again and alert us if the problem continues.';
			$giveregister++;
		}
	}
	if($giveregister && $registere=='')$registere=' Please correct the following errors and try again.';
}else $giveregister++;

if($giveregister){//give the registration form if we need it.
	$response.=<<<END
<script type="text/javascript">
<!--
	function validateform(){
		var email=clean('whiteborder', document.getElementById('register.email').value);
		var first=clean('whiteborder', document.getElementById('register.first').value);
		var last=clean('whiteborder', document.getElementById('register.last').value);
		var encryptedpassword1 = hex_md5(clean('whiteborder', document.getElementById('register.password1').value));
		var encryptedpassword2 = hex_md5(clean('whiteborder', document.getElementById('register.password2').value));
		var blankpass = hex_md5('');
		var errors=0;
		//clean any previous error messages
		document.getElementById('register.error').innerHTML = '';
		document.getElementById('register.email.error').innerHTML = '';
		document.getElementById('register.first.error').innerHTML = '';
		document.getElementById('register.last.error').innerHTML = '';
		document.getElementById('register.password1.error').innerHTML = '';
		document.getElementById('register.password2.error').innerHTML = '';
		
		if(email=='' || !validate('email', email)){
			document.getElementById('register.email.error').innerHTML = ' Please use a valid email address.';
			errors++;
		}
		
		if(first==''){
			document.getElementById('register.first.error').innerHTML = ' Please enter your first name.';
			errors++;
		}
		
		if(last==''){
			document.getElementById('register.last.error').innerHTML = ' Please enter your last name.';
			errors++;
		}
		
		if(encryptedpassword1==blankpass){
			document.getElementById('register.password1.error').innerHTML = ' Please enter a password.';
			errors++;
		}
		
		if(encryptedpassword2==blankpass){
			document.getElementById('register.password2.error').innerHTML = ' Please enter a password.';
			errors++;
		}
		else if(encryptedpassword2!=encryptedpassword1){
			document.getElementById('register.password2.error').innerHTML = ' The passwords do not match.';
			errors++;
		}
		
		if(errors){
			document.getElementById('register.error').innerHTML = ' Please correct the following errors and try again.';
			return false;
		}
		else{
			document.getElementById('register.password1').value=encryptedpassword1;
			document.getElementById('register.password2').value=encryptedpassword2;
			return true;
		}
	}
//-->
</script>
<p>
If you don't have an account yet, please fill in the information below to register.<span id="register.error" class="incorrect">$registere</span>
<form id="register" action="http://wknapp.com/register" onSubmit="return validateform()" method="post">
 <input id="register.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your valid email address.<span id="register.email.error" class="incorrect">$emaile</span></br>
 <input id="register.first" type="text" name="first" value="$first" size="35" maxlength="60"> Your first name.<span id="register.first.error" class="incorrect">$firste</span></br>
 <input id="register.last" type="text" name="last" value="$last" size="35" maxlength="60"> Your last name.<span id="register.last.error" class="incorrect">$laste</span></br>
 <input id="register.password1" type="password" name="password1" value="" size="35" maxlength="60"> A password.<span id="register.password1.error" class="incorrect">$password1e</span></br>
 <input id="register.password2" type="password" name="password2" value="" size="35" maxlength="60"> Your password, again.<span id="register.password2.error" class="incorrect">$password2e</span></br>
 <input type="submit" value="Register">
</form>
</p>
END;
}
echo $response;
?>