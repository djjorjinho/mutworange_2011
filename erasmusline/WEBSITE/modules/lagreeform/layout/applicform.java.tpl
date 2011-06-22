<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/>
                    <script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
                    <script type="text/javascript" src="./core/js/datepicker/js/jquery-ui-1.8.9.custom.min.js"></script>
                    <script type="text/javascript" src="./core/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.pstrength-min.1.2.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>
                    
                    <script>
	jQuery(function() {
		jQuery( "#dateFrom" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#dateUntill" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#signInstSignDate" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#signDepSignDate" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#daateFrom" ).datepicker();
	});
        jQuery(function() {
		jQuery( "#daateUntill" ).datepicker();
	});


	</script>
        <script>
                    jQuery(document).ready(function(){
                    // binds form submission and fields to the validation engine
                    jQuery("#studApplicForm").validationEngine();
                    });
                </script>

                <script>
                jQuery(function(jQuery){
                   jQuery("#dateFrom").mask("9999-99-99");
                   jQuery("#dateUntill").mask("9999-99-99");
                   jQuery("#signDepSignDate").mask("9999-99-99");
                   jQuery("#signInstSignDate").mask("9999-99-99");
                })
                </script>
           
                
         <script type="text/javascript">
             jQuery(document).ready(function(){
                var i = jQuery('#languageTable tr').length-4;;
                jQuery('#addLanguage').click(function(){
                    i++;
                    jQuery('#languageCount').val(i);
                    jQuery('#languageTable > tbody:last').append('<tr><td><input class="validate[required,custom[onlyLetterSp]] text-input"  type="text" id="language'+i+'" name="language'+i+'" /></td><td><input type="radio" name="studyThis'+i+'" value="1" class="validate[required] radio" id="een'+i+'" checked /><input type="radio" name="studyThis'+i+'" value="0" id="nul'+i+'"/></td><td><input type="radio" name="knowledgeThis'+i+'" value="1" class="validate[required] radio" id="een2'+i+'" checked /><input type="radio" name="knowledgeThis'+i+'" value="0" id="nul2'+i+'"/></td><td><input type="radio" name="extraPrep'+i+'" value="1" class="validate[required] radio" id="een3'+i+'" checked /><input type="radio" name="extraPrep'+i+'" value="0" id="nul3'+i+'"/></td><td><span class="req">*</span></td></tr>');});
                    
                jQuery('#remLanguage').click(function(){
                    
                    if (jQuery('#languageTable tr').length > 4)
                    {
                        i--;
                        jQuery('#languageTable tr:last').remove();
                        jQuery('#languageCount').val(i);
                    }
                });


            });
            </script>
            
            <script type="text/javascript">
        jQuery(document).ready(function(){
                var i = jQuery('#workTable tr').length-3;;
                jQuery('#addWork').click(function(){
                    i++;
                    jQuery('#workCount').val(i);   
                    jQuery('#workTable > tbody:last').append('<tr><td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="type'+i+'" name="type'+i+'" /></td><td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="firm'+i+'" name="firm'+i+'" /></td><td><input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="date'+i+'" name="date'+i+'" /></td><td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="country'+i+'" name="country'+i+'" /></td><td><span class="req">*</span></td></tr>');});
                     
                jQuery('#remWork').click(function(){
                    if (jQuery('#workTable tr').length > 3)
                    {
                        i--;
                        jQuery('#workTable tr:last').remove();
                        jQuery('#workCount').val(i);
                    }
                });



            });
        

        </script>
