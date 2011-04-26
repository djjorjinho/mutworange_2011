<?php

class AboutController extends PlonkController {
    
      protected $views = array(
        'about','termsofuse'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array( );
    
    private function mainTplAssigns() {
       
        // Assign main properties
        $this->mainTpl->assign('siteTitle', 'ErasmusLine');
        $this->mainTpl->assign('pageMeta', '');
    }
    
    public function showTermsofuse() {
        $thi->checkLogged();
        $this->mainTplAssigns();        
    }
    
    public function showAbout() {
        
        $this->checkLogged();
        $this->mainTplAssigns();
        
    }
    
    public function checkLogged() {

        if (PlonkSession::exists('loggedIn')) {

            $this->mainTpl->assignOption('oLogged');
            $this->id = PlonkSession::get('id');
        } else {

            $this->mainTpl->assignOption('oNotLogged');
        }
    }
}
?>
