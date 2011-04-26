<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'admin',
        'students',
        'confirmaction',
        'toconfirm'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
        'logout',
        'confirm'
    );

    public function checkLogged() {

        if (!PlonkSession::exists('loggedIn')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === '1') {
                $this->mainTpl->assignOption('oAdmin');
                $this->id = PlonkSession::get('id');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }
        }
    }

    public function showAdmin() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Admin page');

        $this->pageTpl->assignOption('oAdmin');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
        // assign menu active state
        $this->mainTpl->assignOption('oNavAdmin');
    }

    public function showStudents() {

        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Overview students');


        $this->pageTpl->assignOption('oAdmin');
        // assign menu active state
        $this->mainTpl->assignOption('oNavAdmin');

        // gets info of all the users
        $students = AdminDB::getUsersInfo();

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iStudents');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($students as $student) {
            if ($student['idUsers'] != 1) {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iStudents');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iStudents'); // alternative: $tpl->parseIteration();

        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
    }

    public function showToconfirm() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Students to confirm/deny');


        $this->pageTpl->assignOption('oAdmin');
        // assign menu active state
        $this->mainTpl->assignOption('oNavAdmin');

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iStudents');
        $i = 0;

        // gets info of all the users
        $students = AdminDB::getUsersInfo();

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($students as $student) {

            if ($student['idUsers'] != 1) {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['idUsers'] . '/profile.jpg');
                $this->pageTpl->assignIteration('i', $student['userId']);
                $this->pageTpl->assignIteration('acceptedYes', '');
                $this->pageTpl->assignIteration('acceptedNo', '');
                $this->pageTpl->assignIteration('acceptedWait', 'checked');

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iStudents');
                $i++;
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iStudents'); // alternative: $tpl->parseIteration();
    }

    /**
     * Logout action
     */
    public function doLogout() {
        PlonkSession::destroy();

        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
    }

    public function doConfirm() {

        $toAccept = array();
        $toDeny = array();
        foreach ($_POST as $key => $value) {
            if ($value === '2') {
                $toAccept[] = substr($key, 5, count($key) - 6);
            }
            if ($value === '0') {
                $toDeny[] = substr($key, 5, count($key) - 6);
            }
        }
    }

}
