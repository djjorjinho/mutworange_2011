<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/>
                    <script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
                    <script type="text/javascript" src="./core/js/datepicker/js/jquery-ui-1.8.9.custom.min.js"></script>
                    
                    <script>
	$(function() {
		$( "#dateFrom" ).datepicker();
	});
        $(function() {
		$( "#dateUntill" ).datepicker();
	});
        $(function() {
		$( "#signInstSignDate" ).datepicker();
	});
        $(function() {
		$( "#signDepSignDate" ).datepicker();
	});
        $(function() {
		$( "#daateFrom" ).datepicker();
	});
        $(function() {
		$( "#daateUntill" ).datepicker();
	});


	</script>
        <script>
                    jQuery(document).ready(function(){
                    // binds form submission and fields to the validation engine
                    jQuery("#studApplicForm").validationEngine();
                    });
                </script>

<script type="text/javascript" src="./core/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.pstrength-min.1.2.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>

                <script>
                jQuery(function($){
                   $("#dateFrom").mask("9999-99-99");
                   $("#dateUntill").mask("9999-99-99");
                   $("#signDepSignDate").mask("9999-99-99");
                   $("#signInstSignDate").mask("9999-99-99");
                })
                </script>
           
                
         <script type="text/javascript">
             $(document).ready(function(){
                var i = $('#languageTable tr').length-4;;
                $('#addLanguage').click(function(){
                    i++;
                    $('#languageCount').val(i);
                    $('#languageTable > tbody:last').append('<tr><td><input class="validate[required,custom[onlyLetterSp]] text-input"  type="text" id="language'+i+'" name="language'+i+'" /></td><td><input type="radio" name="studyThis'+i+'" value="1" class="validate[required] radio" id="een'+i+'" checked /><input type="radio" name="studyThis'+i+'" value="0" id="nul'+i+'"/></td><td><input type="radio" name="knowledgeThis'+i+'" value="1" class="validate[required] radio" id="een2'+i+'" checked /><input type="radio" name="knowledgeThis'+i+'" value="0" id="nul2'+i+'"/></td><td><input type="radio" name="extraPrep'+i+'" value="1" class="validate[required] radio" id="een3'+i+'" checked /><input type="radio" name="extraPrep'+i+'" value="0" id="nul3'+i+'"/></td><td><span class="req">*</span></td></tr>');});
                    
                $('#remLanguage').click(function(){
                    
                    if ($('#languageTable tr').length > 4)
                    {
                        i--;
                        $('#languageTable tr:last').remove();
                        $('#languageCount').val(i);
                    }
                });


            });
            </script>
            
            <script type="text/javascript">
        $(document).ready(function(){
                var i = $('#workTable tr').length-3;;
                $('#addWork').click(function(){
                    i++;
                    $('#workCount').val(i);   
                    $('#workTable > tbody:last').append('<tr><td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="type'+i+'" name="type'+i+'" /></td><td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="firm'+i+'" name="firm'+i+'" /></td><td><input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="date'+i+'" name="date'+i+'" /></td><td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="country'+i+'" name="country'+i+'" /></td><td><span class="req">*</span></td></tr>');});
                     
                $('#remWork').click(function(){
                    if ($('#workTable tr').length > 3)
                    {
                        i--;
                        $('#workTable tr:last').remove();
                        $('#workCount').val(i);
                    }
                });



            });
        

        </script>
