<?php
	$today = date("Y-m-d");  
	$edb = PlonkWebsite::getDB();
	
	
	$items = $edb->retrieveOne("SELECT `userId` FROM  `users` WHERE `email` = \"".$_SESSION['id']."\" ");
	$eid = $items['userId'];
	
	$items = $edb->retrieveOne("SELECT userLevel FROM users WHERE userId = " . $eid);
	
	if($items['userLevel'] == "Erasmus Coordinator") {
		require_once './modules/exams/exams_coordinator.php';
	} else if($items['userLevel'] == "Student") {	
		$examStr = '<h3>Take exams in your home University</h3>';
		
		$aStr = "";
		
		if(!isset($_POST["manage"])) {
			$str = '<form name="input" action="'.submitExams().'" method="post">';
			$str .= '<input type="submit" name="manage" value=" Manage exams " /></form>';
			$this->pageTpl->assign('exams', $examStr.$str);
		} else {
		
			$items = $edb->retrieveOne("SELECT `erasmusstudent`.`startDate`, `erasmusstudent`.`endDate`  
				FROM `users`, `erasmusstudent`
				WHERE `users`.`userID` = " . $eid .  
				" AND `users`.`email` = `erasmusstudent`.`users_email`");
			if(!$items || ($items['startDate'] == NULL)) {
				$this->pageTpl->assign('exams', $examStr.'You are not Erasmus student.');
			} else if ($today < $items['startDate']) {
				$this->pageTpl->assign('exams', $examStr."You should go to your erasmus to be able to get your home exams!");
			} else if($today > $items['endDate']) {
				$this->pageTpl->assign('exams', $examStr."It's too late to get your home exams!");
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
				
				//approved
				$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
										FROM `coursespereducperinst`, `users`, `erasmusstudent`, `homecoursestoerasmus`
										WHERE `users`.`userID` = " . $eid .  
										" AND `users`.`email` = `erasmusstudent`.`users_email` 
										AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
										AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
										AND `homecoursestoerasmus`.`isRequested` = 1
										AND `homecoursestoerasmus`.`homeanswer` = 1 
										AND `homecoursestoerasmus`.`hostanswer` = 1 ");								
				if($items) {
					$aStr .= 'You are approved to take the following exams:<br />';
					for($i = 0; $i < count($items); $i++) {
						$aStr .= '&emsp; &bull; '.$items[$i].' <br />' ;
					}					
				}
				
				//NOT approved
				$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
										FROM `coursespereducperinst`, `users`, `erasmusstudent`, `homecoursestoerasmus`
										WHERE `users`.`userID` = " . $eid .  
										" AND `users`.`email` = `erasmusstudent`.`users_email` 
										AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
										AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
										AND `homecoursestoerasmus`.`isRequested` = 1
										AND (`homecoursestoerasmus`.`homeanswer` = 2 
										OR `homecoursestoerasmus`.`hostanswer` = 2) ");								
				if($items) {
					$aStr .= 'You are NOT approved to take the following exams:<br />';
					for($i = 0; $i < count($items); $i++) {
						$aStr .= '&emsp; &bull; '.$items[$i].' <br />' ;
					}					
				}
				
				//requested
				$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
										FROM `coursespereducperinst`, `users`, `erasmusstudent`, `homecoursestoerasmus`
										WHERE `users`.`userID` = " . $eid .  
										" AND `users`.`email` = `erasmusstudent`.`users_email` 
										AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
										AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
										AND `homecoursestoerasmus`.`isRequested` = 1 ");									
				if($items) {
					$aStr .= 'You requested to take the following exams:<br />';
					for($i = 0; $i < count($items); $i++) {
						$aStr .= '&emsp; &bull; '.$items[$i].' <br />' ;
					}					
				}
				
				//make request
				$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
										FROM `coursespereducperinst`, `users`, `erasmusstudent`, `homecoursestoerasmus`
										WHERE `users`.`userID` = " . $eid .  
										" AND `users`.`email` = `erasmusstudent`.`users_email` 
										AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
										AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
										AND `homecoursestoerasmus`.`isRequested` IS NULL
										AND `homecoursestoerasmus`.`homeanswer` IS NULL
										AND `homecoursestoerasmus`.`hostanswer` IS NULL ");
				if($items) {
					$str = '<form name="input" action="'.submitExams().'" method="post">Make a request to take:<br />';
					for($i = 0; $i < count($items); $i++) {
						$str .= '&emsp;<input type="checkbox" name="exam'.$i.'" value="'.$items[$i].'" /> '.$items[$i].' <br />' ;
					}
					$str .= '<input type="submit" name="manage" value=" Send Request " /></form>';
					
					//sent requests
					for($i = 0; $i < count($items); $i++) {
						if(isset($_POST["exam".$i])) {
							$str .= "The request to take the exam of ".$_POST["exam".$i]." was sended!<br />";
							$edb->update("homecoursestoerasmus, coursespereducperinst", array('isRequested' => '1'), "`coursespereducperinst`.`courseName` = \"" . $_POST["exam".$i] . "\" AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`");
						}
					}
				} else {
					$str = "There are no exams from your home university that you can request to take.";
				}
				$this->pageTpl->assign('exams', $examStr.$aStr.$str);
			}
		}
	} else {
		$this->pageTpl->assign('exams', "");
	}
	
	function submitExams() {
	}
	
?>