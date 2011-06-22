<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/>
                    <script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
                    <script type="text/javascript" src="./core/js/datepicker/js/jquery-ui-1.8.9.custom.min.js"></script>
                    <script type="text/javascript" src="./core/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.pstrength-min.1.2.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>
                <link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/>
                    <script>
	jQuery(function() {
		jQuery( "#signDate" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#signDepSignDate" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#signInstSignDate" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#signDepSignDate2" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#signInstSignDate2" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#date" ).datepicker();
	});

	</script>
        <script>
                    jQuery(document).ready(function(){
                    // binds form submission and fields to the validation engine
                    jQuery("#lagreement").validationEngine();
                    });
                </script>

                <script>
                jQuery(function(jQuery){
                   jQuery("#signDasdfte").mask("9999-99-99");
                   jQuery("#date").mask("9999-99-99");
                   jQuery("#signDepSignDate").mask("9999-99-99");
                   jQuery("#signInstSignDate").mask("9999-99-99");
                   jQuery("#signDepSignDate2").mask("9999-99-99");
                   jQuery("#signInstSignDate2").mask("9999-99-99");
                })
                </script>
                
                <script type="text/javascript">
             jQuery(document).ready(function(){
                var i = jQuery('#coursesTable tr').length-3;;
                jQuery('#addCourse').click(function(){
                    i++;
                    jQuery('#courseCount').val(i);
                    jQuery('#coursesTable > tbody:last').append('<tr> <td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="code'+i+'" name="code'+i+'" /></td><td><input onkeyup="lookup('+i+',this.value);" onclick="fill();" class="validate[required, custom[onlyLetterNumber]]" type="text" id="title'+i+'" name="title'+i+'" /><div class="suggestionsBox'+i+'" id="suggestions'+i+'" style="display: none;"><div class="suggestionList'+i+'" id="autoSuggestionsList'+i+'">&nbsp;</div></div></td><td><input class="validate[required,custom[onlyNumberSp]]" type="text" id="ects'+i+'" name="ects'+i+'" /></td><td><span class="req">*</span></td></tr>');});
                jQuery('#remCourse').click(function(){
                    
                    if (jQuery('#coursesTable tr').length > 2)
                    {
                        i--;
                        jQuery('#courseCount').val(i);
                        jQuery('#coursesTable tr:last').remove();
                        
                    }
                });


            });
            </script>