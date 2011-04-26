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

        if (PlonkSession::exists('loggedIn')) {
            $this->id = PlonkSession::get('id');

            if (PlonkSession::get('id') === '1') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }
        } else {
            $this->mainTpl->assignOption('oNotLogged');
            $this->pageTpl->assignOption('oNotLogged');
        }

        $this->mainTplAssigns('Home');

        // assign menu active state
        $this->mainTpl->assignOption('oNavHome');
    }

    public function showUserhome() {
        // Main Layout
        // Logged or not logged, that is the question...

        if (!PlonkSession::exists('loggedIn')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            $this->id = PlonkSession::get('id');
            $this->mainTpl->assignOption('oLogged');
            $this->pageTpl->assignOption('oLogged');
        }

        // assign vars in our main layout tpl
        $this->mainTplAssigns('Welcome dummy');

        // assign menu active state
        $this->mainTpl->assignOption('oNavUserHome');

        if (($user = HomeDB::getNameById($this->id)) !== null) {
            $this->pageTpl->assign('user', $user['firstName']);

            $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            $this->mainTpl->assign('profile', 'index.php?module=profile&view=ownprofile');
        }
    }

    public function checkLogged() {

        if (!PlonkSession::exists('loggedIn')) {

            $this->mainTpl->assignOption('oNotLogged');
            $this->pageTpl->assignOption('oNotLogged');

            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === '1') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }

            $this->mainTpl->assignOption('oLogged');
            $this->pageTpl->assignOption('oLogged');

            $this->id = PlonkSession::get('id');
        }
    }

}

