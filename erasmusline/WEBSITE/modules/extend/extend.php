<?php

class ExtendController extends PlonkController {

    protected $views = array(
        'extend'
    );
    protected $actions = array(
        'submit'
    );
    protected $variables = array(
        'from', 'until', 'notes', 'months'
    );
    
    protected $errors = array();
    protected $rules = array();
    protected $fields = array();

    public function showExtend() {
        $this->checklogged();
        $this->mainTplAssigns();
        $this->fillUser();
        $this->fillVariables();
    }

    private function mainTplAssigns() {
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/extend.java.tpl');
        $this->pageTpl->assign('pageJava', $java->getContent(true));
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('pageJava', '');
        $this->mainTpl->assign('siteTitle', 'Extend Mobility Period');
        $this->pageTpl->assign('from', date('Y-m-d'));
        $this->pageTpl->assign('until', date('Y-m-d'));
    }

    private function checklogged() {
        if (PlonkSession::exists('id')) {
            
        } else {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        }
    }

    private function fillUser() {
        $this->pageTpl->assign('months', '');
        $id = PlonkSession::get('id');
        $user = ExtendDB::getUserById($id);
        $this->pageTpl->assign('familyName', $user['familyName']);
        $this->pageTpl->assign('firstName', $user['firstName']);
        $this->pageTpl->assign('startDate', $user['startDate']);
        $this->pageTpl->assign('endDate', $user['endDate']);

        $host = ExtendDB::getHostInstitution($id);
        $this->pageTpl->assign('hostinstitution', $host['instName']);

        $home = ExtendDB::getHomeInstitution($id);
        $this->pageTpl->assign('homeInstitution', $home['instName']);

        $education = ExtendDB::getEducation($id);
        $this->pageTpl->assign("education", $education['educationName']);
    }

    private function fillRules() {
        $this->rules[] = "required,from,Date From is required.";
        $this->rules[] = "required,until,Date Until is required.";
        $this->rules[] = "required,months,# Months is required.";
        $this->rules[] = "required,notes,Nots is required";
    }

    private function fillVariables() {
        foreach ($this->variables as $value) {
            if (empty($this->fields)) {
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, '');
            } else {
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
        $this->fields = $_POST;
        $this->errors = validateFields($this->fields, $this->rules);    
    }

}

?>
