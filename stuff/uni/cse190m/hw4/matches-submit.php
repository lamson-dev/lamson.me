<?php include("top.html"); ?>

<!--  	Son Nguyen
		CSE 190 M
		Homework #4

		TA: Rishi Goutam		
		
		Description: php file of "NerdLuv" online dating site 
		
		this page receive user name from matches.php
		find this user information, match him/her with other singles from singles data (singles.txt)
		then output these matches for user to view
		
-->

<?php

	$queryname = $_GET["name"];

	#get singles information from singles.text
	$singlesdata = file("singles.txt");
	
	#find user information
	foreach ($singlesdata as $single) {
		list($username, $usergender, $userage, $usertype, $useros, $userseekfrom, $userseekto) = explode(",", $single);	
		if ($queryname == $username) {
			break;
		}
	}

	#function to find match
	#this function takes 6 parameters (gender, age, type, os, minage, and maxage of a single)
	#return false if the single does not meet ANY of the user's condition
	#		true if a single is a "match" for the user
	function ismatch($gender, $age, $type, $os, $minage, $maxage) {
		
		global $usergender, $userage, $usertype, $useros, $userseekfrom, $userseekto;
		
		#check if a "match" shares at least one personality type letter in common.
		$isgoodtype = false;
		for ($i = 0; $i < strlen($type); $i++) {
		
			if (strpos($usertype, $type[$i]) !== false) {
				$isgoodtype = true;
				break;
			}
		}			
		
		#check the rest of the user's conditions
		#return false if ANY of these condition does not meet
		if ($usergender == $gender || $useros != $os || 
			$age <= $userseekfrom || $age >= $userseekto || 
			$userage <= $minage || $userage >= $maxage || 
			$isgoodtype == false) {
			
			return false;
		} else {
			return true;
		}
	}

?>

	<div>
		<h1>Matches for <?= $username ?></h1>
	
<?php
	
	#get single information, find out if she/he matches the user
	foreach ($singlesdata as $single) {
		list($mname, $mgender, $mage, $mtype, $mos, $mseekfrom, $mseekto) = explode(",", $single);
		
		if (isMatch($mgender, $mage, $mtype, $mos, $mseekfrom, $mseekto)) {  ?>
					
			<div class="match">
			
				<p><img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/4/user.jpg" 
						alt="photo" /><?= $mname ?></p>
				
				<ul>
					<li><strong>gender:</strong><?= $mgender ?></li>
					<li><strong>age:</strong><?= $mage ?></li>
					<li><strong>type:</strong><?= $mtype ?></li>
					<li><strong>OS:</strong><?= $mos ?></li>
				</ul>
				
			</div>

<?php 
		}
	} ?>
	
		</div> <!-- close main content div -->
	
	
<?php include("bottom.html"); ?>