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
        'submit', 'login','return'
    );
    protected $code;
    protected $id;

    public function doReturn() {
        PlonkWebsite::redirect("index.php?module=home&view=home");
    }
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
            $this->pageTpl->assignOption('oSuccess');
            $array['isValidUser'] = 1;
            RegisterDB::updateUserField($array, 'email = "' . $id . '"');
        } else {
            $this->pageTpl->assignOption('oNoSuccess');
        }
    }

    private function mainTplAssigns() {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', 'Register');
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/register.java.tpl');
        $this->mainTpl->assign('pageJava', $java->getContent(true));
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/css/form.css" type="text/css" />');
        $this->mainTpl->assign('breadcrumb','');
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
        if(PlonkFilter::getGetValue('error') != null) {
            $this->pageTpl->assign('error', "Email already used. Please use another valid email address.");
        }
        else {
            $this->pageTpl->assign('error', "");
        }
        $this->mainTplAssigns();
        $this->fillNationality(PlonkFilter::getPostValue('nationality'));
        $this->checkLogged();

        if (PlonkSession::exists('id')) {
            if (PlonkSession::get('id') == '0') {
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
        $this->fillRules();
        $this->errors = validateFields($_POST, $this->rules);
        if (!empty($this->errors)) {
            $this->fields = $_POST;
        } else {
            $emailExists = RegisterDB::userExists(htmlentities(PlonkFilter::getPostValue('email')));
            if (!empty($emailExists)) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=register&' . PlonkWebsite::$viewKey . '=register&error=1');
            }
            $this->code = Functions::createRandomString();
            $school = RegisterDB::getInstituteId(INSTITUTE);
            $values = array(
                'familyName' => htmlentities(PlonkFilter::getPostValue('familyName')),
                'firstName' => htmlentities(PlonkFilter::getPostValue('firstName')),
                'email' => htmlentities(PlonkFilter::getPostValue('email')),
                'streetNr' => htmlentities(PlonkFilter::getPostValue('street')),
                'city' => htmlentities(PlonkFilter::getPostValue('city')),
                'postalCode' => htmlentities(PlonkFilter::getPostValue('postalCode')),
                'password' => md5(htmlentities(PlonkFilter::getPostValue('password'))),
                'tel' => htmlentities(PlonkFilter::getPostValue('telephone')),
                'mobilePhone' => htmlentities(PlonkFilter::getPostValue('mobilePhone')),
                'birthDate' => htmlentities(PlonkFilter::getPostValue('birthDate')),
                'birthPlace' => htmlentities(PlonkFilter::getPostValue('birthPlace')),
                'country' => htmlentities(PlonkFilter::getPostValue('nationality')),
                'sex' => htmlentities(PlonkFilter::getPostValue('sex')),
                'verificationCode' => $this->code,
                'institutionId' => $school['instEmail'],
                'origin' => 0
            );
            if (PlonkSession::exists('id')) {
                if (PlonkSession::get('id') == '0') {

                    $values['userLevel'] = htmlentities(PlonkFilter::getPostValue('userLevel'));
                    $values['isValidUser'] = 2;
                    RegisterDB::insertUser('users', $values);
                if(!empty($_FILES['pic'])) {
                    $this->upload();
                }
                    PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
                } else {
                    PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
                }
            } else {
                $this->sendMail($values['email'], $values['familyName'], $values['firstName']);

                $values['userLevel'] = htmlentities(PlonkFilter::getPostValue('userLevel'));
                $values['userLevel'] = 'Student';
                $values['isValidUser'] = 0;

                RegisterDB::insertUser('users', $values);
                
                if(!empty($_FILES['pic'])) {
                    $this->upload();
                }

                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=register&' . PlonkWebsite::$viewKey . '=registersucces');
            }
        }
    }

    private function sendMail($email, $familyName, $firstName) {

        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
        $mail->IsSMTP(); // telling the class to use SMTP

        $link = "nathanvanassche.ikdoeict.be/" . $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=register&' . PlonkWebsite::$viewKey . "=registervalidemail&id=" . $email . "&string=" . $this->code;

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
        if (!PlonkSession::exists('id')) {
            $this->pageTpl->assignOption('oNotLogged');
        } else {
            if (PlonkSession::get('id') == 0) {
                $this->id = PlonkSession::get('id');
                $this->pageTpl->assignOption('oAdmin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->id = PlonkSession::get('id');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
            }
        }
    }

    private function fillUserLevel($userLevel = '') {
        $userLevels = array('Teaching staff', 'Erasmus coordinator', 'Higher Education institution', 'Industrial institution', 'International relations office staff');
        $this->pageTpl->setIteration('iUserLevel');
        $i = 1;
        foreach ($userLevels as $value) {
            if ($userLevel == $value) {
                $this->pageTpl->assignIteration('userLevel', '<option selected=\"true\" value=' . $value . '> ' . $value . '</option>');
            } else {
                $this->pageTpl->assignIteration('userLevel', '<option value="' . $value . '"> ' . $value . '</option>');
            }
            $this->pageTpl->refillIteration('iUserLevel');
            $i++;
        }
        $this->pageTpl->parseIteration('iUserLevel');
    }
    
    private function upload() {
        
        mkdir("files/" . PlonkFilter::getPostValue('email') . '/',0777);
        $uploaddir = "files/" . PlonkFilter::getPostValue('email') . "/";

        foreach ($_FILES["pic"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pic"]["tmp_name"][$key];
                $name = 'profile.jpg';
                $uploadfile = $uploaddir . basename($name);

                if (move_uploaded_file($tmp_name, $uploadfile)) {
                    $cover = $uploadfile;
                } else {
                    echo "Error: File " . $name . " cannot be uploaded.<br/>";
                }
            }
        }
    }

}

?>
