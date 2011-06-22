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
        'staff'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
        'logout',
        'infox',
        'add'
        
    );
    
    public function doAdd() {
        PlonkWebsite::redirect("index.php?module=register&amp;view=register");
    }

    public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&amp;' . PlonkWebsite::$viewKey . '=home');
        } else {
            //Plonk::dump('test');
            if (PlonkSession::get('id') === 0) {
                $this->id = PlonkSession::get('id');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&amp;' . PlonkWebsite::$viewKey . '=userhome');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&amp;' . PlonkWebsite::$viewKey . '=staff');
            }
        }
    }

    public function showAdmin() {
        // Main Layout
        // Logged or not logged, that is the question...
        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/css/form.css" type="text/css" />');
        $this->mainTpl->assign('siteTitle', 'Admin page');
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','');
    }

    public function showStaff() {
        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/css/form.css" type="text/css" />');
        $this->mainTpl->assign('siteTitle', 'Overview staff');
        $this->mainTpl->assign('breadcrumb','');
        $this->mainTpl->assign('pageJava','');

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iStaff');
        $i = 0;

        // gets info of all the users
        $staff = AdminDB::getStaffInfo();

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($staff as $staf) {

            if ($staf['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $staf['firstName'] . ' ' . $staf['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&amp;' . PlonkWebsite::$viewKey . '=ownprofile&amp;student='.$staf['email']);
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $staf['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('i', $staf['userId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iStaff');
                $i++;
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iStaff'); // alternative: $tpl->parseIteration();
    }

    /**
     * Logout action
     */
    public function doLogout() {
        PlonkSession::destroy();

        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=home&amp;' . PlonkWebsite::$viewKey . '=home');
    }

    public function doInfox() {
		include('modules/infox/infox.php');
        $method = array('infox:test1', 'infox:test2');
        $table = array('tab1', 'tab2');
        $data = array(array('data11','data12','data13'),array('data21','data22','data23'));
        echo InfoxController::dataTransfer($method, $table, $data, "info@kahosl.be");
        
        // File transfer
        $method = "infox:filetest";
        echo InfoxController::fileTransfer($method, "C:/Program Files/git/msysgit/git/mutworange/erasmusline/WEBSITE/test.txt", "info@kahosl.be");
    }
}
