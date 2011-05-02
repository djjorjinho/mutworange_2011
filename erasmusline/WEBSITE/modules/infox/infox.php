<?php

class InfoxController extends PlonkController {
    protected $views = array ('infox','transfer');
    protected $actions = array('transfer');
    protected $debug = false;
    protected $debugMsg = "";
    
    public function InfoxController() {
      include('curl.php');
      if (PlonkFilter::getGetValue('debug') != null) {
        $this->debug = true;
      }
    }
    
    public function showInfox() {
      $this->mainTpl->assign('pageTitle', 'ERASMUS line');
		if (PlonkSession::exists('login') && PlonkSession::get("login") == true) {
		  $this->mainTpl->assign('loginLink', '<a href="index.php?module=home&view=logout">Logout</a>');
		  $this->mainTpl->assign('loginName', PlonkSession::get('Firstname'));
        } else
		  $this->mainTpl->assign('loginLink', '<a href="index.php?module=home&view=login">Login</a>');

// set Iteration with all universities
      $uni = InfoxDB::getUniversity();
      $this->pageTpl->setIteration('iUniversity');
      foreach ($uni as $key => $value) {
        $this->pageTpl->assignIteration ('university', '<option value=' . $value['id'] . '> ' . $value['name'] . '</option>');
        $this->pageTpl->refillIteration('iUniversity');
      }
      $this->pageTpl->parseIteration('iUniversity');
      
// set JSON String
      if (PlonkFilter::getPostValue('table') != null) {
        $array = array('table' => PlonkFilter::getPostValue('table'), 'data' => $_POST['data']);
        $json = json_encode($array);
        $json = str_replace('"',"'",$json);
        $json = str_replace("''","'",$json);
      } else if (PlonkSession::exists('infoxJSON'))
        $json = PlonkSession::get('infoxJSON');
      else
        PlonkWebsite::redirect('index.php');
      $this->pageTpl->assign('json', $json);

      if ($this->debug) {
        $this->debugMsg['JSON'] = $json;
      }

      if ($this->debug) {
        $msg = "# # # # # # DEBUG # # # # # #<br />".$this->getDebug($this->debugMsg);
        $this->pageTpl->assign("debug",$msg);
      } else {
        $this->pageTpl->assign("debug","");
      }
    }
    public function showTransfer() {
      if (curl::getHTTPCode() == 200) {
        $this->pageTpl->assign('success', curl::getResult());
      } else {
        $this->pageTpl->assign('success', "FEHLER");
      }
      if ($this->debug) {
        $msg = "# # # # # # DEBUG # # # # # #<br />".$this->getDebug($this->debugMsg);
        $this->pageTpl->assign("debug",$msg);
      } else {
        $this->pageTpl->assign("debug","");
      }
    }
    public function doTransfer() {
//    	if (PlonkSession::exists('login') && PlonkSession::get("login") == true) {
        if (PlonkFilter::getPostValue('idUni') == 0) {
          PlonkSession::set('infoxJSON', PlonkFilter::getPostValue('json'));
          PlonkWebsite::redirect('index.php?module=infox');
        } else {
          PlonkSession::remove('infoxJSON');
          curl::start();
          curl::setOption(CURLOPT_URL, InfoxDB::getURL(PlonkFilter::getPostValue('idUni'))."/modules/infox/airport/airport.php");
          curl::setOption(CURLOPT_POST, 1);
          curl::setOption(CURLOPT_RETURNTRANSFER, true);
          curl::setOption(CURLOPT_USERPWD, InfoxDB::getAuthUsername().":".InfoxDB::getAuthPwd());
          curl::setOption(CURLOPT_POSTFIELDS,"json=".PlonkFilter::getPostValue('json'));
          curl::setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl::execute();
          if ($this->debug) {
            $this->debugMsg['curl'] = curl::getInfo();
          }
        }
//    	}
    }
    
    public function getDebug($a) {
      $dbg = "";
      if (is_array($a)) {
        $dbg = "Array (<br />";
        foreach ($a as $key => $value) {
          $dbg .= $key." => ";
          if (is_array($value))
            $dbg .= $this->getDebug($value);
          else
            $dbg .= $value.",<br />";
        }
        $dbg .= ")<br />";
      } else
        $dbg .= $a;
      return $dbg;
    }
}

?>
