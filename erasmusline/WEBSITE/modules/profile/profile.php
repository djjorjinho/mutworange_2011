<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ProfileController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'profile',
        'ownprofile'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
    );
    private $id, $erasmusLevel;

    /**
     * Assign variables that are main and the same for every view
     * @param	= null
     * @return  = void
     */
    private function mainTplAssigns($pageTitle) {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', $pageTitle);
        $this->mainTpl->assign('pageMeta', '
            <script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("UA-1120774-3");
			pageTracker._trackPageview();
			} catch(err) {}
		</script>
		<script type="text/javascript" src="http://t.wits.sg/misc/js/jQuery/jquery.js"></script>
		<script type="text/javascript" src="../www/core/js/progressbar/js/jquery.progressbar.js"></script>
		<script type="text/javascript">

			$(document).ready(function() {
				$("#pb1").progressBar(' . $this->erasmusLevel . ');
			});


		</script>
                <style type="text/css">
			table tr { vertical-align: top; }
			table td { padding: 3px; }
			div.contentblock { padding-bottom: 25px; }	
		</style>
                ');
    }

    public function showOwnprofile() {
        $this->showProfile();

        $erasmus = ProfileDB::getErasmusById($this->id);
    }

    public function showProfile() {

        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();

        // assign menu active state
        $this->mainTpl->assignOption('oNavProfile');

        $info = ProfileDB::getItemsById($this->id);

        $this->pageTpl->assign('fName', $info['firstName']);
        $this->pageTpl->assign('faName', $info['familyName']);
        $this->pageTpl->assign('postal', $info['postalCode']);
        $this->pageTpl->assign('city', $info['city']);
        if ($info['Sex'] === '1') {
            $this->pageTpl->assign('sex', 'Male');
        } else {
            $this->pageTpl->assign('sex', 'Female');
        }
        $this->pageTpl->assign('sex', $info['sex']);
        $this->pageTpl->assign('userLevel', $info['userLevel']);

        $this->erasmusLevel = (int) $info['idUserlevel'] * 10;

        $this->pageTpl->assign('nationality', $info['Code']);

        // assign vars in our main layout tpl
        $this->mainTplAssigns('Profile');

        $this->mainTpl->assign('profile', 'index.php?module=profile&view=ownprofile');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
    }

    public function checkLogged() {

        if (!PlonkSession::exists('loggedIn')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === '1') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            }
            $this->mainTpl->assignOption('oLogged');
            $this->id = PlonkSession::get('id');
        }
        $this->mainTpl->assignOption('oProfile');
    }

}
