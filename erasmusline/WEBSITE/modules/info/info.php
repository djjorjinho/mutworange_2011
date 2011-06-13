<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class InfoController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'erasmus',
        'erasmusline',
        'faq',
        'partners',
        'info',
        'contact'
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
    private function mainTplAssigns() {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', 'Info');
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('breadcrumb','');
        $this->mainTpl->assign('pageJava','');
    }
    
    public function showInfo() {
         $this->mainTplAssigns();

        // assign menu active state
        $this->mainTpl->assignOption('oNavErasmus');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');

        // Page Specific Layout
        $this->pageTpl->assign('pageTitle', 'Info');
    }

    public function showErasmus() {
        // Main Layout
        // assign vars in our main layout tpl

        $this->mainTplAssigns();

        // assign menu active state
        $this->mainTpl->assignOption('oNavErasmus');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');

        // Page Specific Layout
        $this->pageTpl->assign('pageTitle', 'What is Erasmus');
    }

    public function showErasmusline() {
        // Main Layout
        // assign vars in our main layout tpl

        $this->mainTplAssigns();

        // assign menu active state
        $this->mainTpl->assignOption('oNavErasmusline');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');

        // Page Specific Layout
        $this->pageTpl->assign('pageTitle', 'What is this site about');
    }

    public function showFaq() {
        // Main Layout
        // assign vars in our main layout tpl

        $this->mainTplAssigns();

        // assign menu active state
        $this->mainTpl->assignOption('oNavFaq');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');

        // Page Specific Layout
        $this->pageTpl->assign('pageTitle', 'FAQ');
    }

    public function showPartners() {
        // Main Layout
        // assign vars in our main layout tpl

        $this->mainTplAssigns();

        // assign menu active state
        $this->mainTpl->assignOption('oNavPartners');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');

        // Page Specific Layout
        $this->pageTpl->assign('pageTitle', 'Partners');
        
        $partners = InfoDB::getPartners();
        
        $this->pageTpl->setIteration('iPartners');
        
        foreach($partners as $key => $value) {
            $this->pageTpl->assignIteration('instName', $value['instName']);
            $this->pageTpl->assignIteration('instStreetNr',$value['instStreetNr']);
            $this->pageTpl->assignIteration('instCity', $value['instCity']);
            $this->pageTpl->assignIteration('instPostalCode',$value['instPostalCode']);
            $this->pageTpl->assignIteration('instCountry',$value['instCountry']);
            $this->pageTpl->assignIteration('instWebsite',$value['instWebsite']);
            $this->pageTpl->assignIteration('tel',$value['instTel']);
            
            $this->pageTpl->refillIteration('iPartners');
       }
       $this->pageTpl->parseIteration('iPartners');
    }
    
    public function showContact() {
     $this->mainTplAssigns();
     
     $institute = InfoDB::getInstitute(INST_EMAIL);
     if(!empty($institute)) {
         $this->pageTpl->assign('instName',$institute['instName']);
         $this->pageTpl->assign('instTel',$institute['instTel']);
         $this->pageTpl->assign('instStreetNr',$institute['instStreetNr']);
         $this->pageTpl->assign('instCity',$institute['instCity']);
         $this->pageTpl->assign('instPostalCode',$institute['instPostalCode']);
         $this->pageTpl->assign('instEmail',$institute['instEmail']);
         $this->pageTpl->assign('instWebsite','http://'.$institute['instWebsite']);
         
     }
    }

}
