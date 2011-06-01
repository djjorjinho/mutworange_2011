<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MainController extends PlonkController {

    public function assignMain() {
        MainController::checkLogged();
    }

    public function doFunction($action) {
        // Action does exist
        call_user_func('MainController::' . ucfirst($action));
    }

    public static function login() {
        $msgEmail = "Email and/or password is not filled in.";
        $msgName = "Name is not filled in.";

        $email = PlonkFilter::getPostValue('Email');
        $password = PlonkFilter::getPostValue('Password');
        
        
        
        if ($email !== null && $password !== null) {

            
            $values = MainController::userExist($email, md5($password));

            if (!empty($values)) {

                if (strtolower($email) === 'admin') {
                    PlonkSession::set('id', 0);
                    
                    PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
                    
                } else {
                    
                    PlonkSession::set('id', $values['email']);
                    PlonkSession::set('userLevel', $values['userLevel']);
                    
                    if (PlonkSession::get('id') === 0) {
                        
                        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
                    } else if (PlonkSession::get('userLevel') == 'Student') {
                        
                        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
                    }
                    else if(PlonkSession::get('userLevel') == "International Relations Office Staff") {
                        PlonkWebsite::redirect('index.php?module=office&view=office');
                    }
                    else {
                        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . 'staff&' . PlonkWebsite::$viewKey . '=staff');
                    }
                }
            } else {
                PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=login&' . PlonkWebsite::$viewKey . '=login&error=2');
            }
        } else {
            PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=login&' . PlonkWebsite::$viewKey . '=login&error=1');
        }
    }

    /**
     * Logout action
     */
    public static function logout() {

        PlonkSession::destroy();

        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
    }

    /**
     * Return User, controll on PW
     *
     * @return array
     */
    public static function userExist($email, $password) {
        // get DB instance
        $db = PlonkWebsite::getDB();
        
        // query DB
        $items = $db->retrieveOne("select userId, email, userLevel from users where email = '" . $db->escape($email) . "' AND password ='" . $db->escape($password) . "' AND isValidUser != 0");
        // return the result
        return $items;
    }

    public function checkLogged() {
        
        session_id();
        if (PlonkSession::exists('id')) {
            $this->mainTpl->assignOption('oLogged');
            if (PlonkSession::get('id') === 0) {
                $this->mainTpl->assignOption('oAdmin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->mainTpl->assignOption('oStudent');
            } else if (PlonkSession::get('userLevel') == 'Erasmus Coordinator') {
                $this->mainTpl->assignOption('oCoor');
            } else if (PlonkSession::get('userLevel') == 'Teaching Staff') {
                $this->mainTpl->assignOption('oTeacher');
            } else if (PlonkSession::get('userLevel') == 'International Relations Office Staff') {
                $this->mainTpl->assignOption('oOffice');
            } else if (PlonkSession::get('userLevel') == 'Industrial Institution') {
                $this->mainTpl->assignOption('oIndustrial');
            }
        } else {
            $this->mainTpl->assignOption('oNotLogged');
        }
    }
    

}

?>
