<?php
function givepage(){
	$extra='';
	$bodyclass='';
  $bannerimage='';
  $sidecontents='';
  $maincontents='';
  //start object buffering so we can get the sidebar information should we need it
  ob_start();
	//get what the user requested and normalize it
	$page = strtolower(preg_replace("/\/$/", "", $_SERVER["REQUEST_URI"]));//remove trailing forward slashes and make everything lower case
	if($page=='')$page='home';
	else{
		$page = array_filter(explode('/', preg_replace("/^\//", "", $page)));
		$page=$page[count($page)-1];
		if($loc=stripos($page,'.php')){
			$page=substr($page,0,$loc);
		}
	}
	switch($page){
		case 'index':
			$page='home';
			$loc=1;
		case 'home':
			
			$title= "William Knapp: Cognitive Experimental Psychologist";
			$bannerimage='<img class="headban" src="http://wknapp.com/img/home.jpg">';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/homebar.php');
			$sidecontents=ob_get_clean();
			ob_start();
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/home.php');
			$maincontents=ob_get_clean();
			break;
		case 'login':
			$title= "Sign in!";
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/login.php');
			$maincontents=ob_get_clean();
			break;
		case 'register':
			$title= "Sign up!";
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/register.php');
			$maincontents=ob_get_clean();
			break;
		case 'logout':
			$bannerimage='<img class="headban" src="http://wknapp.com/img/logout.jpg">';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/logout.php');
			$maincontents=ob_get_clean();
			$title= "Sign out!";
			break;
		case 'authenticate':
			$title= "Authenticate your account!";
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/authenticate.php');
			$maincontents=ob_get_clean();
			break;
		case 'resetpassword':
			$title= "Reset your password!";
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/resetpassword.php');
			$maincontents=ob_get_clean();
			break;
		case 'teaching':
			$title= 'Thoughts about teaching!';
			$bannerimage='<a href="https://backyardbrains.com/products/roboroach"><img class="headban" src="http://wknapp.com/img/teaching.jpg" title="My students using Backyard Brains\' products. The link takes you to the Roboroach kit which isn\'t yet available at Amazon.">';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/teachingbar.php');
			$sidecontents=ob_get_clean();
			ob_start();
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/teaching.php');
			$maincontents=ob_get_clean();
			break;
		case 'teachingold':
			$title= 'Thoughts about teaching! (Previous Version)';
			$bannerimage='<a href="https://backyardbrains.com/products/roboroach"><img class="headban" src="http://wknapp.com/img/teaching.jpg" title="My students using Backyard Brains\' products. The link takes you to the Roboroach kit which isn\'t yet available at Amazon.">';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/teachingbar.php');
			$sidecontents=ob_get_clean();
			ob_start();
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/teachingold.php');
			$maincontents=ob_get_clean();
			break;
		case 'classes':
			$title= 'Information about some of my classes!';
			$bannerimage='<img class="headban" src="http://wknapp.com/img/classes.jpg">';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/classesbar.php');
			$sidecontents=ob_get_clean();
			ob_start();
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/classes.php');
			$maincontents=ob_get_clean();
			break;
		case 'research':
			$title= 'Research interests!';
			$bannerimage='<img class="headban" src="http://wknapp.com/img/research.jpg">';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/researchbar.php');
			$sidecontents=ob_get_clean();
			ob_start();
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/research.php');
			$maincontents=ob_get_clean();
			break;
		case 'mailform':
			$title= "William's Anonymailer!";
			//I need to separate this better, but for now it will have to work
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				@extract($_POST);
				$name = stripslashes($name);
				$email = stripslashes($email);
				$subject = stripslashes($subject);
				$text = stripslashes($text);
				$formagain=false;
				if($text=='' || $text=='Type your message here.'){
				}
				else if(regexchecker($name, 'taboo') || regexchecker($email, 'taboo') || regexchecker($subject, 'taboo') || regexchecker($text, 'taboo')){
					$bannerimage='<img class="headban" src="http://wknapp.com/img/mailformspam.jpg">';
				}else{
					$bannerimage='<img class="headban" src="http://wknapp.com/img/mailformsent.jpg">';
				}
			}
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/mailform.php');
			$maincontents=ob_get_clean();
			break;
		case 'statsgrades':
			$title = "Statistics grades";
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/statsgrades.php');
			$maincontents=ob_get_clean();
			break;
		case 'glossary':
		case 'glossarypup':
			$title= "William's paper grading glossary!";
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/glossary.php');
			$maincontents=ob_get_clean();
			break;
		case 'plagiarism':
			$title= 'A practical guide to plagiarism!';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/plagiarism.php');
			$maincontents=ob_get_clean();
			break;
		case 'psychinfo':
			$title= 'How to find appropriate articles with PsycINFO/PsycNET!';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/psychinfo.php');
			$maincontents=ob_get_clean();
			break;
		case '403':
			header('HTTP/1.1 403 Forbidden');
			$title= '403 Forbidden.';
			require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/403.php');
			$maincontents=ob_get_clean();
			break;
		case '404':
		default:
			$give404=1;
			if(preg_match('/^([a-zA-Z]+)hw(\\d+)$/', $page, $matches)){
				$hwsub=$matches[1];
				$hwnum=$matches[2];
				if(!file_exists("/homepages/41/d92908607/htdocs/wknapp/protected/pages/homework/$hwsub/$hwnum.php"))$maincontents="It doesn't exist!";
				else{
					require_once("/homepages/41/d92908607/htdocs/wknapp/protected/pages/homework/$hwsub/$hwnum.php");
					$maincontents=ob_get_clean();
					break;
				}
			}
			else if(preg_match('/^([a-zA-Z]+)test(\\d+)p(\\d+)$/', $page, $matches)){//check to see if it's a test
				$testsub=$matches[1];
				$testnum=intval($matches[2]);
				$pagenum=intval($matches[3]);
				if(!file_exists("/homepages/41/d92908607/htdocs/wknapp/protected/pages/tests/$testsub/$testnum/$pagenum.php"));
				else{
					require_once("/homepages/41/d92908607/htdocs/wknapp/protected/pages/tests/$testsub/$testnum/$pagenum.php");
					$maincontents=ob_get_clean();
					break;
				}
			}
			if($give404){
				$page='404';
				header('HTTP/1.1 404 Not Found.');
				$title= '404 Not Found.';
				$bannerimage='<img class="headban" src="http://wknapp.com/img/404.jpg">';
				require_once('/homepages/41/d92908607/htdocs/wknapp/protected/pages/404.php');
				$maincontents=ob_get_clean();
			}
			break;		
	}
	if($loc && $page!='403' && $page!='404'){
		header ('HTTP/1.1 301 Moved permanently.');
		header ("Location: http://wknapp.com/$page");
	}
	$response=<<<END
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="http://wknapp.com/css/wknapptest.css"/>$extra
  <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
  <script type="text/javascript" src="http://wknapp.com/js/md5.js"></script>
  <script type="text/javascript" src="http://wknapp.com/js/wknapp.js"></script>
   <title>
    $title
</title>
 </head>
 <body$bodyclass>

END;
	//the header on the top of the page
	if($page!='glossarypup')$response.= '<div class="header">William H. Knapp III</div>';
	$response.=<<<END
  <div class="content">
    $bannerimage
    <div class="navbar">
      <div class="nav">
        <ul>
          <li><a href="http://wknapp.com/">Home</a></li>
          <li><a href="http://wknapp.com/teaching">Teaching</a></li>
          <li><a href="http://wknapp.com/classes">Classes</a></li>
          <li><a href="http://wknapp.com/research">Research</a></li>
          <li><a href="http://wknapp.com/files/websafeCV.pdf">Vita</a></li>
          <li><a href="http://wknapp.com/mailform">Email Me!</a></li>
          <li>
END;
	if(isset($_SESSION['ID'])){//they're logged in present a button to log out
		$response.='<a href="http://wknapp.com/logout">Log Out</a></li>';
    }
	else{//they're not logged in, present a button to do so.
		$response.='<a href="http://wknapp.com/login">Log In</a></li>';
	}
  $response.=<<<END
        </ul>
      </div>
    </div>
    <div class="main">
      $maincontents
    </div>
    <div class="sidebar">
      $sidecontents
    </div>
  </div>
  <div class="footer">
    William H. Knapp III, Ph.D.<br>
    Evergreen Park, IL 60805
  </div>
 </body>
</html>
END;
echo $response;
}

function hwfeedback($type){
	switch($type){
		case 'correct':
			return ' <span class="correct">Great job!</span>';
		case 'incorrect':
			return ' <span class="incorrect">Whoops! This is incorrect.</span>';
		case 'noans':
			return ' <span class="incorrect">Please submit an answer!</span>';
		case 'grade':
			return ' <span class="correct">Your work will be graded soon!</span>';
		default:
			return '';
	}
}

function spacecleaner ($text, $chartocleanaround="", $spacereplacement=" "){
	//let's turn nonbreaking spaces into a single space
	$regex_pattern="/&nbsp;|&#0160;|&#xa0;/i";
	$text = preg_replace($regex_pattern, " ", $text);
	
	//let's turn breaks into a single space
	$regex_pattern="/<\s*br\s*\/\s*>/";
	$text = preg_replace($regex_pattern, " ", $text);
	
	//let's turn tabs, newlines, and form feeds into a single space
	$regex_pattern="/\t|\r|\n|\f|\v/";
	$text = preg_replace($regex_pattern, " ", $text);
	
	//let's turn all runs of white space into a single space
	$regex_pattern="/\s{2,}/";
	$text = preg_replace($regex_pattern, " ", $text);
	
	//let's get rid of beginning and trailing spaces
	$regex_pattern="/^\s|\s$/";
	$text = preg_replace($regex_pattern, "", $text);
	
	//get rid of spaces around $chartocleanaround
	if($chartocleanaround!==""){
		$regex_pattern="/\s*$chartocleanaround\s*/";
		$text = preg_replace($regex_pattern, "$chartocleanaround", $text);
		//get rid of any runs of the $chartocleanround
		$regex_pattern="/$chartocleanaround+/";
		$text = preg_replace($regex_pattern, "$chartocleanaround", $text);
	}
	
	//replace remaining spaces with $spacereplacement
	if($spacereplacement!==" "){
		$regex_pattern="/\s+/";
		$text = preg_replace($regex_pattern, "$spacereplacement", $text);
	}
	
	return $text;
}

function regexchecker($stringtocheck, $checkfor){
//echo "<p>We're in regexchecker checking $stringtocheck for $checkfor.</p>";
	$getsomething=FALSE;
	switch($checkfor){
		case 'username':
			$regex="/^[a-zA-Z0-9][a-zA-Z0-9_-]+[a-zA-Z0-9]$/";
			break;
		case 'id'://don't call this, just use is_numeric
			$regex="/^[1-9][0-9]*$/";
			break;
		case 'md5':
			$regex="/^[a-f0-9]{32}$/i";
			break;
		case 'taboo':
			$regex="/(fuck)|(viagra)|(cialis)|(ass[\-\s]*hole)|(cock[\-\s]*sucker)|(god[\-\s]*damn*)|(shit)|(pussy)|(cunt)/i";
			break;
		case 'email':
			//replace spaces at beginning or end
			$stringtocheck=preg_replace("/^\s+|\s+$/", "", $stringtocheck);
			$regex="/^(([^:@\s]+)@((?:(?:[a-z\d]+|(?:%[\dA-F][\dA-F])+)(?:(?:-*(?:[a-z\d]+|(?:%[\dA-F][\dA-F])+))*)?)(?:\.(?:(?:[a-z\d]+|(?:%[\dA-F][\dA-F])+)(?:(?:-*(?:[a-z\d]+|(?:%[\dA-F][\dA-F])+))*)?))+))$/i"; //not perfect but should be good enough
			$getsomething='all';
			break;
		case 'sehiremail':
			//replace spaces at beginning or end
			$stringtocheck=preg_replace("/^\s+|\s+$/", "", $stringtocheck);
			$regex="/^[a-zA-Z]+.?[a-zA-Z]+@[a-zA-Z]*.?sehir.edu.tr$/";
			$getsomething='all';
			break;
		case 'homework':
			//replace spaces at beginning or end
			$stringtocheck=preg_replace("/^\s+|\s+$/", "", $stringtocheck);
			$regex='/^([a-zA-Z]+)(\d+)(.*)$/';
			$getsomething='all';
			break;
		case 'authenticationcode':
			$regex="#/([a-zA-Z\d]{10})/authenticate#";
			$getsomething=1;
			break;
		case 'resetpasswordcode':
			$regex="#/([a-zA-Z\d]{10})/resetpassword#";
			$getsomething=1;
			break;
		case 'salt':
			$regex="/^[a-f\d]{10}$/";
			break;
		case 'scientific':
			$regex="/^(\d*\.\d+)(?:[eE](-?\d+))?$/";
			$getsomething='all';
			break;
		default:
			return FALSE;//if we call this without a defined checkfor return false
	}

	//echo "<p>We're in regexchecker checking $stringtocheck for $regex and get something is $getsomething.</p>";

	if(preg_match($regex,$stringtocheck,$matches)){
		 if($getsomething=='all')return $matches;
		 elseif($getsomething)return $matches[$getsomething];
		 else return TRUE;
	}
	//if we're here something went wrong so return FALSE
	return FALSE;
}

function setsessionvariables($userinfo){
	$_SESSION['ID']=$userinfo['ID'];
	$_SESSION['Email']=$userinfo['Email'];
	$_SESSION['FirstName']=$userinfo['FirstName'];
	$_SESSION['LastName']=$userinfo['LastName'];
}

function verifypassword($salt, $saltedpassword, $savedpassword){
return ($saltedpassword==(md5($salt.$savedpassword)))? true : false;
}

function createquestionform($questions, $page, $post, $path, $deadline){//returns the form for display on the page
$time=time();
$response='';
//Start the forms and the javascript appropriately
if(!$post){//We didn't post anything so make sure they sign off that the work is there and set up javascript validation
	$response=<<<END
<p class="question">
	By checking the box below, you certify that the answers you will submit here represent your own work.</br>
	<input type="checkbox" id="honesty" name="honesty" class="bumpright" onclick="certifywork()"><span id="honesty.error" class="incorrect"></span></br>
</p>
<form id="work" class="nodisplay" action="http://wknapp.com/$page" onSubmit="return validateform()" method="post">
	<input type="hidden" id="certified" name="certified" value="0">
END;
	$javascript=<<<END

<script type="text/javascript">
<!--
	function validateform(){
		var errors=0;
		document.getElementById('honesty.error').innerHTML = '';
		if(!document.getElementById('honesty').checked){
			document.getElementById('honesty.error').innerHTML = ' In order to submit your work, you must certify that it is yours.';
			errors++;
		}

END;
}
else{
$columnheadings="time\tip\temail\tlast\tfirst\thonest";
$correctanswers="na\tna\tna\tna\tna\t1";
$studentanswers.="$time\t".((isset($_SERVER['REMOTE_ADDR']))? $_SERVER['REMOTE_ADDR'] : 'na');
$studentanswers.="\t".((isset($_SESSION['Email']))? $_SESSION['Email'] : 'na');
$studentanswers.="\t".((isset($_SESSION['LastName']))? $_SESSION['LastName'] : 'na');
$studentanswers.="\t".((isset($_SESSION['FirstName']))? $_SESSION['FirstName'] : 'na');
$studentanswers.="\t".((isset($post['certified']))? $post['certified'] : '0');
$pointsreceived=0;
$response.=<<<END
<form id="work" class="inline" method="post">
END;
}
//loop through the questions and answers creating the form, javascript, or grade summary components as necessary
$essayqs=0;
$qnum=0;
foreach($questions as $q){
	$qnum++;
	$qbody='';
	if(!$post){//answers weren't submitted so we need to start the javascript code
		$javascript.=<<<END

		document.getElementById('q$qnum.error').innerHTML = '';
END;
	}
	switch($q['type']){
		case 'radio':
			$onum=0;
			$oerrors=0;
			if(!$post){//answers weren't submitted so we need to start the javascript code
				$javascript.=<<<END

		if(
END;
			}
			foreach($q['options'] as $o){
				$onum++;
				$obody='</br>';
				$id='q'.$qnum.'o'.$onum;
				$checked='';
				if(!$post){//answers weren't submitted
					//raise an error if none of the options were checked use the && at the end to catch everything in the loop
						//and then end with "true" to close it properly
					$javascript.=<<<END
!document.getElementById('$id').checked && 
END;
				}
				else{//answers were submitted
					if($onum===1)$columnheadings.="\tq$qnum";
					$answer=$o['answer'];//will be true or false
					if($answer)$correctanswers.="\t$onum";
					$checked=(isset($post["q$qnum"]))? $post["q$qnum"] : false;
					if($checked==$onum)$studentanswers.="\t$onum";
					$correct=(($answer && $checked==$onum)||(!$answer && $checked!=$onum));
					if(!$correct)$oerrors++;
					if($q['feedback']===true){//give feedback on each answer
						if($answer && $correct)$obody='<span class="correct"> Great job!</span></br>';
						elseif($answer && !$correct)$obody='<span class="incorrect">  This is the right answer.</span></br>';
						elseif(!$correct) $obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
					}
					$checked=($checked==$onum)? 'checked' : '';
				}
				$qbody.="\n\t\t<input type=\"radio\" id=\"$id\" name=\"q$qnum\" class=\"bumpright\" value=\"$onum\" $checked>".$o['option'].$obody;
			}//end option loop
			if(!$post){//answers weren't submitted so we need to end the javascript code
				$qbody="<span id=\"q$qnum.error\" class=\"incorrect\"></span></br>$qbody";
				$javascript.=<<<END
true){
			document.getElementById('q$qnum.error').innerHTML = ' Please select an answer.';
			errors++;
		}

END;
			}
			else{//answers were submitted, since this is a radio type question they have to pick the right answer to get a point
				if(!$oerrors){
					$pointsreceived++;
					$qbody='<span class="correct"> Great job! +1 point</span></br>'.$qbody;
				}
				else{
					if($q['feedback']===false) $qbody='<span class="incorrect"> Whoops! This is incorrect.</span></br>'.$qbody;//don't need to give feedback unless we're not giving individual feedback
					else  $qbody='</br>'.$qbody;
				}
			}
		break;
		case 'check':
			$onum=0;
			$oerrors=0;
			if(!$post){//answers weren't submitted so we need to start the javascript code
				$javascript.=<<<END

		if(
END;
			}
			foreach($q['options'] as $o){
				$onum++;
				$obody='</br>';
				$id='q'.$qnum.'o'.$onum;
				$checked='';
				if(!$post){//answers weren't submitted
					$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
					//raise an error if none of the options were checked use the && at the end to catch everything in the loop
						//and then end with "true" to close it properly
					$javascript.=<<<END
!document.getElementById('$id').checked && 
END;
				}
				else{//answers were submitted
					$columnheadings.="\t$id";
					$answer=$o['answer'];
					if($answer)$correctanswers.="\t1";
					else $correctanswers.="\t0";
					$checked=isset($post[$id]);
					if($checked===false)$studentanswers.="\t0";
					else $studentanswers.="\t1";
					$correct=(($answer && $checked)||(!$answer && !$checked));//the student got the answer right
					if(!$correct)$oerrors++;
					if($q['feedback']===true){//give feedback on each answer
						if($correct)$obody='<span class="correct"> Great job!</span></br>';
						else{
							if($answer) $obody='<span class="incorrect"> Whoops! This should have been selected.</span></br>';
							else $obody='<span class="incorrect"> Whoops! This should not have been selected.</span></br>';
						}
					}
					$checked=($checked)? 'checked' : '';
				}
				$qbody.="\n\t\t<input type=\"checkbox\" id=\"$id\" name=\"$id\" class=\"bumpright\" $checked>".$o['option'].$obody;
			}//end option loop
			if(!$post){//answers weren't submitted so we need to end the javascript code
				$qbody="<span id=\"q$qnum.error\" class=\"incorrect\"></span></br>$qbody";
				$javascript.=<<<END
true){
			document.getElementById('q$qnum.error').innerHTML = ' Please select an answer.';
			errors++;
		}
END;
			}
			else{//answers were submitted, since this is a radio type question they have to pick the right answer to get a point
				if(!$oerrors){
					$pointsreceived++;
					$qbody='<span class="correct"> Great job! +1 point</span></br>'.$qbody;
				}
				elseif($oerrors<$onum){
					$pointsreceived+=($onum-$oerrors)/$onum;
					$qbody="<span class=\"incorrect\"> You made $oerrors mistakes. +".($onum-$oerrors)/$onum.' points</span></br>'.$qbody;
				}
				else{
					if($q['feedback']===false) $qbody='<span class="incorrect"> Whoops! This is incorrect.</span></br>'.$qbody;//don't need to give feedback unless we're not giving individual feedback
					else  $qbody='</br>'.$qbody;
				}
			}
		break;
		case 'short':
			$id='q'.$qnum;
			$checked='';
			if(!$post){//answers weren't submitted so we need to create the javascript code
				$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
				$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
END;
			}
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? stripslashes($post[$id]) : false;
				if($checked===false)$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				$correct=($answer==$checked);
				//lets check to see if the value is a float. If it isn't we don't need to do anyfurther checking.
				if(!$correct && is_float($answer) && $answer!=0 && is_numeric($checked) && abs(($answer-$checked)/$answer)<.001){
					$correct=TRUE;//need to check further
				}
				if($q['feedback']===true && !$correct){//give the correct answer if it's wrong and we want feedback
					$obody="<span class=\"incorrect\"> Whoops! The correct answer is $answer.</span></br>";
				}
				elseif(!$correct){//let them know the answer was wrong, but don't give them the right answer
					$obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
				}
				else{//they got it right
					$pointsreceived++;
					$obody='<span class="correct"> Great job! +1 point</span></br>';
				}
				if($checked===0)$checked='0';
				$checked=($checked==='0' || $checked)? "value=\"$checked\"" : '';
			}
			$qbody.="</br>\n\t\t<input type=\"text\" id=\"$id\" name=\"$id\" class=\"bumpright\" $checked>".$obody;
		break;
		case 'mcans':
			$id='q'.$qnum;
			$checked='';
			if(!$post){//answers weren't submitted so we need to create the javascript code
				$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
				$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
		else if(!validate('mcans', clean('whiteborder', document.getElementById('$id').value))){
			document.getElementById('$id.error').innerHTML = ' Please use the letter corresponding to the option you choose.';
			errors++;
		}
END;
			}
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? $post[$id] : false;
				if($checked===false)$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				$correct=($answer==$checked);
				if($q['feedback']===true && !$correct){//give the correct answer if it's wrong and we want feedback
					$obody="<span class=\"incorrect\"> Whoops! The correct answer is $answer.</span></br>";
				}
				elseif(!$correct){//let them know the answer was wrong, but don't give them the right answer
					$obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
				}
				else{//they got it right
					$pointsreceived++;
					$obody='<span class="correct"> Great job! +1 point</span></br>';
				}
				$checked=($checked)? "value=\"$checked\"" : '';
			}
			$qbody.="<input type=\"text\" id=\"$id\" name=\"$id\" size=\"1\" maxlength=\"1\" $checked>".$obody;
		break;
		case 'essay':
			$essayqs++;
			$id='q'.$qnum;
			$checked='';
			if(!$post){//answers weren't submitted so we need to create the javascript code
				$obody=" <span id=\"$id.error\" class=\"incorrect\"></span></br>";
				$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
END;
			}
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? stripslashes($post[$id]) : '';
				if($checked==='')$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				if($q['feedback']===true){//give the correct answer if we're giving them feedback
					$obody=" <span class=\"correct\"> Your essay will be graded soon. I'm looking for an answer that contains the following points:</span> $answer</br>";
				}
				else{//let them know the answer was wrong, but don't give them the right answer
					$obody=' <span class="correct"> Your essay will be graded soon.</span></br>';
				}
			}
			$qbody.="$obody\n\t\t<textarea id=\"$id\" name=\"$id\" class=\"bumpright\" rows=\"4\" cols=\"60\">$checked</textarea>";
		break;
	}//end switch on question type
	$response.="\n\t<p>$qnum. ".$q['question'].$qbody.'</p>';
}//end question loop

//end the form and javascript appropriately
if(!$post){
	$response.=<<<END
	<input type="submit" value="Submit"><span id="submit.error" class="incorrect"></span>
END;
	$javascript.=<<<END

		document.getElementById('submit.error').innerHTML = '';
		if(errors){
			document.getElementById('submit.error').innerHTML = ' Please fix the errors and try again.';
			return false;
		}
		else return true;
	}
//-->
</script>
END;
	$response=$javascript.$response;
}
else{
	if($essayqs)$response="\n<p>Essays will be graded soon. Scores, reported below, do not reflect performance on essays and may change once the essays are graded.</p>$response";
	$percent=$pointsreceived/($qnum-$essayqs)*100;
	$extra=($deadline<$time)? " Since this was submitted after the deadline, you will only receive half credit for this submission. If you scored higher previously, you will retain your highest score." : '';
	if($percent==100)$response="\n<p class=\"correct\">Awesome, you got everything right! 100%$extra</p>$response";
	elseif($percent>80)$response="\n<p class=\"correct\">Congratulations, you got $percent% correct!$extra</p>$response";
	elseif($percent>60)$response="\n<p class=\"correct\">You got $percent% correct.$extra</p>$response";
	else $response="\n<p class=\"incorrect\">You got $percent% correct. If you're having trouble, please <a href=\"http://wknapp.com/mailform\">contact</a> me!</p>$response";
	$percent=($deadline<$time)? $percent/2 : $percent;
	$response.=<<<END
</form>
END;
	//only need to check and write the files if we posted data
	$filename="$page.dat";
	if(file_exists("$path/$filename")){//the file exists. open it, put the pointer at the end, and write studentanswers
		$towrite="$studentanswers\n";
		$handle=fopen("$path/$filename", 'a');
		if($handle){
			if(!fwrite($handle, $towrite))emailadminerror('homeworkfwritefileexists', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
			fclose($handle);
		}
		else emailadminerror('homeworknohandlefileexists', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
	}
	else{//the file doesn't exits open it and write the columnnheadings, studentanswers and correctanswers
		$towrite="$columnheadings\n$correctanswers\n$studentanswers\n";
		$handle=fopen("$path/$filename", 'w');
		if($handle){
			if(!fwrite($handle, $towrite))emailadminerror('homeworkfwritefiledoesnotexist', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
			fclose($handle);
		}
		else emailadminerror('homeworknohandlefiledoesnotexist', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
	}
}

//Put the page number into proper format for mysql
$page=regexchecker('homework', $page);
if($page[2]<10)$page=$page[1].'0'.$page[2];
else $page=$page[1].$page[2];

if(isset($_SESSION['ID'])){
	$existingscore = checkforscore($_SESSION['ID'], $page);
	if($post){
		if(insertscore($_SESSION['ID'], $page, $percent)){
			if($existingscore){
				if($existingscore>=$percent)$response="<p>So far, your highest score is $existingscore%.</p>$response";
				else $response='<p>Your score has been successfully updated.</p>'.$response;
			}
			else $response='<p>Your score has been successfully recorded.</p>'.$response;
		}
		else $response='<p>There was a problem recording your score. Please use the back button and try again. If this problem persists, please <a href="http://wknapp.com/mailform">email</a> me.</p>'.$response;
	}
	else{
		$existingscore=($existingscore)? "Your highest score on this assignment is $existingscore%. Feel free to practice some more. Only your best score on the assignment will count towards your grade." : "You have not yet completed this assignment.";
		$response= '<p>Hi, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>'.$existingscore.'</p>'.$response;
	}
}
else{
	if($post)$response= '<p class="incorrect">Because you were not logged in, your score was not recorded for credit.</p>'.$response;
	else $response= '<p class="incorrect">You will not be able to submit your work for credit, because you are not logged in. <a href="http://wknapp.com/login">Log in</a>!</p>'.$response;
}

return $response;
}

function createmultiplesubmitquestionform($questions, $page, $post, $path, $deadline){//returns the form for display on the page
$time=time();
$response='';
//Start the forms and the javascript appropriately
$temp=(isset($post['certified']))? 'checked' : '';

$response.=<<<END
<p class="question">
	By checking the box below, you certify that the answers you will submit here represent your own work.</br>
	<input type="checkbox" id="honesty" name="honesty" class="bumpright" onclick="certifywork()" $temp><span id="honesty.error" class="incorrect"></span></br>
</p>
END;
$temp=($temp=='checked')? 'inline' : 'nodisplay';
$response.=<<<END

<form id="work" class="$temp" action="http://wknapp.com/$page" onSubmit="return validateform()" method="post">
	<input type="hidden" id="certified" name="certified" 
END;
$temp=($temp=='inline')? '1' : '0';
$response.="value=\"$temp\">";
$javascript=<<<END

<script type="text/javascript">
<!--
	function validateform(){
		var errors=0;
		document.getElementById('honesty.error').innerHTML = '';
		if(!document.getElementById('honesty').checked){
			document.getElementById('honesty.error').innerHTML = ' In order to submit your work, you must certify that it is yours.';
			errors++;
		}

END;

if($post){
$columnheadings="time\tip\temail\tlast\tfirst\thonest";
$correctanswers="na\tna\tna\tna\tna\t1";
$studentanswers.="$time\t".((isset($_SERVER['REMOTE_ADDR']))? $_SERVER['REMOTE_ADDR'] : 'na');
$studentanswers.="\t".((isset($_SESSION['Email']))? $_SESSION['Email'] : 'na');
$studentanswers.="\t".((isset($_SESSION['LastName']))? $_SESSION['LastName'] : 'na');
$studentanswers.="\t".((isset($_SESSION['FirstName']))? $_SESSION['FirstName'] : 'na');
$studentanswers.="\t".((isset($post['certified']))? $post['certified'] : '0');
$pointsreceived=0;
}
//loop through the questions and answers creating the form, javascript, or grade summary components as necessary
$essayqs=0;
$qnum=0;
foreach($questions as $q){
	$qnum++;
	$qbody='';
	$javascript.=<<<END

		document.getElementById('q$qnum.error').innerHTML = '';
END;
	switch($q['type']){
		case 'radio':
			$onum=0;
			$oerrors=0;
			$javascript.=<<<END

		if(
END;
			foreach($q['options'] as $o){
				$onum++;
				$obody='</br>';
				$id='q'.$qnum.'o'.$onum;
				$checked='';
				$javascript.=<<<END
!document.getElementById('$id').checked && 
END;
				if($post){//answers were submitted
					if($onum===1)$columnheadings.="\tq$qnum";
					$answer=$o['answer'];//will be true or false
					if($answer)$correctanswers.="\t$onum";
					$checked=(isset($post["q$qnum"]))? $post["q$qnum"] : false;
					if($checked==$onum)$studentanswers.="\t$onum";
					$correct=(($answer && $checked==$onum)||(!$answer && $checked!=$onum));
					if(!$correct)$oerrors++;
					if($q['feedback']===true){//give feedback on each answer
						if($answer && $correct)$obody='<span class="correct"> Great job!</span></br>';
						elseif($answer && !$correct)$obody='<span class="incorrect">  This is the right answer.</span></br>';
						elseif(!$correct) $obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
					}
					$checked=($checked==$onum)? 'checked' : '';
				}
				$qbody.="\n\t\t<input type=\"radio\" id=\"$id\" name=\"q$qnum\" class=\"bumpright\" value=\"$onum\" $checked>".$o['option'].$obody;
			}//end option loop
			if(!$post)$qbody="<span id=\"q$qnum.error\" class=\"incorrect\"></span></br>$qbody";
			else{//answers were submitted, since this is a radio type question they have to pick the right answer to get a point
				if(!$oerrors){
					$pointsreceived++;
					$qbody='<span class="correct"> Great job! +1 point</span></br>'.$qbody;
				}
				else{
					if($q['feedback']===false) $qbody='<span class="incorrect"> Whoops! This is incorrect.</span></br>'.$qbody;//don't need to give feedback unless we're not giving individual feedback
					else  $qbody='</br>'.$qbody;
				}
			}
			$javascript.=<<<END
true){
			document.getElementById('q$qnum.error').innerHTML = ' Please select an answer.';
			errors++;
		}

END;
		break;
		case 'check':
			$onum=0;
			$oerrors=0;
			$javascript.=<<<END

		if(
END;
			foreach($q['options'] as $o){
				$onum++;
				$obody='</br>';
				$id='q'.$qnum.'o'.$onum;
				$checked='';
				//raise an error if none of the options were checked use the && at the end to catch everything in the loop
						//and then end with "true" to close it properly
				$javascript.=<<<END
!document.getElementById('$id').checked && 
END;
				if(!$post)$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
				else{//answers were submitted
					$columnheadings.="\t$id";
					$answer=$o['answer'];
					if($answer)$correctanswers.="\t1";
					else $correctanswers.="\t0";
					$checked=isset($post[$id]);
					if($checked===false)$studentanswers.="\t0";
					else $studentanswers.="\t1";
					$correct=(($answer && $checked)||(!$answer && !$checked));//the student got the answer right
					if(!$correct)$oerrors++;
					if($q['feedback']===true){//give feedback on each answer
						if($correct)$obody='<span class="correct"> Great job!</span></br>';
						else{
							if($answer) $obody='<span class="incorrect"> Whoops! This should have been selected.</span></br>';
							else $obody='<span class="incorrect"> Whoops! This should not have been selected.</span></br>';
						}
					}
					$checked=($checked)? 'checked' : '';
				}
				$qbody.="\n\t\t<input type=\"checkbox\" id=\"$id\" name=\"$id\" class=\"bumpright\" $checked>".$o['option'].$obody;
			}//end option loop
			if(!$post)$qbody="<span id=\"q$qnum.error\" class=\"incorrect\"></span></br>$qbody";
			else{//answers were submitted, since this is a radio type question they have to pick the right answer to get a point
				if(!$oerrors){
					$pointsreceived++;
					$qbody='<span class="correct"> Great job! +1 point</span></br>'.$qbody;
				}
				elseif($oerrors<$onum){
					$pointsreceived+=($onum-$oerrors)/$onum;
					$qbody="<span class=\"incorrect\"> You made $oerrors mistakes. +".($onum-$oerrors)/$onum.' points</span></br>'.$qbody;
				}
				else{
					if($q['feedback']===false) $qbody='<span class="incorrect"> Whoops! This is incorrect.</span></br>'.$qbody;//don't need to give feedback unless we're not giving individual feedback
					else  $qbody='</br>'.$qbody;
				}
			}
			$javascript.=<<<END
true){
			document.getElementById('q$qnum.error').innerHTML = ' Please select an answer.';
			errors++;
		}
END;
		break;
		case 'short':
			$id='q'.$qnum;
			$checked='';
			$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
END;
			if(!$post)$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? stripslashes($post[$id]) : false;
				if($checked===false)$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				$correct=($answer==$checked);
				//lets check to see if the value is a float. If it isn't we don't need to do anyfurther checking.
				if(!$correct && is_float($answer) && $answer!=0 && is_numeric($checked) && abs(($answer-$checked)/$answer)<.001){
					$correct=TRUE;//need to check further
				}
				if($q['feedback']===true && !$correct){//give the correct answer if it's wrong and we want feedback
					$obody="<span class=\"incorrect\"> Whoops! The correct answer is $answer.</span></br>";
				}
				elseif(!$correct){//let them know the answer was wrong, but don't give them the right answer
					$obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
				}
				else{//they got it right
					$pointsreceived++;
					$obody='<span class="correct"> Great job! +1 point</span></br>';
				}
				if($checked===0)$checked='0';
				$checked=($checked==='0' || $checked)? "value=\"$checked\"" : '';
			}
			$qbody.="</br>\n\t\t<input type=\"text\" id=\"$id\" name=\"$id\" class=\"bumpright\" $checked>".$obody;
		break;
		case 'mcans':
			$id='q'.$qnum;
			$checked='';
			$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
		else if(!validate('mcans', clean('whiteborder', document.getElementById('$id').value))){
			document.getElementById('$id.error').innerHTML = ' Please use the letter corresponding to the option you choose.';
			errors++;
		}
END;
			if(!$post)$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? $post[$id] : false;
				if($checked===false)$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				$correct=($answer==$checked);
				if($q['feedback']===true && !$correct){//give the correct answer if it's wrong and we want feedback
					$obody="<span class=\"incorrect\"> Whoops! The correct answer is $answer.</span></br>";
				}
				elseif(!$correct){//let them know the answer was wrong, but don't give them the right answer
					$obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
				}
				else{//they got it right
					$pointsreceived++;
					$obody='<span class="correct"> Great job! +1 point</span></br>';
				}
				$checked=($checked)? "value=\"$checked\"" : '';
			}
			$qbody.="<input type=\"text\" id=\"$id\" name=\"$id\" size=\"1\" maxlength=\"1\" $checked>".$obody;
		break;
		case 'essay':
			$essayqs++;
			$id='q'.$qnum;
			$checked='';
			$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
END;
			if(!$post)$obody=" <span id=\"$id.error\" class=\"incorrect\"></span></br>";
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? stripslashes($post[$id]) : '';
				if($checked==='')$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				if($q['feedback']===true){//give the correct answer if we're giving them feedback
					$obody=" <span class=\"correct\"> Your essay will be graded soon. I'm looking for an answer that contains the following points:</span> $answer</br>";
				}
				else{//let them know the answer was wrong, but don't give them the right answer
					$obody=' <span class="correct"> Your essay will be graded soon.</span></br>';
				}
			}
			$qbody.="$obody\n\t\t<textarea id=\"$id\" name=\"$id\" class=\"bumpright\" rows=\"4\" cols=\"60\">$checked</textarea>";
		break;
	}//end switch on question type
	$response.="\n\t<p>$qnum. ".$q['question'].$qbody.'</p>';
}//end question loop

//end the form and javascript appropriately
$response.=<<<END
	<input type="submit" value="Submit"><span id="submit.error" class="incorrect"></span>
</form>
END;
$javascript.=<<<END

		document.getElementById('submit.error').innerHTML = '';
		if(errors){
			document.getElementById('submit.error').innerHTML = ' Please fix the errors and try again.';
			return false;
		}
		else return true;
	}
//-->
</script>
END;
$response=$javascript.$response;

if($post){
	if($essayqs)$response="\n<p>Essays will be graded soon. Scores, reported below, do not reflect performance on essays and may change once the essays are graded.</p>$response";
	$percent=$pointsreceived/($qnum-$essayqs)*100;
	$extra=($deadline<$time)? " Since this was submitted after the deadline, you will only receive half credit for this submission. If you scored higher previously, you will retain your highest score." : '';
	if($percent==100)$response="\n<p class=\"correct\">Awesome, you got everything right! 100%$extra</p>$response";
	elseif($percent>80)$response="\n<p class=\"correct\">Congratulations, you got $percent% correct!$extra</p>$response";
	elseif($percent>60)$response="\n<p class=\"correct\">You got $percent% correct.$extra</p>$response";
	else $response="\n<p class=\"incorrect\">You got $percent% correct. If you're having trouble, please <a href=\"http://wknapp.com/mailform\">contact</a> me!</p>$response";
	$percent=($deadline<$time)? $percent/2 : $percent;
	
	//only need to check and write the files if we posted data
	$filename="$page.dat";
	if(file_exists("$path/$filename")){//the file exists. open it, put the pointer at the end, and write studentanswers
		$towrite="$studentanswers\n";
		$handle=fopen("$path/$filename", 'a');
		if($handle){
			if(!fwrite($handle, $towrite))emailadminerror('homeworkfwritefileexists', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
			fclose($handle);
		}
		else emailadminerror('homeworknohandlefileexists', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
	}
	else{//the file doesn't exits open it and write the columnnheadings, studentanswers and correctanswers
		$towrite="$columnheadings\n$correctanswers\n$studentanswers\n";
		$handle=fopen("$path/$filename", 'w');
		if($handle){
			if(!fwrite($handle, $towrite))emailadminerror('homeworkfwritefiledoesnotexist', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
			fclose($handle);
		}
		else emailadminerror('homeworknohandlefiledoesnotexist', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
	}
}

//Put the page number into proper format for mysql
$page=regexchecker('homework', $page);
if($page[2]<10)$page=$page[1].'0'.$page[2];
else $page=$page[1].$page[2];


$due=getdate($deadline+60*60*6);//add on 6 hours for difference between turkey and eastern
$ampm=($due["hours"]<12)? 'a.m.':'p.m.';
if($time>$deadline)$response='<p class="incorrect">'.sprintf("This homework was due on %s, %s %d at %02d:%02d %s Turkish time. Late submissions receive half credit.",$due["weekday"],$due["month"],$due["mday"], $due["hours"]%12, $due["minutes"], $ampm, $due["hours"]%12, $due["minutes"], $ampm).'</p>'.$response;
else $response='<p>'.sprintf("This homework is due on %s, %s %d at %02d:%02d %s Turkish time. Late submissions will receive half credit.",$due["weekday"],$due["month"],$due["mday"], $due["hours"]%12, $due["minutes"], $ampm, $due["hours"]%12, $due["minutes"], $ampm).'</p>'.$response;


if(isset($_SESSION['ID'])){
	$existingscore = checkforscore($_SESSION['ID'], $page);
	if($post){
		if(insertscore($_SESSION['ID'], $page, $percent)){
			if($existingscore){
				if($existingscore>=$percent)$response="<p>So far, your highest score is $existingscore%.</p>$response";
				else $response='<p>Your score has been successfully updated.</p>'.$response;
			}
			else $response='<p>Your score has been successfully recorded.</p>'.$response;
		}
		else $response='<p>There was a problem recording your score. Please use the back button and try again. If this problem persists, please <a href="http://wknapp.com/mailform">email</a> me.</p>'.$response;
	}
	else{
		$existingscore=($existingscore)? "Your highest score on this assignment is $existingscore%. Feel free to practice some more. Only your best score on the assignment will count towards your grade." : "You have not yet completed this assignment.";
		$response= '<p>Hi, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>'.$existingscore.'</p>'.$response;
	}
}
else{
	if($post)$response= '<p class="incorrect">Because you were not logged in, your score was not recorded for credit.</p>'.$response;
	else $response= '<p class="incorrect">You will not be able to submit your work for credit, because you are not logged in. <a href="http://wknapp.com/login">Log in</a>!</p>'.$response;
}

return $response;
}

function createtestquestionform($questions, $page, $post, $path, $deadline, $silent=false){//returns the form for display on the page
$time=time();
$response='';

//Start the forms and the javascript appropriately
$response.=<<<END

<form id="work" action="http://wknapp.com/$page" onSubmit="return validateform()" method="post">
END;

$javascript=<<<END

<script type="text/javascript">
<!--
	function validateform(){
		var errors=0;

END;

if(!$post){
$columnheadings="time\tip\temail\tlast\tfirst";
$correctanswers="na\tna\tna\tna\tna\t1";
$studentanswers.="$time\t".((isset($_SERVER['REMOTE_ADDR']))? $_SERVER['REMOTE_ADDR'] : 'na');
$studentanswers.="\t".((isset($_SESSION['Email']))? $_SESSION['Email'] : 'na');
$studentanswers.="\t".((isset($_SESSION['LastName']))? $_SESSION['LastName'] : 'na');
$studentanswers.="\t".((isset($_SESSION['FirstName']))? $_SESSION['FirstName'] : 'na');
$pointsreceived=0;
}
//loop through the questions and answers creating the form, javascript, or grade summary components as necessary
$essayqs=0;
$qnum=0;
foreach($questions as $q){
	$qnum++;
	$qbody='';
	$javascript.=<<<END

		document.getElementById('q$qnum.error').innerHTML = '';
END;
	switch($q['type']){
		case 'radio':
			$onum=0;
			$oerrors=0;
			$javascript.=<<<END

		if(
END;
			foreach($q['options'] as $o){
				$onum++;
				$obody='</br>';
				$id='q'.$qnum.'o'.$onum;
				$checked='';
				$javascript.=<<<END
!document.getElementById('$id').checked && 
END;
				if($post){//answers were submitted
					if($onum===1)$columnheadings.="\tq$qnum";
					$answer=$o['answer'];//will be true or false
					if($answer)$correctanswers.="\t$onum";
					$checked=(isset($post["q$qnum"]))? $post["q$qnum"] : false;
					if($checked==$onum)$studentanswers.="\t$onum";
					$correct=(($answer && $checked==$onum)||(!$answer && $checked!=$onum));
					if(!$correct)$oerrors++;
					if($q['feedback']){//give feedback on each answer
						if($answer && $correct)$obody='<span class="correct"> Great job!</span></br>';
						elseif($answer && !$correct)$obody='<span class="incorrect">  This is the right answer.</span></br>';
						elseif(!$correct) $obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
					}
					$checked=($checked==$onum)? 'checked' : '';
				}
				$qbody.="\n\t\t<input type=\"radio\" id=\"$id\" name=\"q$qnum\" class=\"bumpright\" value=\"$onum\" $checked>".$o['option'].$obody;
			}//end option loop
			if(!$post)$qbody="<span id=\"q$qnum.error\" class=\"incorrect\"></span></br>$qbody";
			else{//answers were submitted, since this is a radio type question they have to pick the right answer to get a point
				if(!$oerrors){
					$pointsreceived++;
					$qbody='<span class="correct"> Great job! +1 point</span></br>'.$qbody;
				}
				else{
					if(!$q['feedback']) $qbody='<span class="incorrect"> Whoops! This is incorrect.</span></br>'.$qbody;//don't need to give feedback unless we're not giving individual feedback
					else  $qbody='</br>'.$qbody;
				}
			}
			$javascript.=<<<END
true){
			document.getElementById('q$qnum.error').innerHTML = ' Please select an answer.';
			errors++;
		}

END;
		break;
		case 'check':
			$onum=0;
			$oerrors=0;
			$javascript.=<<<END

		if(
END;
			foreach($q['options'] as $o){
				$onum++;
				$obody='</br>';
				$id='q'.$qnum.'o'.$onum;
				$checked='';
				//raise an error if none of the options were checked use the && at the end to catch everything in the loop
						//and then end with "true" to close it properly
				$javascript.=<<<END
!document.getElementById('$id').checked && 
END;
				if(!$post)$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
				else{//answers were submitted
					$columnheadings.="\t$id";
					$answer=$o['answer'];
					if($answer)$correctanswers.="\t1";
					else $correctanswers.="\t0";
					$checked=isset($post[$id]);
					if($checked===false)$studentanswers.="\t0";
					else $studentanswers.="\t1";
					$correct=(($answer && $checked)||(!$answer && !$checked));//the student got the answer right
					if(!$correct)$oerrors++;
					if($q['feedback']){//give feedback on each answer
						if($correct)$obody='<span class="correct"> Great job!</span></br>';
						else{
							if($answer) $obody='<span class="incorrect"> Whoops! This should have been selected.</span></br>';
							else $obody='<span class="incorrect"> Whoops! This should not have been selected.</span></br>';
						}
					}
					$checked=($checked)? 'checked' : '';
				}
				$qbody.="\n\t\t<input type=\"checkbox\" id=\"$id\" name=\"$id\" class=\"bumpright\" $checked>".$o['option'].$obody;
			}//end option loop
			if(!$post)$qbody="<span id=\"q$qnum.error\" class=\"incorrect\"></span></br>$qbody";
			else{//answers were submitted, since this is a radio type question they have to pick the right answer to get a point
				if(!$oerrors){
					$pointsreceived++;
					$qbody='<span class="correct"> Great job! +1 point</span></br>'.$qbody;
				}
				elseif($oerrors<$onum){
					$pointsreceived+=($onum-$oerrors)/$onum;
					$qbody="<span class=\"incorrect\"> You made $oerrors mistakes. +".($onum-$oerrors)/$onum.' points</span></br>'.$qbody;
				}
				else{
					if(!$q['feedback']) $qbody='<span class="incorrect"> Whoops! This is incorrect.</span></br>'.$qbody;//don't need to give feedback unless we're not giving individual feedback
					else  $qbody='</br>'.$qbody;
				}
			}
			$javascript.=<<<END
true){
			document.getElementById('q$qnum.error').innerHTML = ' Please select an answer.';
			errors++;
		}
END;
		break;
		case 'short':
			$id='q'.$qnum;
			$checked='';
			$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
END;
			if(!$post)$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? stripslashes($post[$id]) : false;
				if($checked===false)$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				$correct=($answer==$checked);
				//lets check to see if the value is a float. If it isn't we don't need to do anyfurther checking.
				if(!$correct && is_float($answer) && $answer!=0 && is_numeric($checked) && abs(($answer-$checked)/$answer)<.001){
					$correct=TRUE;//need to check further
				}
				if($q['feedback'] && !$correct){//give the correct answer if it's wrong and we want feedback
					$obody="<span class=\"incorrect\"> Whoops! The correct answer is $answer.</span></br>";
				}
				elseif(!$correct){//let them know the answer was wrong, but don't give them the right answer
					$obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
				}
				else{//they got it right
					$pointsreceived++;
					$obody='<span class="correct"> Great job! +1 point</span></br>';
				}
				if($checked===0)$checked='0';
				$checked=($checked==='0' || $checked)? "value=\"$checked\"" : '';
			}
			$qbody.="</br>\n\t\t<input type=\"text\" id=\"$id\" name=\"$id\" class=\"bumpright\" $checked>".$obody;
		break;
		case 'mcans':
			$id='q'.$qnum;
			$checked='';
			$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
		else if(!validate('mcans', clean('whiteborder', document.getElementById('$id').value))){
			document.getElementById('$id.error').innerHTML = ' Please use the letter corresponding to the option you choose.';
			errors++;
		}
END;
			if(!$post)$obody="<span id=\"$id.error\" class=\"incorrect\"></span></br>";
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? $post[$id] : false;
				if($checked===false)$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				$correct=($answer==$checked);
				if($q['feedback'] && !$correct){//give the correct answer if it's wrong and we want feedback
					$obody="<span class=\"incorrect\"> Whoops! The correct answer is $answer.</span></br>";
				}
				elseif(!$correct){//let them know the answer was wrong, but don't give them the right answer
					$obody='<span class="incorrect"> Whoops! This is incorrect.</span></br>';
				}
				else{//they got it right
					$pointsreceived++;
					$obody='<span class="correct"> Great job! +1 point</span></br>';
				}
				$checked=($checked)? "value=\"$checked\"" : '';
			}
			$qbody.="<input type=\"text\" id=\"$id\" name=\"$id\" size=\"1\" maxlength=\"1\" $checked>".$obody;
		break;
		case 'essay':
			$essayqs++;
			$id='q'.$qnum;
			$checked='';
			$javascript.=<<<END

		if(clean('whiteborder', document.getElementById('$id').value)==''){
			document.getElementById('$id.error').innerHTML = ' Please enter an answer.';
			errors++;
		}
END;
			if(!$post)$obody=" <span id=\"$id.error\" class=\"incorrect\"></span></br>";
			else{//answers were submitted
				$columnheadings.="\t$id";
				$answer=$q['options'];//options for short questions contains the correct answer
				if($answer)$correctanswers.="\t$answer";
				else $correctanswers.="\tna";
				$checked=(isset($post[$id]))? stripslashes($post[$id]) : '';
				if($checked==='')$studentanswers.="\tna";
				else $studentanswers.="\t$checked";
				if($q['feedback']){//give the correct answer if we're giving them feedback
					$obody=" <span class=\"correct\"> Your essay will be graded soon. I'm looking for an answer that contains the following points:</span> $answer</br>";
				}
				else{//let them know the answer was wrong, but don't give them the right answer
					$obody=' <span class="correct"> Your essay will be graded soon.</span></br>';
				}
			}
			$qbody.="$obody\n\t\t<textarea id=\"$id\" name=\"$id\" class=\"bumpright\" rows=\"4\" cols=\"60\">$checked</textarea>";
		break;
	}//end switch on question type
	$response.="\n\t<p>$qnum. ".$q['question'].$qbody.'</p>';
}//end question loop

//end the form and javascript appropriately
if($post){
	$response.='</form>';
	$javascript.=<<<END
		return false;
	}
//-->
</script>
END;
}
else{
	$response.=<<<END
	<input type="submit" value="Submit"><span id="submit.error" class="incorrect"></span>
</form>
END;
	$javascript.=<<<END

		document.getElementById('submit.error').innerHTML = '';
		if(errors){
			document.getElementById('submit.error').innerHTML = ' Please fix the errors and try again.';
			return false;
		}
		else return true;
	}
//-->
</script>
END;
}
$response=$javascript.$response;

if($post){
	if($essayqs)$response="\n<p>Scores reported below do not reflect performance on essays.</p>$response";
	$percent=$pointsreceived/($qnum-$essayqs)*100;
	$extra=($deadline<$time)? " Since the test is over, your score will not be recorded." : '';
	if($percent==100)$response="\n<p class=\"correct\">Awesome, you got everything right! 100%$extra</p>$response";
	elseif($percent>80)$response="\n<p class=\"correct\">Congratulations, you got $percent% correct!$extra</p>$response";
	elseif($percent>60)$response="\n<p class=\"correct\">You got $percent% correct.$extra</p>$response";
	else $response="\n<p class=\"incorrect\">You got $percent% correct. If you're having trouble, please <a href=\"http://wknapp.com/mailform\">contact</a> me!</p>$response";
	$percent=($deadline<$time)? $percent/2 : $percent;
	
	//only need to check and write the files if silent is true
	if($silent){
		$filename="$page.dat";
		if(file_exists("$path/$filename")){//the file exists. open it, put the pointer at the end, and write studentanswers
			$towrite="$studentanswers\n";
			$handle=fopen("$path/$filename", 'a');
			if($handle){
				if(!fwrite($handle, $towrite))emailadminerror('homeworkfwritefileexists', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
				fclose($handle);
			}
			else emailadminerror('homeworknohandlefileexists', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
		}
		else{//the file doesn't exits open it and write the columnnheadings, studentanswers and correctanswers
			$towrite="$columnheadings\n$correctanswers\n$studentanswers\n";
			$handle=fopen("$path/$filename", 'w');
			if($handle){
				if(!fwrite($handle, $towrite))emailadminerror('homeworkfwritefiledoesnotexist', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
				fclose($handle);
			}
			else emailadminerror('homeworknohandlefiledoesnotexist', array('path'=>$path, 'filename'=>$filename, 'towrite'=>$towrite));
		}
	}
}

//Put the page number into proper format for mysql
if(isset($_SESSION['ID'])){
	$existingscore = checkforscore($_SESSION['ID'], $page);
	if($silent && $post && $deadline>$time && !$existingscore){//only include test scores once
		if(insertscore($_SESSION['ID'], $page, $percent)){
			return "<p>Your score has been successfully recorded.</p><p>Please continue to the <a href=\"http://wknapp.com/$silent\">next page</a>.</p>";
		}
		else  return "<p class=\"error\">There was a problem recording your score.<p><p class=\"error\">Please contact your teacher immediately.</p><p>Do not begin the <a href=\"http://wknapp.com/$silent\">next page</a> until your instructor resolves the error.</p>";
	}
	else{
		$existingscore=($existingscore)? "Your recorded score was $existingscore%. Feel free to practice some more. Only your first score on the test counts towards your grade." : "You have not yet completed this exam.";
		$response= '<p>Hi, '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</p><p>'.$existingscore.'</p>'.$response;
	}
}
else{
	if($post)$response= '<p class="incorrect">Because you were not logged in, your score was not recorded for credit.</p>'.$response;
	else $response= '<p class="incorrect">You will not be able to submit your work for credit, because you are not logged in. <a href="http://wknapp.com/login">Log in</a>!</p>'.$response;
}

if($silent) $response="Please continue to the <a href=\"http://wknapp.com/$silent\">next page</a>.";

return $response;
}

function checkforscore($userid, $assignment){
	usedb();
	$userid=mysql_real_escape_string($userid);
	$assignment=mysql_real_escape_string($assignment);
	$rows=($result=mysql_query($sql="SELECT * FROM scores WHERE UserID='$userid' AND Assignment='$assignment' LIMIT 1"))? mysql_num_rows($result) : 0;
	mysql_close();
	if($rows===1){//we have a score
		$assoc=mysql_fetch_assoc($result);
		return $assoc['Score'];
	}
	else return false;//there is no score on this assignment yet
}

function insertscore($userid, $assignment, $score){
	usedb();
	$userid=mysql_real_escape_string($userid);
	$assignment=mysql_real_escape_string($assignment);
	$score=mysql_real_escape_string($score);
	$time=mysql_real_escape_string(time());
	$result=mysql_query($sql="INSERT INTO scores (UserID, Assignment, Score, NumAttempts, FirstAttemptTime, LastAttemptTime) VALUES ($userid, '$assignment', $score, 1, $time, $time) ON DUPLICATE KEY UPDATE NumAttempts=NumAttempts+1, LastAttemptTime=$time, Score=GREATEST(Score, $score)");
	mysql_close();
	if($result)return true;//we inserted the score
	else{
		emailadminerror('insertscore', array('sql'=>$sql));
		return false;//there is no score on this assignment yet
	}
}

function timeago($timeposted){ //used in r8rfuncs.stories.php
	$needsans="s";
	$timedif=time()-$timeposted;
	if($timedif==1)$needsans="";
	if($timedif<60) return "$timedif second$needsans ago";
	$timedif=floor($timedif/60);
	if($timedif==1)$needsans="";
	if($timedif<60)return "$timedif minute$needsans ago";
	$timedif=floor($timedif/60);
	if($timedif==1)$needsans="";
	if($timedif<24)return "$timedif hour$needsans ago";
	$timedif=floor($timedif/24);
	if($timedif==1)$needsans="";
	return "$timedif day$needsans ago";
}
?>