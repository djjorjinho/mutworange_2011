<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class MainController extends PlonkController {
    
    public function assignMain() {
        //$this->mainTpl->assign('test', 'Ahaaaaaaaa');
        
    }
    
    public function doFunction($action) {
        // Action does exist
        call_user_func('MainController::'.ucfirst($action));
        
    }
    

    public static function login() {
        
        $msgEmail = "Email and/or password is not filled in.";
        $msgName = "Name is not filled in.";

        $email = PlonkFilter::getPostValue('Email');
        $password = PlonkFilter::getPostValue('Password');

        if ($email !== null && $password !== null) {


            $values = MainController::userExist($email, $password);

            if (!empty($values)) {

                if (strtolower($email) === 'admin') {
                    PlonkSession::set('loggedIn', true);
                    PlonkSession::set('id', $values['userId']);

                    PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
                } else {
                    PlonkSession::set('loggedIn', true);
                    PlonkSession::set('id', $values['userId']);

                    PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
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
        $items = $db->retrieveOne("select userId from users where email = '" . $db->escape($email) . "' AND password ='" . $db->escape($password) . "'");

        // return the result
        return $items;
    }
}
?>
