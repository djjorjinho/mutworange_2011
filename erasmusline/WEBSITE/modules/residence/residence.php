<?php

class ResidenceController extends PlonkController {
    protected $views = array(
        'detail', 'overview'
    );    
    protected $actions = array(
        'search'
    );
    
    public function showDetail() {
        $this->checklogged();
        $erasmuslevel = ResidenceDB::getErasmusLevel(PlonkSession::get('id'));
        if($erasmuslevel['levelName'] == "Accommodation Registration Form") {
            $this->pageTpl->assignOption('oReservation');
        }
        $this->mainTplAssigns();
        if(PlonkFilter::getGetValue('id') == null) {
                 PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home' );
        }
        else {
            $residence = ResidenceDB::getResidenceById(PlonkFilter::getGetValue('id'));
            
            if(!empty($residence)) {
                $this->showResidence($residence);
            }
            else {
                   PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home' );      
            }
        }        
    }
    
    public function showResidence($residence) {
        $owner = ResidenceDB::getOwnerByResidence($residence['residenceId']);
        
        foreach($owner as $key => $value) {
            $this->pageTpl->assign($key, $value);
        }
        
        foreach($residence as $key => $value) {
            if($key == "kitchen" || $key == "bathroom") {
                if($value == 1) {
                    $value = 'Personal ' . $key;                    
                }
                else {
                    $value = 'Communal ' . $key;
                }                
            }
            if($key == "water" || $key == "elektricity" || $key == "television" || $key == "internet") {
                if($value == 1) {
                    $value = ucfirst($key) . " available, but not included in the price";
                }
                else if ($value == 2) {
                    $value = ucfirst($key) . " available and included in the price";
                }
                else {
                    $value = ucfirst($key) . " not available";
                }
            }
            if($key == "available") {
                if($value == 1) {
                    $value = 'Available';
                }
                else {
                    $value = 'Not available';
                }
            }
            $this->pageTpl->assign($key,$value);            
        }
    }
    
    public function showOverview() {
        $this->checkLogged();
        
        $this->mainTplAssigns();
        $this->fillCountries(PlonkFilter::getGetValue('search'));
        
        if(PlonkFilter::getGetValue('search') == null) {
            $residences = ResidenceDB::getResidences();
            
        }
        else {
            $residences = ResidenceDB::getResidencesByCountry(PlonkFilter::getGetValue('search'));
        }   
        if(!empty($residences)) {
            $this->pageTpl->assign('error','');
            $this->fillResidences($residences);
        }
        else {
            $this->pageTpl->assign('error','No results found');
        }
    }
    
    private function fillResidences($residences) {
        
        $this->pageTpl->setIteration('iResidences');
        
        foreach($residences as $residence) {
            $this->pageTpl->assignIteration('country', $residence['country']);
            $this->pageTpl->assignIteration('price',$residence['price']);
            $this->pageTpl->assignIteration('city',$residence['city']);
            $this->pageTpl->assignIteration('postalCode', $residence['postalCode']);
            $this->pageTpl->assignIteration('id',$residence['residenceId']);
            $this->pageTpl->refillIteration();           
        }
        $this->pageTpl->parseIteration();
    }
    
    public function doSearch() {  
        if(PlonkFilter::getPostValue('btnSearch') == null) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=residence&' . PlonkWebsite::$viewKey . '=overview' );
        }
        else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=residence&' . PlonkWebsite::$viewKey . '=overview&search=' . PlonkFilter::getPostValue('country'));
        }
       
    }
    private function mainTplAssigns() {
        $this->mainTpl->assign('pageMeta','');
        $this->mainTpl->assign('pageJava','');
        $this->mainTpl->assign('breadcrumb','');
        $this->mainTpl->assign('siteTitle','Residence');
        if(PlonkFilter::getGetValue('search') == null) {
            $this->pageTpl->assign('search','');            
        }
        else {
            $this->pageTpl->assign('search',PlonkFilter::getGetValue('search'));
        }
    }
    private function fillCountries($country = '') {
         $countries = ResidenceDB::getCountries();
        try {
            $this->pageTpl->setIteration('iCountry');
            foreach ($countries as $key => $value) {
                if ($country == $value['Code']) {
                    $this->pageTpl->assignIteration('country', '<option selected=\"true\" value=' . $value['Code'] . '> ' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('country', '<option value="' . $value['Code'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iCountry');
            }
            $this->pageTpl->parseIteration('iCountry');
        } catch (Exception $e) {
            
        }
    }
    
    public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->id = PlonkSession::get('id');                
            } else {
                if (PlonkFilter::getGetValue('student') != null) {
                    $this->pageTpl->assignOption('oCoor');
                } else {
                    PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
                }
            }
        }
    }
}
?>
