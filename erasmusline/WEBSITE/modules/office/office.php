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
        'changes',
        'extends'
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
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=office&view=office" title="Office page">International Office</a>');
        $this->mainTpl->assign('pageJava', '');
        if(PlonkFilter::getGetValue('success') != null) {
            if(PlonkFilter::getGetValue('success') == 'true') {
                $this->pageTpl->assign('message', 'Info was sent succesfull');
            }
            else if (PlonkFilter::getGetValue('success') == 'false') {
                $this->pageTpl->assign('message', 'Info couldn\'t be sent because the host institute couldn\'t be accesed.');
            }
            else {
                $this->pageTpl->assign('message', 'No notifications.');
            }
        }
        else {
            $this->pageTpl->assign('message', '');
        }
    }
    
    public function showAgreements() {
        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Learning Agreements');
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=office&view=office" title="Office page">International Office</a><a href="index.php?module=office&view=agreements" title="Learning Agreements">Learning Agreements</a>');
        $this->mainTpl->assign('pageJava', '');

        // gets info of all the users
        $agrees = OfficeDB::getLagree();

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iAgreements');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($agrees as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile&student='.$student['email']);
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
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=office&view=office" title="Office page">International Office</a><a href="index.php?module=office&view=changes" title="Change of Learning Agreements">Change of Learning Agreements</a>');
        $this->mainTpl->assign('pageJava', '');

        // gets info of all the users
        $changes = OfficeDB::getForms('Change Of Learning Agreement');

        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iChanges');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($changes as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile&student='.$student['email']);
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
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=office&view=office" title="Office page">International Office</a><a href="index.php?module=office&view=applics" title="Student Application Forms">Student Application Forms</a>');
        $this->mainTpl->assign('pageJava', '');

        // gets info of all the users
        $id = OfficeDB::getIdLevel("Student Application and Learning Agreement");
        $applics = OfficeDB::getApplics();
        
        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iApplics');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($applics as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile&student='.$student['email']);
                $this->pageTpl->assignIteration('hrefPhoto', 'files/' . $student['email'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=lagreeform&view=applicform&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iApplics');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iApplics'); // alternative: $tpl->parseIteration();
    }
    
    public function showExtends() {
        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('siteTitle', 'Application forms');
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=office&view=office" title="Office page">International Office</a><a href="index.php?module=office&view=extend" title="Extend Mobility Period">Extend Mobility Period</a>');
        $this->mainTpl->assign('pageJava', '');

        // gets info of all the users
        $id = OfficeDB::getIdLevel("Extend Mobility Period");
        $extends = OfficeDB::getForms('Extend Mobility Period');
        
        // assign iterations: overlopen van de gevonden users
        $this->pageTpl->setIteration('iExtends');

        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        foreach ($extends as $student) {
            if ($student['email'] != 'admin') {
                $this->pageTpl->assignIteration('name', $student['firstName'] . ' ' . $student['familyName']);
                $this->pageTpl->assignIteration('hrefProfile', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile&student='.$student['email']);
                $this->pageTpl->assignIteration('hrefPhoto', 'files/' . $student['email'] . '/profile.jpg');
                $this->pageTpl->assignIteration('url', "index.php?module=extend&view=extend&form=".$student['formId']);

                // refill the iteration (mandatory!)
                $this->pageTpl->refillIteration('iExtends');
            }
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iExtends'); // alternative: $tpl->parseIteration();
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
