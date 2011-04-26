<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class RegisterController extends PlonkController {

    protected $fields = array();
    protected $errors = array();
    protected $rules = array();
    protected $views = array(
        'register', 'registersucces', 'registervalidemail'
    );
    protected $variables = array(
        'familyName', 'firstName', 'email', 'password', 'password2', 'sex',
        'birthDate', 'birthPlace', 'telephone', 'mobilePhone', 'street',
        'city', 'postalCode', 'nationality'
    );
    protected $actions = array(
        'submit'
    );
    protected $code;
    protected $id;

    public function showRegistersucces() {
        $this->checkLogged();
        $this->mainTplAssigns();
    }

    public function showRegistervalidemail() {
        $this->checkLogged();
        $this->mainTplAssigns();
        $id = PlonkFilter::getGetValue('id');
        $string = PlonkFilter::getGetValue('string');

        $user = RegisterDB::getUserById($id);
        if ($string === $user['verificationCode']) {
            $this->pageTpl->assignOption('oTest');
            $array['isValidUser'] = true;
            RegisterDB::updateUserField($array, 'userId = ' . (int) $id);
        }
    }

    private function mainTplAssigns() {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', 'ErasmusLine');
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/register.java.tpl');
        $this->mainTpl->assign('pageMeta', $java->getContent(true));
        $this->mainTpl->assign('pageCSS', '');
    }

    private function fillNationality($nationality = '') {
        $countries = RegisterDB::getCountries();
        try {
            $this->pageTpl->setIteration('iCountries');
            foreach ($countries as $key => $value) {
                if ($nationality == $value['Code']) {
                    $this->pageTpl->assignIteration('nationality', '<option selected=\"true\" value=' . $value['Code'] . '> ' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('nationality', '<option value="' . $value['Code'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iCountries');
            }
            $this->pageTpl->parseIteration('iCountries');
        } catch (Exception $e) {
            
        }
    }

    private function fillRules() {
        $this->rules[] = "required,familyName,Last name is required.";
        $this->rules[] = "required,firstName,First name is required.";
        $this->rules[] = "required,email, Email address is required.";
        $this->rules[] = "valid_email,email,Please enter a valid email address";
        $this->rules[] = "required,password,Password is required";
        $this->rules[] = "length>8,password,Password minimum 8 characters";
        $this->rules[] = "same_as,password,password2,Passwords do not match.";
        $this->rules[] = "required,birthPlace,Birthplace is required";
        $this->rules[] = "required,mobilePhone,Phone number is required";
        $this->rules[] = "required,telephone,Phone number is required";
        $this->rules[] = "required,street,Street + NR = required";
        $this->rules[] = "required,city,City is required";
        $this->rules[] = "required,postalCode,Postal Code is required";
        $this->rules[] = "digits_only,postalCode,Only digits allowed in Postal Code";
        $this->rules[] = "required,nationality,Choose a country";
        $this->rules[] = "required,password2,Password 2 required";
        $this->rules[] = "required,birthDate,Date wrong format";
    }

    public function showRegister() {
        $this->mainTplAssigns();
        $this->fillNationality(PlonkFilter::getPostValue('nationality'));
        $this->checkLogged();
        
        
        if (PlonkSession::exists('loggedIn')) {    
            if (PlonkSession::get('id') === '1') {                 
                $this->variables[] = 'userLevel';
                $this->fillUserLevel(PlonkFilter::getPostValue('userLevel'));
            }
        }        

        foreach ($this->variables as $value) {
            if (empty($this->fields)) {
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, '');
                $this->pageTpl->assign('sexTrue', 'checked');
            } else {
                if ($value === 'sex') {
                    if ($this->fields[$value] == '1') {
                        $this->pageTpl->assign($value . 'True', 'checked');
                    } else {
                        $this->pageTpl->assign($value . 'False', 'checked');
                    }
                }
                if (!array_key_exists($value, $this->errors)) {
                    $this->pageTpl->assign('msg' . ucfirst($value), '');
                    $this->pageTpl->assign($value, $this->fields[$value]);
                } else {
                    $this->pageTpl->assign('msg' . ucfirst($value), $this->errors[$value]);
                    $this->pageTpl->assign($value, $this->fields[$value]);
                }
            }
        }
    }

    public function doSubmit() {
        $test = RegisterDB::getMaxId('users', 'userId');
        $this->id = $test['MAX(userId)'] + 1;
        $this->fillRules();
        $this->errors = validateFields($_POST, $this->rules);
        if (!empty($this->errors)) {
            $this->fields = $_POST;
        } else {
            $this->code = Functions::createRandomString();
            $values = array(
                'familyName' => htmlentities(PlonkFilter::getPostValue('familyName')),
                'firstName' => htmlentities(PlonkFilter::getPostValue('firstName')),
                'email' => htmlentities(PlonkFilter::getPostValue('email')),
                'street' => htmlentities(PlonkFilter::getPostValue('street')),
                'city' => htmlentities(PlonkFilter::getPostValue('city')),
                'postalCode' => htmlentities(PlonkFilter::getPostValue('postalCode')),
                'password' => htmlentities(PlonkFilter::getPostValue('password')),
                'tel' => htmlentities(PlonkFilter::getPostValue('telephone')),
                'mobilePhone' => htmlentities(PlonkFilter::getPostValue('mobilePhone')),
                'birthDate' => htmlentities(PlonkFilter::getPostValue('birthDate')),
                'birthPlace' => htmlentities(PlonkFilter::getPostValue('birthPlace')),
                'code' => htmlentities(PlonkFilter::getPostValue('nationality')),
                'sex' => htmlentities(PlonkFilter::getPostValue('sex')),
                'verificationCode' => $this->code,
                'userLevel' => '1'
            );
            $db = PlonkWebsite::getDB();
            if (!PlonkSession::get('id') === '1') {
                $this->sendMail($values['email'], $values['familyName'], $values['firstName']);
                $insertId = $db->insert('users', $values);
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=register&' . PlonkWebsite::$viewKey . '=registersucces');
            } else {
                $values['userLevel'] = htmlentities(PlonkFilter::getPostValue('userLevel'));
                $insertId = $db->insert('users', $values);
            }
        }
    }

    private function sendMail($email, $familyName, $firstName) {

        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
        $mail->IsSMTP(); // telling the class to use SMTP

        $link = $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=register&' . PlonkWebsite::$viewKey . "=registervalidemail&id=" . $this->id . "&string=" . $this->code;

        $htmlBody = '<html>
                    <h1>Test</h1>
                    <a href=' . $link . '>Klik hier</a>
                    </html>';
        try {
            $body = $htmlBody;
            $mail->Host = MAIL_SMTP; // SMTP server
            $mail->SMTPDebug = 2;
            $mail->IsHTML(true);
            $mail->AddAddress($email, $familyName . ' ' . $firstName);
            $mail->SetFrom(MAIL_SENDER, 'ErasmusLine');
            $mail->Subject = MAIL_SUBJECT;
            $mail->Port = 25;
            //$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
            $mail->MsgHTML($body);
            $mail->Send();
        } catch (phpmailerException $e) {
            // $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            //echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function checkLogged() {

        if (!PlonkSession::exists('loggedIn')) {
            $this->mainTpl->assignOption('oNotLogged');
            $this->pageTpl->assignOption('oNotLogged');
        } else {
            if (PlonkSession::get('id') === '1') {
                $this->pageTpl->assignOption('oAdmin');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }

            $this->mainTpl->assignOption('oLogged');
            $this->pageTpl->assignOption('oLogged');
        }
    }

    private function fillUserLevel($userLevel = '') {
        $userLevels = ('student');
        $this->pageTpl->setIteration('iUserLevel');
        $i = 1;
        foreach ($userLevels as $value) {
            if ($userLevel == $value) {
                $this->pageTpl->assignIteration('userLevel', '<option selected=\"true\" value=' . $value . '> ' . $value . '</option>');
            } else {
                $this->pageTpl->assignIteration('userLevel', '<option value=' . $i . '> ' . $value . '</option>');
            }
            $this->pageTpl->refillIteration('iUserLevel');
            $i++;
        }
        $this->pageTpl->parseIteration('iUserLevel');
    }

}

?>
