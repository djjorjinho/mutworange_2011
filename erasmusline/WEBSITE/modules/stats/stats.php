<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

#require_once 'StatsCall.php';
#require_once("library/plonk/template/template.php");
class StatsController extends PlonkController {

	
	
    protected $views = array(
        'stats'
    );
    protected $actions = array(
        'submit'
    );
    protected $variablesFixed = array(
        
    );
    protected $variablesRequired = array(
      
    );
    protected $variablesOptional = array(
        
    );

    protected $errors = array();
    protected $rules = array();
    protected $user;
    

    private function MainTplAssigns() {
        $this->mainTpl->assign('siteTitle', "Executive Information System");
        
        $this->mainTpl->assign('breadcrumb', "");
        
        $this->mainTpl->assign('pageJava', 
        	'<script type="text/javascript"' .
        	' src="core/js/jquery/jquery-1.5.js"></script');
        
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

}

?>
