<?php
/*
echo "<p>William is updating this page. It'll be back up soon.</p>";
return true;
*/

$sql=<<<END
SELECT scores.Assignment, scores.Score, users.Email
FROM scores
LEFT JOIN users
ON users.ID = scores.UserID
ORDER BY scores.UserID ASC, scores.Assignment ASC;
END;
usedb();
$rows=($result=mysql_query($sql))? mysql_num_rows($result) : 0;
mysql_close();
if($rows>1){//we have data from students
	$row=0;
	$cols=0;
	$assignments=array();
	$assignments[0] = 'Name';
	$print=array();
	$print[0] = true;
	$last='notanemail';
	$studtab='';
	$response=<<<END
<h3>Statistics Grades</h3>
	<div class="bumpright">
	<table class="classtab">
		<tr class="classhead bold">
			<th>Email</th>
END;
	while($assoc=mysql_fetch_assoc($result)){
		//echo 'Email ' . $assoc['Email'] . ' Assignment ' . $assoc['Assignment'] . ' Score ' . $assoc['Score'] . '<br>';
		$thecount=count($assignments);
		if($last!=$assoc['Email']){//it's a new row so print the name for the user or william
			$row++;
			//before setting cols to 0 make sure we got through everything
			if($last!='notanemail' && $cols++<$thecount-1){
				while($cols<$thecount){
					if($assignments[$cols] && $print[$cols]===true)$studtab.='<td class="error">0</td>';
					$cols++;
				}
			}
			$cols=0;
			if($last!='notanemail')$studtab.="\n\t\t</tr>";
			
			//If it's me or the student in question, we can print their name
			if(isset($_SESSION['Email']) && ($_SESSION['Email']==$assoc['Email'] || isadmin())){
				if($_SESSION['Email']==$assoc['Email'])$studtab.="\n\t\t<tr class=\"bold\">\n\t\t\t<td>".$assoc['Email'].'</td>';
				else $studtab.="\n\t\t<tr>\n\t\t\t<td>".$assoc['Email'].'</td>';
			}
			else{//it's someone who shouldn't know who this student is
				$studtab.="\n\t\t<tr>\n\t\t\t<td>Student $row</td>";
			}
		}
		$cols++;
		$assignment=regexchecker($assoc['Assignment'], 'homework');
		if($row===1){//we're on our first row, so we need headings
			$assignments[$cols]=$assoc['Assignment'];
			$print[$cols]=true;
			if($assignment[1]=='statshw'){
				$response.='<th>H.W.<br>'.intval($assignment[2])."</th>";
			}
			else if($assignment[1]=='statstest'){
				if($assignment[3]=='')$response.='<th>Test<br>'.intval($assignment[2])."</th>";
				else $print[$cols]=false;
			}
			else $print[$cols]=false;
		}
		
		//echo $assignment[0] . ' Assoc Score ' . $assoc['Score'] . ' Strpos Score ' . $score . '<br>';
		//if the assignments match and we're supposed to print, print it
		if($assignment[0]==$assignments[$cols] && $print[$cols]){//print the score
			$score=(strpos(($assoc['Score']),'.'))? sprintf('%.2f',$assoc['Score']) : $assoc['Score'];
			if($score==0)$studtab.="<td class=\"error\">$score</td>";
			else $studtab.="<td>$score</td>";
			//echo $assignment[0] . ' Assoc Score ' . $assoc['Score'] . ' Strpos Score ' . $score . '<br>';
		}
		else if($assignment[0]!=$assignments[$cols]){//we have a later assignment or a missing one
			while($assignment[0]!=$assignments[$cols] && $cols<$thecount-1){//go through the assignments until we find our match
				if($print[$cols++]===true)$studtab.='<td class="error">0</td>';//print any 0's for missing assignments
			}
			if($print[$cols]===true){
				$score=(strpos(($assoc['Score']),'.'))? sprintf('%.2f',$assoc['Score']) : $assoc['Score'];
				if($score==0)$studtab.="<td class=\"error\">$score</td>";
				else $studtab.="<td>$score</td>";
			}
		}
		$last=$assoc['Email'];
	}//end while
	//fill in any remaining holes in the student table where there is an assignment
	while($cols<$thecount-1){
		if($assignments[$cols++] && $print[$cols++]===true)$studtab.='<td class="error">0</td>';
	}
	
	//close join and close the tables
	$response.="\n\t\t</tr>\n\t\t\t".$studtab."\n\t\t</tr>\n\t</table>\n\t</div>\n";
}
else{//we didn't get the data from the students so tell user to try again and send an emailadminerror
	$response='There was a problem getting the grades. Please try again and alert Dr. Knapp if the problem continues.';
	emailadminerror('no grades', array('sql'=>$sql));
}
echo $response;

?>