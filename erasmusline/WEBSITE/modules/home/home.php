<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class HomeController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'home',
        'userhome',
        'notify'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
        'login',
        'logout'
    );
    private $id;

    /**
     * Assign variables that are main and the same for every view
     * @param	= null
     * @return  = void
     */
    private function mainTplAssigns($pageTitle) {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', $pageTitle);
        $this->mainTpl->assign('pageMeta', '');
    }

    public function showHome() {
        // Main Layout
        // assign vars in our main layout tpl

        if (PlonkSession::exists('id')) {

            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
            }
        }
        $this->mainTplAssigns('Home');
    }

    public function showUserhome() {
        
        // Main Layout
        // Logged or not logged, that is the question...

        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->id = PlonkSession::get('id');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
            }
        }

        if (($user = HomeDB::getNameById($this->id)) !== null) {
            $this->pageTpl->assign('user', $user['firstName']);
            // assign vars in our main layout tpl
            $this->mainTplAssigns('Welcome ' . $user['firstName']);

            $this->getErasmusInfo();
        }
    }

    public function getErasmusInfo() {
        $latestEvent = HomeDB::getLatestEvent($this->id);
        $next;
        $statusStudent = HomeDB::getStudent($this->id);

        if (!empty($statusStudent)) {
            if ($statusStudent['statusOfErasmus'] != "Student Application and Learning Agreement") {
                if (!empty($latestEvent)) {
                    $next = HomeDB::getNext($latestEvent['next']);
                }

                if (!empty($latestEvent)) {
                    $action = "";
                    $this->pageTpl->assign('action', '<a href="index.php?module=' . $latestEvent['module'] . '&view=' . $latestEvent['view'] . '" title="' . $latestEvent['levelName'] . '">' . $latestEvent['levelName'] . '</a>');
                    if ($latestEvent['action'] == 2) {
                        $action = "Pending";
                    } else if ($latestEvent['action'] == 1) {
                        $action = 'Approved';
                    } else {
                        $action = "Denied";
                    }
                    $this->pageTpl->assign('status', $action);
                    if ($latestEvent['levelName'] == "Certificate Of Arrival") {
                        $this->pageTpl->assign('next', '<li><a href="index.php?module=learnagr_ch&view=learnagrch" title="Change Learn Agreement">Change Learning Agreement</a></li>
                        <li><a href="index.php?module=mobility&view=mobility" title="Mobility Extension Period">Mobility Extension Period</a></li>');
                    } else if ($latestEvent['levelName'] == "Precandidate" && $action == "Denied") {
                        $this->pageTpl->assign('next', "<li>Your Precandidate has been denied. Sorry</li>");
                    } else {
                        if ($action == "Pending") {
                            $this->pageTpl->assign('next', 'Waiting for confirmation of ' . $latestEvent['levelName']);
                        } else {
                            $this->pageTpl->assign('next', '<li><a href="index.php?module=' . $next['module'] . '&view=' . $next['view'] . '" title="' . $next['levelName'] . '">' . $next['levelName'] . '</a></li>');
                        }
                    }
                }
            } else {
                if ($statusStudent['action'] == 0) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application is denied. Learning Agreement is denied');
                    $this->pageTpl->assign('next', '<a href="index.php?module=lagreeform&view=applicform" title="Fill in Student Application and Learning Agreement">Fill in Student Application and Learning Agreement</a>');
                } else if ($statusStudent['action'] == 1) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application is denied. Learning Agreement is approved.');
                    $this->pageTpl->assign('next', '<a href="index.php?module=lagreeform&view=applicform" title="Fill in Student Application Form">Fill in Student Application Form</a>');
                } else if ($statusStudent['action'] == 2) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form.');
                    $this->pageTpl->assign('status', 'Student Application is denied, Learning Agreement is pending.');
                    $this->pageTpl->assign('next', '<a href="index.php?module=lagreeform&view=applicform" title="Fill in Student Application Form">Fill in Student Application Form</a>');
                } else if ($statusStudent['action'] == 10) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application Form is approved, Learning Agreement is denied.');
                    $this->pageTpl->assign('next', '<a href="index.php?module=lagreeform&view=lagreement" title="Fill in Learning Agreement">Fill in Learning Agreement</a>');
                } else if ($statusStudent['action'] == 11) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application Form is approved, Learning Agreement is approved.');
                    $this->pageTpl->assign('next', '<a href="index.php?module=acom_reg&view=acom_reg" title="Fill in Accomodation registration form">Fill in Accomodation registration form</a>');
                } else if ($statusStudent['action'] == 12) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application Form is approved, Learning Agreement is pending.');
                    $this->pageTpl->assign('next', 'Waiting for confirmation of Learning Agreement.');
                } else if ($statusStudent['action'] == 20) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application Form is pending, Learning Agreement is denied.');
                    $this->pageTpl->assign('next', '<a href="index.php?module=lagreeform&view=lagreement" title="Fill in Learning Agreement">Fill in Learning Agreement</a>');
                } else if ($statusStudent['action'] == 21) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application Form is pending, Learning Agreement is approved.');
                    $this->pageTpl->assign('next', 'Waiting for confirmation of Student Application Form.');
                } else if ($statusStudent['action'] == 22) {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form and Learning Agreement.');
                    $this->pageTpl->assign('status', 'Student Application Form is pending, Learning Agreement is pending.');
                    $this->pageTpl->assign('next', 'Waiting for confirmation of Student Application and Learning Agreement.');
                } else {
                    $this->pageTpl->assign('action', 'Filled in Student Application Form but not Learning Agreement.');
                    $this->pageTpl->assign('status', 'Waiting for you to fill in Learning Agreement.');
                    $this->pageTpl->assign('next', '<a href="index.php?module=lagreeform&view=lagreement" title="Fill in Learning Agreement">Fill in Learning Agreement</a>');
                }
            }
        } else {
            $this->pageTpl->assign('action', 'No previous action taken.');
            $this->pageTpl->assign('status', 'No status due to no previous action.');
            $this->pageTpl->assign('next', '<a href="index.php?module=precandidate&view=precandidate" title="precandidate">Fill in pre candidate form</a>');
        }



        $forms = HomeDB::getForms($this->id);

        if (!empty($forms)) {
            $this->pageTpl->setIteration('iForms');

            foreach ($forms as $form) {
                if ($form['type'] == "Student Application Form") {
                    $this->pageTpl->assignIteration('form', '<li>' . $form['date'] . '<a href="index.php?module=' . $form['module'] . '&view=applicform&form='.$form['formId'].'" title="' . $form['type'] .'">' . $form['type'] . '</a></li>');
                } else if ($form['type'] == "Learning Agreement") {
                    $this->pageTpl->assignIteration('form', '<li>' . $form['date'] . '<a href="index.php?module=' . $form['module'] . '&view=lagreement&form='.$form['formId'].'" title="' . $form['type'] . '">' . $form['type'] . '</a></li>');
                } else {
                    $this->pageTpl->assignIteration('form', '<li>' . $form['date'] . '<a href="index.php?module=' . $form['module'] . '&view=' . $form['view'] . '&form='.$form['formId'].'" title="' . $form['type'] . '">' . $form['type'] . '</a></li>');
                }$this->pageTpl->refillIteration('iForms');
            }

            $this->pageTpl->parseIteration('iForms');
        } else {
            $this->pageTpl->assignOption('noForms');
        }

        $events = HomeDB::getEvents($this->id);

        if (!empty($events)) {
            $this->pageTpl->setIteration('iEvents');

            foreach ($events as $event) {
                $this->pageTpl->assignIteration('event', '<li>' . $event['timestamp'] . ' - ' . $event['eventDescrip'] . '<a href="index.php?module=home&view=notify&event=' . $event['eventId'] . '" title="read" >Read it</a></li>');
                $this->pageTpl->refillIteration('iEvents');
            }

            $this->pageTpl->parseIteration('iEvents');
        }
    }

    public function showNotify() {
        $array = array(
            'readIt' => 1
        );

        $id = PlonkFilter::getGetValue('event');

        if ($id != null) {
            HomeDB::updateEvent('studentsEvents', $array, 'eventId = ' . $id);
        }

        PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
    }

}

