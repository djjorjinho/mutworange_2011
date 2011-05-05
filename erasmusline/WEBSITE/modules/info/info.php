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
        'partners'
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
    }

    public function checkLogged() {
        //Plonk::dump(PlonkSession::get('id').'hgdjdh');
        if (!PlonkSession::exists('loggedIn')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') == 0) {
                $this->mainTpl->assignOption('oAdmin');
                $this->id = PlonkSession::get('id');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }
        }
    }

    public function showErasmus() {
        // Main Layout
        // assign vars in our main layout tpl
        $this->checkLogged();

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

        $this->checkLogged();

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
        $this->checkLogged();

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
        $this->checkLogged();

        $this->mainTplAssigns();

        // assign menu active state
        $this->mainTpl->assignOption('oNavPartners');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');

        // Page Specific Layout
        $this->pageTpl->assign('pageTitle', 'Partners');
    }

}
