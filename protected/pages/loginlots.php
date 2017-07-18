<?php
$option=0;
$posted=0;
$givelogin=0;
$giveregistration=0;
$response='';
$requested=$_SERVER["REQUEST_URI"];
echo "The user requested $requested";

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$posted=1;
	@extract($_POST);
	var_dump($_POST);
}
else{//it wasn't posted, but we might be authenticating information
	if(strlen($requested)>6){//the user is doing something special
		if($ecode=regexchecker($requested, 'authenticationcode')){//authenticate the user
			usedb();
			$ecode=mysql_real_escape_string($ecode);
			if($result=mysql_query($sql="SELECT ID FROM users where Ecode='$ecode' LIMIT 1")){
				if(mysql_num_rows($result)==1){//user exists
					$assoc = mysql_fetch_assoc($result);
					$userid=$assoc['ID'];
					$result=mysql_query($sql="UPDATE users SET Ecode='' ID='$userid' LIMIT 1");
					$givelogin=1;
					mysql_close();
					if(!$result){//it didn't update
						emailadminerror('mysqlerror', array('location'=>"updateuserauthenticationcode", 'query'=>$sql));
						$response.='There was a problem processing your request, please try logging in.</br>';
						$givelogin=1;
					}
				}else{ // no one had that error code
					mysql_close();
					emailadminerror('mysqlerror', array('location'=>"selectauthenticationcodenorows", 'query'=>$sql));
					$response.='You have been successfully authenticated, please try logging in.</br>';
					$givelogin=1;
				}
			}
			else{//we didn't find the error
				mysql_close();
				emailadminerror('mysqlerror', array('location'=>"selectauthenticationcode", 'query'=>$sql));
				$response.='There was a problem processing your request, please try logging in.</br>';
				$givelogin=1;
			}
		}
		elseif($ecode=regexchecker($_SERVER["REQUEST_URI"], 'passwordresetcode')){//reset the users password
			usedb();
			$ecode=mysql_real_escape_string($ecode);
			if($result=mysql_query($sql="SELECT ID FROM users where Ecode='$ecode' LIMIT 1")){
				if(mysql_num_rows($result)==1){//user exists
					mysql_close();
					$response.=<<<END
	<p>Please enter your email and the new password you would like to use.</br><span id="passwordreset.error" class="incorrect"></span>
	<form id="passwordreset" action"http://wknapp.com/login" onSubmit="return controller('login', 'pwdreset')" method="post">
	 <input id="passwordreset.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your email address.<span id="passwordreset.email.error" class="incorrect"></span></br>
	 <input id="passwordreset.pwd1" type="password" name="passwd1" value="" size="35" maxlength="60"> Your password.<span id="passwordreset.pwd1.error" class="incorrect"></span></br>
	 <input id="passwordreset.pwd2" type="password" name="passwd2" value="" size="35" maxlength="60"> Your password, again.<span id="passwordreset.pwd2.error" class="incorrect"></span></br>
	 <input type="hidden" name="login" value="pwdreset">
	 <input type="hidden" name="resetcode" value="$ecode">
	 <input type="submit" value="Log In">
	</form>
	</p>
END;
				}else{ // no one had that error code
					mysql_close();
					emailadminerror('mysqlerror', array('location'=>"selectauthenticationcodenorows", 'query'=>$sql));
					$response.='There are no pending password associated with link you provided. If you would like to change your password click <a href="http://wknapp.com/resetpasswordform/login"></a>>.</br>';
					$givelogin=1;
				}
			}
			else{//we didn't find the error
				mysql_close();
				emailadminerror('mysqlerror', array('location'=>"selectauthenticationcode", 'query'=>$sql));
				$response.='There was a problem processing your request, please try logging in.</br>';
				$givelogin=1;
			}
		}
		elseif(strpos($requested, '/resetpasswordform')===0){//they want to reset their password. Ask for their email.
			$response.=<<<END
	<p>Please enter your email address to receive a special link where you can reset your password.</br><span id="passwordreset.error" class="incorrect"></span>
	<form id="passwordreset" action"http://wknapp.com/resetpassword/login" onSubmit="return controller('login', 'pwdresetform')" method="post">
	 <input id="passwordreset.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your email address.<span id="passwordreset.email.error" class="incorrect"></span></br>
	 <input type="submit" value="Submit">
	</form>
	</p>
END;
		}
		elseif(strpos($requested, '/resetpassword')===0){//they want to reset their password. Ask for their email.
			$response.=<<<END
	<p>Please enter your email address to receive a special link where you can reset your password.</br><span id="passwordreset.error" class="incorrect"></span>
	<form id="passwordreset" action"http://wknapp.com/login" onSubmit="return controller('login', 'pwdreset')" method="post">
	 <input id="passwordreset.email" type="text" name="email" value="$email" size="35" maxlength="60"> Your email address.<span id="passwordreset.email.error" class="incorrect"></span></br>
	 <input type="submit" value="Submit">
	</form>
	</p>
END;
		}
	}
}
if(!$option){
$response=<<<END
<p>If you already have an account, sign in here.</br>
<form id="login" action"http://wknapp.com/login" onSubmit="return controller('login', 'login')" method="post">
 <input type="text" name="email" value="$email" size="35" maxlength="60"> Your email address.</br>
 <input type="password" name="passwd" value="" size="35" maxlength="60"> Your password.</br>
 <input type="hidden" name="login" value="login">
 <input type="submit" value="Log In">
</form>
</p>
<p>
If you don't have an account yet, please fill in the information below to register.<span id="register.error" class="incorrect"></span>
<form id="register" action"http://wknapp.com/login" onSubmit="return controller('login', 'register')" method="post">
 <input id="register.email" type="text" name="email" value="$email" size="35" maxlength="60"> Email<span id="register.email.error" class="incorrect">: Only valid email addresses please.</span></br>
 <input id="register.first" type="text" name="first" value="$first" size="35" maxlength="60"> Your first name<span id="register.first.error" class="incorrect"></span></br>
 <input id="register.last" type="text" name="last" value="$last" size="35" maxlength="60"> Your last name<span id="register.last.error" class="incorrect"></span></br>
 <input id="register.display" type="text" name="display" value="$display" size="35" maxlength="60"> A name to uniquely identify you to me but no one else.<span id="register.display.error" class="incorrect"> Do not use a name anyone would know you by!</span></br>
 <input id="register.pwd1" type="password" name="passwd1" value="" size="35" maxlength="60"> A password<span id="register.pwd1.error" class="incorrect"></span></br>
 <input id="register.pwd2" type="password" name="passwd2" value="" size="35" maxlength="60"> Your password, again<span id="register.pwd2.error" class="incorrect"></span></br>
 <input type="hidden" name="option" value="register">
 <input type="submit" value="Register">
</form>
</p>
END;
echo $response;
}
if($option=='register'){
	//check to make sure we have all the information we need
	//first lets clean any weird spacing
	$email=spacecleaner($email);
	$first=spacecleaner($first);
	$last=spacecleaner($email);
	$display=spacecleaner($email);
	$passwd1=spacecleaner($email);
	$passwd2=spacecleaner($email);

	$registere=$emaile=$firste=$laste=$displaye=$passwd1e=$passwd2e='';
	$errors=0;
	if(!regexchecker($email, 'email')){$emaile=': Please use a valid email address.';$errors++;}
	if($first==''){$firste=': Please enter your first name.';$errors++;}
	else if(!regexchecker($first, 'taboo'){$firste=': No profanity please.';$errors++;}
	if($last==''){$laste=': Please enter your last name.';$errors++;}
	else if(!regexchecker($last, 'taboo'){$laste=': No profanity please.';$errors++;}
	if($display==''){$displaye=': Please enter a code so I can uniquely identify you.';$errors++;}
	else if(!regexchecker($display, 'taboo'){$displaye=': No profanity please.';$errors++;}
	if(!regexchecker($passwd1, 'md5')){$passwd1e=': Make sure Javascript is enabled.';$errors++;}
	else if($passwd1=md5('')){$passwd1e=': Please enter a password.';$errors++;}
	if(!regexchecker($passwd2,'md5')){$passwd2e=': Make sure Javascript is enabled.';$errors++;}
	else if($passwd1=md5('')){$passwd2e=': Please enter a password.';$errors++;}
	else if($passwd1!=$passwd2){$passwd2e=': The passwords do not match.';$errors++;}

	if(!$errors){//the data look good, let's check for the user and add them if they don't already exist
		usedb();//clean everything before submission
		$email=mysql_real_escape_string($email);
		$first=mysql_real_escape_string($first);
		$last=mysql_real_escape_string($last);
		$display=mysql_real_escape_string($display);
		$passwd1=mysql_real_escape_string($passwd1);
		$time=mysql_real_escape_string($time);
		$ecode==mysql_real_escape_string(substr(md5($email.$first.$last.$passwd1.$time),10,10));
		if($result=mysql_query($sql="SELECT ID FROM users where Email='$email' LIMIT 1")){
			if(mysql_num_rows($result)==1){//user exists
				mysql_close();
				$emaile = ': There is already a user with this email address.';
				$errors++;
			}
			else{//there's no user with that name or email add the user
				if(mysql_query($sql="INSERT INTO users (PassWord, Email, FirstName, LastName, DisplayName, ECode) VALUES ('$passwd1', '$email', '$first', '$last', '$display', '$ecode')")){
					$insertid=mysql_insert_id();
					mysql_close();
					if($insertid>0){
						sendauthenticationemail("", $email, $uname, $ecode);
					}
					else{
						emailadminerror('mysqlerror', array('location'=>"joinaddinguser$email", 'query'=>$sql));
						$registere= 'There was a problem processing your request.  Please, try again and alert us if the problem continues.';
						$errors++;
					}
				}
				else{
					mysql_close();
					emailadminerror('mysqlerror', array('location'=>"joinaddinguser$email", 'query'=>$sql));
					$registere= 'There was a problem processing your request.  Please, try again and alert us if the problem continues.';
					$errors++;
				}
			}
		}
	}
	if(!$errors){//there were no errors include the box for authentication
	}
}//done with register
?>