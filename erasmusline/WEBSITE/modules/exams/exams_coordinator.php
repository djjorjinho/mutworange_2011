<?php
	$examStr = "";
	
	if(isset($_POST["studentHomeA"])) {
		//HOME EXAMS Finish
		if(isset($_POST["exid"])) {
			$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
											FROM `coursespereducperinst`, `erasmusstudent`, `homecoursestoerasmus`
											WHERE \"".$_POST["exid"]."\" = `erasmusstudent`.`users_email` 
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `homecoursestoerasmus`.`isRequested` = 1
											AND `homecoursestoerasmus`.`homeanswer` IS NULL 
											 ");
			
			for($i = 0; $i < count($items); $i++){
				if(isset($_POST["exama".$i])) {
					setAnswerHome($items[$i], $_POST["exid"], $_POST["exama".$i], $edb);
				}
			}
		} 
		$str = '<form name="input" action="'.submitExams().'" method="post">Your answer is submited.<br />';
		$str .= '<input type="submit" name="" value=" Back " /></form>';
		$this->pageTpl->assign('exams', $examStr.$str);
		
	} else if(isset($_POST["studentHostA"])) {
		//HOST EXAMS Finish
		if(isset($_POST["exid"])) {
			$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
											FROM `coursespereducperinst`, `erasmusstudent`, `homecoursestoerasmus`
											WHERE \"".$_POST["exid"]."\" = `erasmusstudent`.`users_email` 
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `homecoursestoerasmus`.`isRequested` = 1
											AND `homecoursestoerasmus`.`hostanswer` IS NULL 
											 ");
			
			for($i = 0; $i < count($items); $i++){
				if(isset($_POST["exama".$i])) {
					setAnswerHost($items[$i], $_POST["exid"], $_POST["exama".$i], $edb);
				}
			}
		} 
		$str = '<form name="input" action="'.submitExams().'" method="post">Your answer is submited.<br />';
		$str .= '<input type="submit" name="" value=" Back " /></form>';
		$this->pageTpl->assign('exams', $examStr.$str);
		
	} else if(isset($_POST["studentHost"])) {
		//HOST EXAMS list per Student
		if(!isset($_POST["sexam"])) {
			$str = '<form name="input" action="'.submitExams().'" method="post">Please select student!<br />';
			$str .= '<input type="submit" name="" value=" Back " /></form>';
			$this->pageTpl->assign('exams', $examStr.$str);
		} else {
			$str = '<form name="input" action="'.submitExams().'" method="post">'.getForeignStudentInfo($_POST["sexam"], $edb).
									"<br />has requested to take this exams:<br />";
			$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
											FROM `coursespereducperinst`, `erasmusstudent`, `homecoursestoerasmus`
											WHERE \"".$_POST["sexam"]."\" = `erasmusstudent`.`users_email` 
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `homecoursestoerasmus`.`isRequested` = 1
											AND `homecoursestoerasmus`.`hostanswer` IS NULL 
											 ");
			if(!$items) {
				$str .= "There are no requested exams.<br />";
				$str .= '<input type="submit" name="" value=" Back " /></form>';
			} else {
				$str .= '<input type="hidden" name="exid" value="'.$_POST["sexam"].'" />';
				for($i = 0; $i < count($items); $i++){
					$str .= '&emsp;<b>'.$items[$i].'</b><br />&emsp;&emsp;<input type="radio" name="exama'.$i.'" value="1" /> Accept<br />
												&emsp;&emsp;<input type="radio" name="exama'.$i.'" value="2" /> Deny <br />';
				}
				$str .= '<input type="submit" name="studentHostA" value=" Submit " /></form>';
			}
			
			
			$this->pageTpl->assign('exams', $examStr.$str);
		}
	} else if(isset($_POST["studentHome"])) {
		//HOME EXAMS list per Student
		if(!isset($_POST["sexam"])) {
			$str = '<form name="input" action="'.submitExams().'" method="post">Please select student!<br />';
			$str .= '<input type="submit" name="" value=" Back " /></form>';
			$this->pageTpl->assign('exams', $examStr.$str);
		} else {
			$str = '<form name="input" action="'.submitExams().'" method="post">'.getOurStudentInfo($_POST["sexam"], $edb).
									"<br />has requested to take this exams:<br />";
			$items = $edb->getColumnAsArray("SELECT `coursespereducperinst`.`courseName`  
											FROM `coursespereducperinst`, `erasmusstudent`, `homecoursestoerasmus`
											WHERE \"".$_POST["sexam"]."\" = `erasmusstudent`.`users_email` 
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `homecoursestoerasmus`.`isRequested` = 1
											AND `homecoursestoerasmus`.`homeanswer` IS NULL 
											 ");
			if(!$items) {
				$str .= "There are no requested exams.<br />";
				$str .= '<input type="submit" name="" value=" Back " /></form>';
			} else {
				$str .= '<input type="hidden" name="exid" value="'.$_POST["sexam"].'" />';
				for($i = 0; $i < count($items); $i++){
					$str .= '&emsp;<b>'.$items[$i].'</b><br />&emsp;&emsp;<input type="radio" name="exama'.$i.'" value="1" /> Accept<br />
												&emsp;&emsp;<input type="radio" name="exama'.$i.'" value="2" /> Deny <br />';
				}
				$str .= '<input type="submit" name="studentHomeA" value=" Submit " /></form>';
			}
			
			
			$this->pageTpl->assign('exams', $examStr.$str);
		}
	} else if(isset($_POST["maine"])) {
		
		//HOME Student list
		if($_POST["maine"] == "home") {
			$str = '<form name="input" action="'.submitExams().'" method="post">';
			$items = $edb->getColumnAsArray("SELECT users_email FROM `users`, `erasmusstudent`, `homecoursestoerasmus` 
											WHERE `users`.`email` = `erasmusstudent`.`homeCoordinatorId` AND 
											`users`.`email` = \"".$_SESSION['id']."\" AND
											`erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId` AND
											`homecoursestoerasmus`.`isRequested` = 1 AND
											homecoursestoerasmus.homeanswer IS NULL GROUP BY users_email");
			if(!$items) {
				$str .= "There are no students on Erasmus from this university that requested to take their home exams.<br />";
				$str .= '<input type="submit" name="" value=" Back " /></form>';
				$this->pageTpl->assign('exams', $examStr.$str);
			} else {
				for($i = 0; $i < count($items); $i++){
					$str .= '&emsp;<input type="radio" name="sexam" value="'.$items[$i].'" /> '.getOurStudentInfo($items[$i], $edb).' <br />';
				}
				$str .= '<input type="submit" name="studentHome" value=" View " /></form>';
				$this->pageTpl->assign('exams', $examStr.$str);
			}
			
			
		} else if ($_POST["maine"] == "host") {
			//HOST Student list
			$str = '<form name="input" action="'.submitExams().'" method="post">';
			$items = $edb->getColumnAsArray("SELECT users_email FROM `users`, `erasmusstudent`, `homecoursestoerasmus`
											WHERE `users`.`email` = `erasmusstudent`.`hostCoordinatorId` AND 
											`users`.`email` = \"".$_SESSION['id']."\" AND
											`erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId` AND
											`homecoursestoerasmus`.`isRequested` = 1 AND
											homecoursestoerasmus.hostanswer IS NULL GROUP BY users_email");
			if(!$items) {
				$str .= "There are no students on Erasmus to this university that requested to take their home exams.<br />";
				$str .= '<input type="submit" name="" value=" Back " /></form>';
				$this->pageTpl->assign('exams', $examStr.$str);
			} else {
				for($i = 0; $i < count($items); $i++){
					$str .= '&emsp;<input type="radio" name="sexam" value="'.$items[$i].'" /> '.getForeignStudentInfo($items[$i], $edb).' <br />';
				}
				$str .= '<input type="submit" name="studentHost" value=" View " /></form>';
				$this->pageTpl->assign('exams', $examStr.$str);
			}
			
			
		} else if ($_POST["maine"] == "hostA") {
			//HOST Student approved list
			$str = '<form name="input" action="'.submitExams().'" method="post">Approved students on Erasmus in our university:<br />';
			$items = $edb->retrieve("SELECT `users_email`, `coursespereducperinst`.`courseName`  
											FROM `coursespereducperinst`, `homecoursestoerasmus`, `users`, `erasmusstudent` 
											WHERE `users`.`email` = `erasmusstudent`.`hostCoordinatorId` AND 
											`users`.`email` = \"".$_SESSION['id']."\"
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `homecoursestoerasmus`.`isRequested` = 1
											AND `homecoursestoerasmus`.`homeanswer` = 1 
											AND `homecoursestoerasmus`.`hostanswer` = 1 
											");
			if(!$items) {
				$str .= "None<br />";
			} else {
				$tmpSt = "";
				for($i = 0; $i < count($items); $i++){
					if($tmpSt != $items[$i]['users_email']) {
						$tmpSt = $items[$i]['users_email'];
						$str .= '&emsp; '.getForeignStudentInfo($items[$i]['users_email'], $edb).' <br />';
					} 
					$str .= '&emsp;&emsp;&emsp;&bull;'.$items[$i]['courseName'].' <br />';
				}
			}
			
			$str .= '<input type="submit" name="" value=" Back " /></form>';
			$this->pageTpl->assign('exams', $examStr.$str);
		} else if ($_POST["maine"] == "homeA") {
			//HOME Student approved list
			$str = '<form name="input" action="'.submitExams().'" method="post">Approved students on Erasmus in foreign universities:<br />';
			$items = $edb->retrieve("SELECT `users_email`, `coursespereducperinst`.`courseName`  
											FROM `coursespereducperinst`, `homecoursestoerasmus`, `users`, `erasmusstudent` 
											WHERE `users`.`email` = `erasmusstudent`.`homeCoordinatorId` AND 
											`users`.`email` = \"".$_SESSION['id']."\"
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `homecoursestoerasmus`.`isRequested` = 1
											AND `homecoursestoerasmus`.`homeanswer` = 1 
											AND `homecoursestoerasmus`.`hostanswer` = 1 
											");
			if(!$items) {
				$str .= "None<br />";
			} else {
				$tmpSt = "";
				for($i = 0; $i < count($items); $i++){
					if($tmpSt != $items[$i]['users_email']) {
						$tmpSt = $items[$i]['users_email'];
						$str .= '&emsp; '.getOurStudentInfo($items[$i]['users_email'], $edb).' <br />';
					}
					$str .= '&emsp;&emsp;&emsp;&bull;'.$items[$i]['courseName'].' <br />';
				}
			}
			
			$str .= '<input type="submit" name="" value=" Back " /></form>';
			$this->pageTpl->assign('exams', $examStr.$str);
		}
	} else {
		//MAIN
		$str = '<form name="input" action="'.submitExams().'" method="post">';
		$str .= '<input type="radio" name="maine" value="home" /> View requests from our students<br />';
		$str .= '<input type="radio" name="maine" value="host" /> View requests from foreign students<br />';
		$str .= '<input type="radio" name="maine" value="homeA" /> View our approved students<br />';
		$str .= '<input type="radio" name="maine" value="hostA" /> View approved foreign students<br />';
		$str .= '<div class="TRdiv"><input type="submit" name="" value=" Submit " /> </div></form>';
		$this->pageTpl->assign('exams', $examStr.$str);
		
	}
	
	function getOurStudentInfo($mail, $edb) {
		$itemsS = $edb->retrieveOne("SELECT * FROM `users` WHERE `email` = \"".$mail."\"");
		$itemsSU = $edb->retrieveOne("SELECT `institutions`.`instName` FROM `erasmusstudent`, `institutions` 
									WHERE `erasmusstudent`.`users_email` = \"".$mail."\" AND `erasmusstudent`.`hostInstitutionId` = `institutions`.`instEmail`");
		$retStr = "<b>".$itemsS['firstName'] . " " . $itemsS['familyName'] ."</b> at <b>".$itemsSU['instName']."</b> with email: <b>".$mail."</b>";
		return $retStr;
	}
	
	function getForeignStudentInfo($mail, $edb) {
		$itemsS = $edb->retrieveOne("SELECT * FROM `users` WHERE `email` = \"".$mail."\"");
		$itemsSU = $edb->retrieveOne("SELECT `institutions`.`instName` FROM `erasmusstudent`, `institutions` 
									WHERE `erasmusstudent`.`users_email` = \"".$mail."\" AND `erasmusstudent`.`homeInstitutionId` = `institutions`.`instEmail`");
		$retStr = "<b>".$itemsS['firstName'] . " " . $itemsS['familyName']."</b> from <b>".$itemsSU['instName']."</b> with email: <b>".$mail."</b>";
		return $retStr;
	}
	
	function setAnswerHost($examName, $stId, $a, $edb) {
		$edb->execute("UPDATE `coursespereducperinst`, `homecoursestoerasmus`, `erasmusstudent`
											SET `homecoursestoerasmus`.`hostanswer` = ".$a."
											WHERE \"".$stId."\" = `erasmusstudent`.`users_email` 
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `coursespereducperinst`.`courseName` = \"".$examName."\"
											 "); 
											 
		$infox = new InfoxController();
					
		$institution = $edb->retrieveOne("SELECT * 
								FROM erasmusstudent
								WHERE users_email = \"" .$stId. "\"" );
		$values = array(
		'set' => "`homecoursestoerasmus`.`hostanswer` = " . $a ,
		'where' => "\"".$stId."\" = `erasmusstudent`.`users_email` 
					AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
					AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
					AND `coursespereducperinst`.`courseName` = \"".$examName."\""
		);
		$methods = array('forms:updateToDb');
		$table = array('`coursespereducperinst`, `homecoursestoerasmus`, `erasmusstudent`');
		$data = array($values);
		$idInst = $institution['homeInstitutionId'];					
		$infox->dataTransfer($methods, $table, $data, $idInst);
	}
	
	function setAnswerHome($examName, $stId, $a, $edb) {
	
		$edb->execute("UPDATE `coursespereducperinst`, `homecoursestoerasmus`, `erasmusstudent`
											SET `homecoursestoerasmus`.`homeanswer` = ".$a."
											WHERE \"".$stId."\" = `erasmusstudent`.`users_email` 
											AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
											AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
											AND `coursespereducperinst`.`courseName` = \"".$examName."\"
											 "); 
											 
		$infox = new InfoxController();
					
		$institution = $edb->retrieveOne("SELECT * 
								FROM erasmusstudent
								WHERE users_email = \"" . $stId . "\"" );
		$values = array(
		'set' => "`homecoursestoerasmus`.`homeanswer` = " . $a ,
		'where' => "\"".$stId."\" = `erasmusstudent`.`users_email` 
					AND `erasmusstudent`.`studentId` = `homecoursestoerasmus`.`erasmusId`
					AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`
					AND `coursespereducperinst`.`courseName` = \"".$examName."\""
		);
		$methods = array('forms:updateToDb');
		$table = array('`coursespereducperinst`, `homecoursestoerasmus`, `erasmusstudent`');
		$data = array($values);
		$idInst = $institution['hostInstitutionId'];					
		$infox->dataTransfer($methods, $table, $data, $idInst);
	}
	
?>