<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class StaffController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'staff',
        'applics',
        'precandidates'
    );
    protected $id;
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
    );

    public function showStaff() {
        // Main Layout
        // Logged or not logged, that is the question...
        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Staff');
    }

    public function showPrecandidates() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Precandidates');

        // gets info of all the users
        $pres = StaffDB::getPrecandidates();

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iPres');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($pres as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=precandidate&view=precandidate&student=".$student['userId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iPres');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iPres'); // alternative: $tpl->parseIteration();
    }

    public function showApplics() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Application forms');

        $erasmusLevel = ErasmusCoorDB::getIdLevel('Student Application Form');

        // gets info of all the users
        $applics = ErasmusCoorDB::getLagree($erasmusLevel['levelId'], $this->id);

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iPres');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($applics as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=precandidate&view=precandidate");

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iPres');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iPres'); // alternative: $tpl->parseIteration();
    }

    public function checkLogged() {
        //Plonk::dump(PlonkSession::get('id').'hgdjdh');
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            
            if (PlonkSession::get('id') == 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            } else {
                $this->id = PlonkSession::get('id');
            }
        }
    }
    
    

}
