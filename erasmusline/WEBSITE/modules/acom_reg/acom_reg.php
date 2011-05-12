<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class acom_regController extends PlonkController {

    protected $views = array(
        'acom_reg'
    );
    protected $actions = array(
        'next'
    );
    protected $variables = array(
        'hostInstitution', 'dateArrival', 'dateDeparture', 'student'
    );
    private $error = '';
    private $position = '1';

    public function showacom_reg() {
        // Assign main properties
        $this->mainTpl->assign('pageTitle', 'Accomodation Registration');
$this->mainTpl->assign('pageMeta', '<script src="./core/js/jquery/jquery-1.5.js" type="text/javascript"></script>
        <script src="./core/js/jquery/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"> </script>
        <script src="./core/js/jquery/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
        <script src="./core/js/custom.js" type="text/javascript" charset="utf-8"> </script><script src="./core/js/sorttable.js" type="text/javascript"></script>');
        $this->mainTpl->assign('pageCSS', '<link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/>');

        $this->pageTpl->assign('errorString', $this->error);
        $this->error = '';

        if ($this->position == '1') {
            $this->pageTpl->assignOption('showSelectAccommodationYN');
            $this->getDBdata(PlonkSession::get('id'));
        }

        if ($this->position == '2yes') {
            $this->mainTpl->assign('pageMeta', '');
            $this->mainTpl->assign('pageCSS', '');

            $this->pageTpl->assignOption('showSelectAccomodation');
        }
    }

    public function doNext() {
        if ($this->position == '1') {
            if (empty($_POST['option1'])) {
                $this->error = '<div class="errorPHP">Please Select An Option</div>';
            } elseif ($_POST['option1'] == 'con') {
                $this->position = '2yes';
                echo 'tes';
            } elseif ($_POST['option1'] == 'nocon') {
                $this->position = '2no';
            }
        }
    }

    public function getDBdata($name) {


        $query = acom_regDB::getStudentInfo($name);
        foreach ($query as $key => $value) {
            $this->pageTpl->assign('stFirstName', $value['firstName']);
            $this->pageTpl->assign('stLastName', $value['familyName']);
            $this->pageTpl->assign('stGender', $value['sex'] > 0 ? 'Male' : 'Female');
            $this->pageTpl->assign('stMail', $value['email']);

            $query2 = acom_regDB::getInstInfo($query[0]['homeInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('reInName', $value['instName']);
            }
            $query2 = acom_regDB::getInstInfo($query[0]['hostInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('seInName', $value['instName']);
            }
        }
    }

}

?>
