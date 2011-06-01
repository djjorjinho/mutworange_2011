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
        'precandidates',
        'agreements',
        'reapplics',
        'changes'
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
		
		//Add Exams modul
		require_once './modules/exams/exams.php';
    }

    public function showReapplics() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Retried Student Application Form');

        // gets info of all the users
        $reapplic = StaffDB::getForms('ReStudent Application Form');

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iReApplics');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($reapplic as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=lagreeform&view=applicform&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iReApplics');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iReApplics'); // alternative: $tpl->parseIteration();
    }
    
    public function showPrecandidates() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Precandidates');

        // gets info of all the users
        $pres = StaffDB::getForms('Precandidate');

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iPres');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($pres as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=precandidate&view=precandidate&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iPres');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iPres'); // alternative: $tpl->parseIteration();
    }
    
    public function showAgreements() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Learning Agreements');

        // gets info of all the users
        $agrees = StaffDB::getLagree();

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iAgreements');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($agrees as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=lagreeform&view=lagreement&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iAgreements');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iAgreements'); // alternative: $tpl->parseIteration();
    }
    
    public function showChanges() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Change of Learning Agreements');

        // gets info of all the users
        $changes = StaffDB::getForms('Change Of Learning Agreement');

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iChanges');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($changes as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=learnagr_ch&view=learnagrch&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iChanges');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iChanges'); // alternative: $tpl->parseIteration();
    }

    public function showApplics() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Application forms');

        // gets info of all the users
        $id = StaffDB::getIdLevel("Student Application and Learning Agreement");
        $applics = StaffDB::getApplics();
        
        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iApplics');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($applics as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'users/' . $student['userId'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=lagreeform&view=applicform&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iApplics');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iApplics'); // alternative: $tpl->parseIteration();
    }

    public function checkLogged() {
        //Plonk::dump(PlonkSession::get('id').'hgdjdh');
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            } else {
                $this->id = PlonkSession::get('id');
            }
        }
    }  

}
