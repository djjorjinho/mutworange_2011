<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class OfficeController extends PlonkController {
    
    protected $views = array(
        'office',
        'applics',
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
    
    public function showOffice() {
        // Main Layout
        // Logged or not logged, that is the question...
        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Office coordinator');
        
        if(PlonkFilter::getGetValue('success') != null) {
            if(PlonkFilter::getGetValue('success') == 'true') {
                $this->pageTpl->assign('message', 'Info was sent succesfull');
            }
            else if (PlonkFilter::getGetValue('success') == 'false') {
                $this->pageTpl->assign('message', 'Info couldn\'t be sent because the host institute couldn\'t be accesed.');
            }
            else {
                $this->pageTpl->assign('message', '');
            }
        }
    }
    
    public function showReapplics() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Retried Student Application Form');

        // gets info of all the users
        $reapplic = OfficeDB::getForms('ReStudent Application Form');

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
    
    public function showAgreements() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Learning Agreements');

        // gets info of all the users
        $agrees = OfficeDB::getLagree();

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
        $changes = OfficeDB::getForms('Change Of Learning Agreement');

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
        $id = OfficeDB::getIdLevel("Student Application and Learning Agreement");
        $applics = OfficeDB::getApplics();
        
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
