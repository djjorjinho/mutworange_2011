<?php

class Course_matchingController extends PlonkController {

	protected $fields = array();
	protected $matches = "";
	protected $matchScores = array();

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
		'freeform',
        'course_matching',
		'matchsuccess'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
	
    );

    /**
     * Assign variables that are main and the same for every view
     * @param	= null
     * @return  = void
     */
    private function mainTplAssigns($pageTitle) {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', $pageTitle);
        $this->mainTpl->assign('pageMeta', '');
		$this->mainTpl->assign('pageJava', '');
		$this->mainTpl->assign('breadcrumb','');
    }
	
	public function showFreeform() {
		$this->mainTplAssigns('Course Matching');
		$this->fillInstitutions();
	}
	
	public function showCourse_matching() {
        // Main Layout
        // assign vars in our main layout tpl

        $this->mainTplAssigns('Course Matching');
		$this->fillInstitutions();
		$this->fillCourses();
    }
	
	public function showMatchsuccess() {
		$this->mainTplAssigns('Course Matched');
		if (PlonkFilter::getPostValue('course') > 0){
			$this->fields = array(
				PlonkFilter::getPostValue('course'),
				PlonkFilter::getPostValue('institution')
			);
			$this->getFromPython($this->fields[0], $this->fields[1]);
			$this->fillCourseDescriptions($this->matches, $this->fields[0]);
			//$this->pageTpl->assign('matches', $this->matches);
		} else{
			$this->fields = array(
				PlonkFilter::getPostValue('Description'),
				PlonkFilter::getPostValue('Name'),
				PlonkFilter::getPostValue('Ects'),
				PlonkFilter::getPostValue('institution')
			);
			$this->getFreeformFromPython($this->fields[0], $this->fields[1], $this->fields[2], $this->fields[3]);
			$this->fillCourseDescriptions($this->matches, -1);
		}
		
	}
	
	private function getFromPython($course, $inst) {
		$result = explode("|", exec("modules\\course_matching\\matcher.py ids $course $inst"));
		$this->matches = $result[0];
		$this->matchScores = explode(",", $result[1]);
	}
	
	private function getFreeformFromPython($desc, $name, $ects, $inst) {
		$desc = '"' . $desc . '"';
		$name = '"' . $name . '"';
		$result = explode("|", exec("modules\\course_matching\\matcher.py spec " . $this->cleanStringForPython($desc) . " $name $ects $inst"));
		$this->matches = $result[0];
		$this->matchScores = explode(",", $result[1]);
	}	
	
	private function cleanStringForPython($string){
		$string = str_replace("\r\n", "", $string);
		return $string;
	}
	
	private function fillCourseDescriptions($ids, $origCourse){
		$courses = array();
		if($ids[0] == "-"){
			$c = array();
			$c["courseId"] = -1;
			$c["courseCode"] = "-";
			$c["courseName"] = $this->fields[1];
			$c["ectsCredits"] = $this->fields[2];
			$c["courseDescription"] = $this->fields[0];
			$courses[] = $c;
		}
		$courses = array_merge($courses, Course_matchingDB::getCoursesForIDs($ids));
		try {
			$this->pageTpl->setIteration('courseDescriptions');
			$i = 0;
			foreach ($courses as $key => $value) {
				$this->pageTpl->assignIteration('cid', $value['courseId']);
				$this->pageTpl->assignIteration('cEcts', $value['ectsCredits']);
				$this->pageTpl->assignIteration('cDesc', $value['courseDescription']);
				if ($i % 2 == 0)
					$this->pageTpl->assignIteration('cClass', 'lightCourseRow');
				else
					$this->pageTpl->assignIteration('cClass', 'darkCourseRow');
				if ($value['courseId'] != $origCourse){
					$strScore = str_split($this->matchScores[$i]*100, 5);
					$this->pageTpl->assignIteration('cName', $value['courseName']);
					$this->pageTpl->assignIteration('cScore', $strScore[0] . "%");
				}
				else
					$this->pageTpl->assignIteration('cName', "Original: " . $value['courseName']);
					$this->pageTpl->assignIteration('cScore', '100%');
				$this->pageTpl->refillIteration('courseDescriptions');
				$i++;
			}
			$this->pageTpl->parseIteration('courseDescriptions');
		} catch (Exception $e) {
			Plonk::dump($e);
		}
	}
	
	private function fillInstitutions() {
		$institutions = Course_matchingDB::getInstitutions();
        try {
            $this->pageTpl->setIteration('iInstitutions');
            foreach ($institutions as $key => $value) {
				$this->pageTpl->assignIteration('institution', '<option value="' . $value['instId'] . '"> ' . $value['instName'] . '</option>');
                $this->pageTpl->refillIteration('iInstitutions');
            }
            $this->pageTpl->parseIteration('iInstitutions');
        } catch (Exception $e) {
            
        }
	}
	
	private function fillCourses()
	{
		$courses = Course_matchingDB::getCourses();
		try{
			$this->pageTpl->setIteration('iCourses');
			foreach ($courses as $key => $value) {
				$this->pageTpl->assignIteration('courses','<option value="' . $value['courseId'] . '"> ' . $value['instName'] . ': ' . $value['courseName'] . ' (' . $value['ectsCredits'] . ' ECTS)</option>');
				$this->pageTpl->refillIteration('iCourses');
			}
			$this->pageTpl->parseIteration('iCourses');
		} catch (Exception $e) {
			
		}
	}

}
