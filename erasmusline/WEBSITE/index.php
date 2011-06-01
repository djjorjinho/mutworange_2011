<?php


// config & functions
require_once './core/includes/config.php';
require_once './core/includes/functions.php';

// Include Plonk & PlonkWebsite
require_once './library/plonk/plonk.php';
require_once './library/plonk/website/website.php';
require_once './library/plonk/filter/filter.php';
require_once './library/phpmailer/class.phpmailer.php';
require_once './library/validation/validation.php';

// ... require other classes if needed.


$website = new PlonkWebsite(
		array(
		'home','about','register','info','admin','profile', 'abroad_stay',
		'lagreeform','pre_leave','precandidate','teardown_finish','login', 
		'staff', 'infox', 'residence','stats','course_matching','extend','learnagr_ch',
                    'trrec','acom_reg', 'office'));

?>
