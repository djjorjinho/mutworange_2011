<?php

class ResidenceController extends PlonkController {

    protected $views = array(
        'detail', 'overview', 'add'
    );
    protected $actions = array(
        'search', 'add'
    );
    protected $variables = array(
        'familyName', 'firstName', 'email', 'telephone', 'mobilePhone', 'streetNr',
        'city', 'postalCode', 'price', 'streetNrResidence', 'cityResidence', 'postalCodeResidence',
        'kitchen', 'bathroom', 'beds', 'water', 'internet', 'television', 'elektricity',
        'countryOwner', 'countryResidence', 'description'
    );
    protected $fields = array();
    protected $errors = array();
    protected $rules = array();

    public function showDetail() {
        $this->checklogged();
        $erasmuslevel = ResidenceDB::getErasmusLevel(PlonkSession::get('id'));
        if ($erasmuslevel['levelName'] == "Accomodation Registration Form") {
            $this->pageTpl->assignOption('oReservation');
        }
        $this->mainTplAssigns();
        if (PlonkFilter::getGetValue('id') == null) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            $residence = ResidenceDB::getResidenceById(PlonkFilter::getGetValue('id'));

            if (!empty($residence)) {
                $this->showResidence($residence);
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
            }
        }
    }

    public function showResidence($residence) {
        $owner = ResidenceDB::getOwnerByResidence($residence['residenceId']);

        foreach ($owner as $key => $value) {
            $this->pageTpl->assign($key, $value);
        }

        foreach ($residence as $key => $value) {
            if ($key == "kitchen" || $key == "bathroom") {
                if ($value == 1) {
                    $value = 'Personal ' . $key;
                } else {
                    $value = 'Communal ' . $key;
                }
            }
            if ($key == "water" || $key == "elektricity" || $key == "television" || $key == "internet") {
                if ($value == 1) {
                    $value = ucfirst($key) . " available, but not included in the price";
                } else if ($value == 2) {
                    $value = ucfirst($key) . " available and included in the price";
                } else {
                    $value = ucfirst($key) . " not available";
                }
            }
            if ($key == "available") {
                if ($value == 1) {
                    $value = 'Available';
                } else {
                    $value = 'Not available';
                }
            }
            $this->pageTpl->assign($key, $value);
        }
    }

    public function showOverview() {
        $this->checkLogged();

        $this->mainTplAssigns();
        $this->fillCountries(PlonkFilter::getGetValue('search'));

        if (PlonkFilter::getGetValue('search') == null) {
            $residences = ResidenceDB::getResidences();
        } else {
            $residences = ResidenceDB::getResidencesByCountry(PlonkFilter::getGetValue('search'));
        }
        if (!empty($residences)) {
            $this->pageTpl->assign('error', '');
            $this->fillResidences($residences);
        } else {
            $this->pageTpl->assign('error', 'No results found');
        }
    }

    private function fillResidences($residences) {

        $this->pageTpl->setIteration('iResidences');

        foreach ($residences as $residence) {
            $this->pageTpl->assignIteration('country', $residence['country']);
            $this->pageTpl->assignIteration('price', $residence['price']);
            $this->pageTpl->assignIteration('city', $residence['city']);
            $this->pageTpl->assignIteration('postalCode', $residence['postalCode']);
            $this->pageTpl->assignIteration('id', $residence['residenceId']);
            $this->pageTpl->refillIteration();
        }
        $this->pageTpl->parseIteration();
    }

    public function doSearch() {
        if (PlonkFilter::getPostValue('btnSearch') == null) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=residence&' . PlonkWebsite::$viewKey . '=overview');
        } else {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=residence&' . PlonkWebsite::$viewKey . '=overview&search=' . PlonkFilter::getPostValue('country'));
        }
    }

    private function mainTplAssigns() {
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/residence.java.tpl');
        $this->mainTpl->assign('pageJava', $java->getContent(true) . '<script type="text/javascript">
	                function lookup(inputString) {
			if(inputString.length == 0) {
				// Hide the suggestion box.
				$(\'#suggestions\').hide();
			} else {
				$.post("modules/residence/rpc.php", {queryString: ""+inputString+""}, function(data){

					if(data.length >0) {
						$(\'#suggestions\').show();
						$(\'#autoSuggestionsList\').html(data);
					}
				});
			}
		} // lookup
		function fill(familyName, firstName, email, streetNr,city,postalCode,telephone,mobilePhone) {
			$(\'#familyName\').val(familyName);
	                $(\'#firstName\').val(firstName);
	                $(\'#email\').val(email);
                         $(\'#streetNr\').val(streetNr);
                          $(\'#city\').val(city);
                           $(\'#postalCode\').val(postalCode);
                            $(\'#telephone\').val(telephone);
                             $(\'#mobilePhone\').val(mobilePhone);
			setTimeout("$(\'#suggestions\').hide();", 200);

		}</script>');
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/css/form.css" type="text/css" />');
        $this->mainTpl->assign('breadcrumb', '');
        $this->mainTpl->assign('siteTitle', 'Residence');
        if (PlonkFilter::getGetValue('search') == null) {
            $this->pageTpl->assign('search', '');
        } else {
            $this->pageTpl->assign('search', PlonkFilter::getGetValue('search'));
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

    public function doAdd() {

        $this->fillRules();

        $this->errors = validateFields($_POST, $this->rules);
        if (!empty($this->errors)) {
            $this->fields = $_POST;
        } else {
            $emailExists = ResidenceDB::userExists(htmlentities(PlonkFilter::getPostValue('email')));
            if (empty($emailExists)) {
                $values = array(
                    'familyName' => htmlentities(PlonkFilter::getPostValue('familyName')),
                    'firstName' => htmlentities(PlonkFilter::getPostValue('firstName')),
                    'email' => htmlentities(PlonkFilter::getPostValue('email')),
                    'streetNr' => htmlentities(PlonkFilter::getPostValue('streetNr')),
                    'city' => htmlentities(PlonkFilter::getPostValue('city')),
                    'postalCode' => htmlentities(PlonkFilter::getPostValue('postalCode')),
                    'tel' => htmlentities(PlonkFilter::getPostValue('telephone')),
                    'mobilePhone' => htmlentities(PlonkFilter::getPostValue('mobilePhone')),
                    'country' => htmlentities(PlonkFilter::getPostValue('countryOwner'))
                );

                ResidenceDB::insert('owner', $values);
            }
            $values = array(
                'price' => htmlentities(PlonkFilter::getPostValue('price')),
                'streetNr' => htmlentities(PlonkFilter::getPostValue('streetNrResidence')),
                'city' => htmlentities(PlonkFilter::getPostValue('cityResidence')),
                'postalCode' => htmlentities(PlonkFilter::getPostValue('postalCodeResidence')),
                'beds' => htmlentities(PlonkFilter::getPostValue('beds')),
                'kitchen' => htmlentities(PlonkFilter::getPostValue('kitchen')),
                'water' => htmlentities(PlonkFilter::getPostValue('water')),
                'elektricity' => htmlentities(PlonkFilter::getPostValue('elektricity')),
                'internet' => htmlentities(PlonkFilter::getPostValue('internet')),
                'bathroom' => htmlentities(PlonkFilter::getPostValue('bathroom')),
                'television' => htmlentities(PlonkFilter::getPostValue('television')),
                'ownerId' => htmlentities(PlonkFilter::getPostValue('email')),
                'country' => htmlentities(PlonkFilter::getPostValue('countryResidence')),
                'available' => 1,
                'description' => htmlentities(PlonkFilter::getPostValue('description'))
            );
            ResidenceDB::insert('residence', $values);
        }
    }

    public function showAdd() {
        $this->mainTplAssigns();
        foreach ($this->variables as $value) {
            if (empty($this->fields)) {
                if ($value === 'kitchen' || $value === 'bathroom' || $value === 'water' || $value === 'internet' || $value === 'television' || $value === 'elektricity') {

                    $this->pageTpl->assign($value . 'Yes', 'checked');
                } else {
                    $this->pageTpl->assign($value, '');
                }

                $this->pageTpl->assign('msg' . ucfirst($value), '*');
            } else {
                if ($value === 'kitchen' || $value === 'bathroom' || $value === 'water' || $value === 'internet' || $value === 'television' || $value === 'elektricity') {

                    if ($this->fields[$value] == 1) {
                        $this->pageTpl->assign($value . 'Yes', 'checked');
                    } else {
                        $this->pageTpl->assign($value . 'No', 'checked');
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
        $countries = ResidenceDB::getCountries();

        $this->pageTpl->setIteration('icountryOwner');
        foreach ($countries as $value) {
            if (!empty($this->fields)) {
                if ($value['Name'] === $this->fields['countryOwner']) {
                    $this->pageTpl->assignIteration('countryOwner', '<option selected=\"true\" value="' . $value['Code'] . '">' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('countryOwner', '<option value="' . $value['Code'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('icountryOwner');
            } else {
                $this->pageTpl->assignIteration('countryOwner', '<option value="' . $value['Code'] . '">' . $value['Name'] . '</option>');
                $this->pageTpl->refillIteration('icountryOwner');
            }
        }
        $this->pageTpl->parseIteration('icountryOwner');

        $this->pageTpl->setIteration('iCountryResidence');
        foreach ($countries as $value) {
            if (!empty($this->fields)) {
                if ($value['Name'] === $this->fields['countryResidence']) {
                    $this->pageTpl->assignIteration('countryResidence', '<option selected=\"true\" value="' . $value['Code'] . '">' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('countryResidence', '<option value="' . $value['Code'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iCountryResidence');
            } else {
                $this->pageTpl->assignIteration('countryResidence', '<option value="' . $value['Code'] . '">' . $value['Name'] . '</option>');
                $this->pageTpl->refillIteration('iCountryResidence');
            }
        }
        $this->pageTpl->parseIteration('iCountryResidence');
    }

    private function fillRules() {
        $this->rules[] = "required,familyName,Last name is required.";
        $this->rules[] = "required,firstName,First name is required.";
        $this->rules[] = "required,email, Email address is required.";
        $this->rules[] = "valid_email,email,Please enter a valid email address";
        $this->rules[] = "required,mobilePhone,Phone number is required";
        $this->rules[] = "required,telephone,Phone number is required";
        $this->rules[] = "required,streetNr,Street + NR = required";
        $this->rules[] = "required,city,City is required";
        $this->rules[] = "required,postalCode,Postal Code is required";
        $this->rules[] = "required,cityResidence,City is required";
        $this->rules[] = "required,price,price is required";
        $this->rules[] = "required,postalCodeResidence,Postal Code is required";
         $this->rules[] = "required,streetNrResidence,Street is required";        
        $this->rules[] = "required,beds,Beds is required";
        $this->rules[] = "required,kitchen,Kitchen is required";
        $this->rules[] = "required,bathroom,Bathroom is required";
        $this->rules[] = "required,water,Water is required";
        $this->rules[] = "required,internet,Internet is required";
        $this->rules[] = "required,television,Television is required";
        $this->rules[] = "required,elektricity,Elektricity is required";
    }

}

?>
