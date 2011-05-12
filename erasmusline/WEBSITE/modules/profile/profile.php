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
		<script type="text/javascript" src="./core/js/progressbar/js/jquery.progressbar.js"></script>
		<script type="text/javascript">

			$(document).ready(function() {
				$("#pb1").progressBar({$progress});
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

        //$erasmus = ProfileDB::getErasmusById($this->id);
    }

    public function showProfile() {

        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        $this->mainTplAssigns('Profile');

        // assign menu active state
        $this->mainTpl->assignOption('oNavProfile');
        $this->id = PlonkSession::get('id');
        $info = ProfileDB::getItemsById($this->id);
        $erasmuslevel = ProfileDB::getErasmusById($this->id);

        $this->pageTpl->assign('fName', $info['firstName']);
        $this->pageTpl->assign('faName', $info['familyName']);
        $this->pageTpl->assign('postal', $info['postalCode']);
        $this->pageTpl->assign('city', $info['city']);
        if ($info['sex'] === '1') {
            $this->pageTpl->assign('sex', 'Male');
        } else {
            $this->pageTpl->assign('sex', 'Female');
        }
        $this->pageTpl->assign('sex', $info['sex']);
        $this->pageTpl->assign('userLevel', $info['userLevel']);
        
        if ($erasmuslevel['statusOfErasmus'] == 'Precandidate') {
            $this->erasmusLevel = 1 * 10;
        }
        if ($erasmuslevel['statusOfErasmus'] == 'Student Application Form') {
            $this->erasmusLevel = 1.5 * 10;
        }
        if ($erasmuslevel['statusOfErasmus'] == 'Learning Agreement') {
            $this->erasmusLevel =100;
        }
        
        $this->mainTpl->assign('progress', $this->erasmusLevel);
        
        $this->pageTpl->assign('nationality', $info['country']);
    }

    
    public function checkLogged() {
        //Plonk::dump(PlonkSession::get('id').'hgdjdh');
<<<<<<< HEAD
        if (!PlonkSession::exists('loggedIn')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') == 0) {
                $this->mainTpl->assignOption('oAdmin');
                $this->id = PlonkSession::get('id');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
=======
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') == 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
            }
        }
    }

}
