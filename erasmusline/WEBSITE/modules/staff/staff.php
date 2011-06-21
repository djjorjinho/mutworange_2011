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
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','<a href="index.php?module=staff&view=staff" title="Home">Home</a>');
		
		//Add Exams modul
		require_once './modules/exams/exams.php';
    }
    
    public function showPrecandidates() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Precandidates');
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','<a href="index.php?module=staff&view=staff" title="Home">Home</a><a href="index.php?module=staff&view=precandidates" title="Precandidates">Precandidates</a>');
        
        // gets info of all the users
        $pres = StaffDB::getForms('Precandidate',  PlonkSession::get('id'));
        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iPres');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($pres as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'files/' . $student['email'] . '/profile.jpg');
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
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','<a href="index.php?module=staff&view=staff" title="Home">Home</a><a href="index.php?module=staff&view=agreements" title="Learning Agreements">Learning Agreements</a>');

        // gets info of all the users
        $agrees = StaffDB::getLagree(PlonkSession::get('id'));

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iAgreements');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($agrees as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'files/' . $student['email'] . '/profile.jpg');
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
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','<a href="index.php?module=staff&view=staff" title="Home">Home</a><a href="index.php?module=staff&view=changes" title="Change of Learning Agreements">Change of Learning Agreements</a>');

        // gets info of all the users
        $changes = StaffDB::getForms('Change Of Learning Agreement', PlonkSession::get('id'));

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iChanges');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($changes as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'files/' . $student['email'] . '/profile.jpg');
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
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','<a href="index.php?module=staff&view=staff" title="Home">Home</a><a href="index.php?module=staff&view=applics" title="Student Application Forms">Student Application Forms</a>');

        // gets info of all the users
        $id = StaffDB::getIdLevel("Student Application and Learning Agreement");
        $applics = StaffDB::getApplics(PlonkSession::get('id'));
        
        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iApplics');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($applics as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=profile');
                $this->pageTpl->assignIteration('hrefPhoto', 'files/' . $student['email'] . '/profile.jpg');
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
