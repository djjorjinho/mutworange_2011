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
        'userhome'
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
            
            if (PlonkSession::get('id') == 0) {
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
            if (PlonkSession::get('id') == 0) {
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
        if (!empty($latestEvent)) {
            $next = HomeDB::getNext($latestEvent['next']);
        }

        if (!empty($latestEvent)) {
            $action = "";
            $this->pageTpl->assign('action', '<a href="index.php?module=' . $latestEvent['module'] . '&view=' . $latestEvent['view'] . '" title="' . $latestEvent['levelName'] . '">' . $latestEvent['levelName'] . '</a>');
            if($latestEvent['action'] == 2) {
                $action = "Pending";
            }
            else if($latestEvent['action'] == 1) {
                $action = 'Approved';
            }
            else {
                $action = "Denied";
            }
            $this->pageTpl->assign('status', $latestEvent['action']);
            $this->pageTpl->assign('next', '<a href="index.php?module=' . $next['module'] . '&view=' . $next['view'] . '" title="' . $next['levelName'] . '">' . $next['levelName'] . '</a>');
        } else {
            $this->pageTpl->assign('action', 'No current action taken.');
            $this->pageTpl->assign('status', 'No status');
            $this->pageTpl->assign('next', '<a href="index.php?module=precandidate&view=precandidate" title="precandidate">Fill in pre candidate form</a>');
        }


        $forms = HomeDB::getForms($this->id);

        if (!empty($forms)) {
            $this->pageTpl->setIteration('iForms');

            foreach ($forms as $form) {
                $this->pageTpl->assignIteration('form', '<li>' . $form['date'] . '<a href="index.php?module=' . $form['module'] . '&view=' . $form['view'] . '" title="' . $form['type'] . '">' . $form['type'] . '</a></li>');
                $this->pageTpl->refillIteration('iForms');
            }

            $this->pageTpl->parseIteration('iForms');
        } else {
            $this->pageTpl->assignOption('noForms');
        }
    }

}

