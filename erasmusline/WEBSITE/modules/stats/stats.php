<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('library/eis/StatsCall.php');
class StatsController extends PlonkController {
	
    protected $views = array(
        'stats'
    );
    protected $actions = array(
        'submit'
    );
    

    private function MainTplAssigns() {
        $this->mainTpl->assign('siteTitle', "Executive Information System");
        
        $this->mainTpl->assign('breadcrumb', "");
        
        $this->mainTpl->assign('pageJava', "");
        
        $this->mainTpl->assign('pageMeta', 
        	'<link rel="stylesheet" href="core/css/stats.css"' .
        		' type="text/css" media="screen"/>');
    }

    public function showStats() {
    	//Metodo chamado a quando a pagina é chamada
        $this->checkLogged();
        $this->MainTplAssigns();
        $this->fillTest();
        
    }

 	private function fillTest(){
 		$array=array();
 		//Preencher a lista de dimensoes
 		$this->pageTpl->setIteration('iDimension');
        foreach ($array as $value) {
           
                $this->pageTpl->assignIteration('dim1', 
                '<option value="' . $value['Name'] . '">' . 
                	$value['Name'] . '</option>');
                $this->pageTpl->refillIteration('iDimension');
            
        }
        $this->pageTpl->parseIteration('iDimension');
 	}

    private function checkLogged() {
    	
    	if (!PlonkSession::exists('id')) {
            	 PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' 
            	 . PlonkWebsite::$moduleKey . '=home&' 
            	 . PlonkWebsite::$viewKey . '=home');
     	}
    	
    }
	
    function loadRules(){
    	$call = new StatsCall();
    }
    
    function listScenarios(){
    	$call = new StatsCall();
    }
    
    function loadScenario(){
    	$call = new StatsCall();
    }
    
	function saveScenario(){
    	$call = new StatsCall();
    }
    
    
}

?>
