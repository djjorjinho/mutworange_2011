<?php

class LoginController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'login', 'logout'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
        'login'
    );

    public function showLogout() {
        MainController::logout();
    }

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

    public function showLogin() {
        $this->mainTplAssigns('Login');

        $this->checkLogged();

        if (PlonkFilter::getGetValue('error') === '1') {
            $this->pageTpl->assign('errorMsg', 'Username or password is incorrect');
        }
        if (PlonkFilter::getGetValue('error') === '2') {
            $this->pageTpl->assign('errorMsg', 'We couldn\'t find you. Is it possible you don\'t have an <a href="index.php?module=register&view=register" title="Create account">account</a> yet?');
        }
    }

    public function doLogin() {
        MainController::Login();
    }

    public function checkLogged() {

        if (PlonkSession::exists('id')) {
            if (PlonkSession::get('id') === '0') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }
        }
        $this->mainTpl->assignOption('oNotLogged');
        $this->pageTpl->assignOption('oNotLogged');
    }

}
