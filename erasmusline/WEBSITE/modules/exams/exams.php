<?php 
	$edb = PlonkWebsite::getDB();
	$eid = 23;
	$today = date("Y-m-d");  
	
	$items = $edb->retrieveOne("SELECT `erasmusstudent`.`startDate`, `erasmusstudent`.`endDate`  
		FROM `users`, `erasmusstudent`
		WHERE `users`.`userID` = " . $eid .  
		" AND `users`.`email` = `erasmusstudent`.`users_email`"); 
	if ($today < $items['startDate']) {
		$this->pageTpl->assign('exams', "You should go to your erasmus to be able to get your home exams!");
	} else if($today > $items['endDate']) {
		$this->pageTpl->assign('exams', "It's too late to get your home exams!");
	} else {
		$items = $edb->retrieveOne("SELECT `homecoursestoerasmus`.`erasmusId` FROM `users`, `homecoursestoerasmus`,`erasmusstudent` 
								WHERE `users`.`userId` = ".$eid." AND
								`users`.`email` = `erasmusstudent`.`users_email` AND 
								`erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId` ");
		if(!$items) {
			$items = $edb->getColumnAsArray("SELECT `erasmusstudent`.`studentId`
								FROM `coursespereducperinst`, `users`, `erasmusstudent`, `educationperinstitute`, `institutions`
								WHERE `users`.`userID` = " . $eid .  
								" AND `users`.`email` = `erasmusstudent`.`users_email` 
								AND `erasmusstudent`.`educationPerInstId` = `educationperinstitute`.`educationPerInstId`
								AND `educationperinstitute`.`studyId` = `coursespereducperinst`.`educationId`
								AND `erasmusstudent`.`homeInstitutionId` = `institutions`.`instEmail`
								AND `institutions`.`instEmail` = `coursespereducperinst`.`institutionId`"); 
			$items2 = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseId`
								FROM `coursespereducperinst`, `users`, `erasmusstudent`, `educationperinstitute`, `institutions`
								WHERE `users`.`userID` = " . $eid .  
								" AND `users`.`email` = `erasmusstudent`.`users_email` 
								AND `erasmusstudent`.`educationPerInstId` = `educationperinstitute`.`educationPerInstId`
								AND `educationperinstitute`.`studyId` = `coursespereducperinst`.`educationId`
								AND `erasmusstudent`.`homeInstitutionId` = `institutions`.`instEmail`
								AND `institutions`.`instEmail` = `coursespereducperinst`.`institutionId`"); 
			for($i = 0; $i < count($items); $i++) {
				$edb->execute("INSERT INTO `homecoursestoerasmus` (`erasmusId`, `courseId`) VALUES (" . $items[$i] . ", " . $items2[$i] . ")");
			}
		}
		
		$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
								FROM `coursespereducperinst`, `users`, `erasmusstudent`, `homecoursestoerasmus`
								WHERE `users`.`userID` = " . $eid .  
								" AND `users`.`email` = `erasmusstudent`.`users_email` 
								AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
								AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`");
		
		$str = '<form name="input" action="'.submitExams().'" method="post">Make a request to take:<br />';
		for($i = 0; $i < count($items); $i++) {
			$str .= '<input type="checkbox" name="exam'.$i.'" value="'.$items[$i].'" /> '.$items[$i].' <br />' ;
		}
		$str .= '<input type="submit" value="Submit" /></form>';
		
		
		for($i = 0; $i < count($items); $i++) {
			if(isset($_POST["exam".$i])) {
				$str .= "The request to take the exam of ".$_POST["exam".$i]." was sended!<br />";
				$edb->update("homecoursestoerasmus, coursespereducperinst", array('isRequested' => '1'), "`coursespereducperinst`.`courseName` = \"" . $_POST["exam".$i] . "\" AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`");
			}
		}
		$this->pageTpl->assign('exams', $str);
	}
	
	function submitExams() {
	}
?>