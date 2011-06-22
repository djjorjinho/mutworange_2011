<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include './modules/profile/profile.db.php';

class MainController extends PlonkController {

    public function assignMain() {
        $no = MainController::checkLogged();
        if ($no != "nono") {
            $this->mainTpl->assign('progress', MainController::fillProgress());
        }
    }

    private function fillProgress() {
        $id;
        if (PlonkSession::exists('id')) {
            $id = PlonkSession::get('id');
        }
        $erasmuslevel = ProfileDB::getErasmusById($id);
        $erasmusLevel = 0;

        if (!empty($erasmuslevel)) {
            
            if ($erasmuslevel['statusOfErasmus'] == 'Precandidate') {
                if ($erasmuslevel['action'] == 2)
                    $erasmusLevel = 10;
                if ($erasmuslevel['action'] == 1)
                    $erasmusLevel = 20;
            }
            else if ($erasmuslevel['statusOfErasmus'] == 'Student Application and Learning Agreement') {
                if ($erasmuslevel['action'] == 22)
                    $erasmusLevel = 30;
                if ($erasmuslevel['action'] == 21 || $erasmuslevel['action'] == 12 || $erasmuslevel['action'] == 10 || $erasmuslevel['action'] == 1)
                    $erasmusLevel = 40;
                if ($erasmuslevel['action'] == 11)
                    $erasmusLevel = 50;
                else
                    $erasmusLevel = 20;
            }
            else if ($erasmuslevel['statusOfErasmus'] == 'Accomodation Registration Form') {
                
                    $erasmusLevel = 60;
            }
            else if($erasmuslevel['statusOfErasmus'] == 'Certificate of Arrival') {
                $erasmusLevel = 70;
            }
            else if($erasmuslevel['statusOfErasmus'] == 'Certficate of Departure') {
                $erasmuslevel = 90;
            }
            else if($erasmuslevel['statusOfErasmus'] == "Evaluation Questionaire") {
                $erasmuslevel = 100;
            }
            
            else {
                $erasmusLevel = 75;
            }
        }
        return $erasmusLevel;
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
                    PlonkSession::set('userLevel', "plom");
                    PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
                } else {

                    PlonkSession::set('id', $values['email']);
                    PlonkSession::set('userLevel', $values['userLevel']);

                    if (PlonkSession::get('id') === 0) {

                        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
                    } else if (PlonkSession::get('userLevel') == 'Student') {

                        PlonkWebsite::redirect('index.php?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
                    } else if (PlonkSession::get('userLevel') == "International Relations Office Staff") {
                        PlonkWebsite::redirect('index.php?module=office&view=office');
                    } else {
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
                $this->mainTpl->assignOption('oProfile');
            } else if (PlonkSession::get('userLevel') == 'Erasmus Coordinator') {
                $this->mainTpl->assignOption('oCoor');
                $this->mainTpl->assignOption('oProfile');
            } else if (PlonkSession::get('userLevel') == 'Teaching Staff') {
                $this->mainTpl->assignOption('oTeacher');
                $this->mainTpl->assignOption('oProfile');
            } else if (PlonkSession::get('userLevel') == 'International Relations Office Staff') {
                $this->mainTpl->assignOption('oOffice');
                $this->mainTpl->assignOption('oProfile');
            } else if (PlonkSession::get('userLevel') == 'Industrial Institution') {
                $this->mainTpl->assignOption('oIndustrial');
            }
        } else {
            $this->mainTpl->assignOption('oNotLogged');
            return 'nono';
        }
    }

}

?>
