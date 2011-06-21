<?php

class LoginController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'login', 'logout', 'forgot'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
        'login', 'password'
    );

    public function showLogout() {
        MainController::logout();
    }

    public function doPassword() {
        if (PlonkFilter::getPostValue('Email') == '') {
            PlonkWebsite::redirect('index.php?module=login&view=forgot&error=1');
        } else {

            $email = PlonkFilter::getPostValue('Email');
            $user = LoginDB::getUserByEmail($email);

            if (!empty($user)) {
                $password = Functions::createPassword();
                $this->sendMail($email,$password);
                $values = array('password'=>md5($password));
                LoginDB::updatePassword($values,$email);
                PlonkWebsite::redirect('index.php?module=login&view=forgot&error=2');
            } else {
                PlonkWebsite::redirect('index.php?module=login&view=forgot&error=1');
            }
        }
    }
    public function sendMail($email,$password) {
        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
        $mail->IsSMTP(); // telling the class to use SMTP

        $htmlBody = '<html>
                    <h3>Your new password</h3>
                    <p>Your new password is: '. $password . '</p>
                    </html>';
        try {
            $body = $htmlBody;
            $mail->Host = MAIL_SMTP; // SMTP server
            $mail->SMTPDebug = 2;
            $mail->IsHTML(true);
            $mail->AddAddress($email);
            $mail->SetFrom(MAIL_SENDER, 'ErasmusLine');
            $mail->Subject = MAIL_SUBJECT . ': Password reset';
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

    public function showForgot() {
        if (PlonkFilter::getGetValue('error') === '1') {
            $this->pageTpl->assign('errorMsg', 'Wrong Email');
        }  
        else if (PlonkFilter::getGetValue('error') === '2') {
            $this->pageTpl->assign('errorMsg', 'Your new password has been sent to your emailaddress!');
        } else {
            $this->pageTpl->assign('errorMsg', '');
        }
        $this->mainTplAssigns('Forgot your password');
    }

    /**
     * Assign variables that are main and the same for every view
     * @param	= null
     * @return  = void
     */
    private function mainTplAssigns($pageTitle) {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', $pageTitle);
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('breadcrumb', '');
        $this->mainTpl->assign('pageJava', '');
    }

    public function showLogin() {
        $this->mainTplAssigns('Login');

        $this->checkLogged();

        if (PlonkFilter::getGetValue('error') === '1') {
            $this->pageTpl->assign('errorMsg', 'Username or password is incorrect');
        } else if (PlonkFilter::getGetValue('error') === '2') {
            $this->pageTpl->assign('errorMsg', 'We couldn\'t find you. Is it possible you don\'t have an <a href="index.php?module=register&view=register" title="Create account">account</a> yet?');
        } else {
            $this->pageTpl->assign('errorMsg', '');
        }
    }

    public function doLogin() {

        MainController::Login();
    }

    public function checkLogged() {

        if (PlonkSession::exists('id')) {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            }
        }
    }

}
