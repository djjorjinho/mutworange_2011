<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/>
                    <script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
                    <script type="text/javascript" src="./core/js/datepicker/js/jquery-ui-1.8.9.custom.min.js"></script>
                    <script>
	$(function() {
		$( "#signDate" ).datepicker();
	});
        $(function() {
		$( "#signDepSignDate" ).datepicker();
	});
        $(function() {
		$( "#signInstSignDate" ).datepicker();
	});
        $(function() {
		$( "#signDepSignDate2" ).datepicker();
	});
        $(function() {
		$( "#signInstSignDate2" ).datepicker();
	});
        $(function() {
		$( "#date" ).datepicker();
	});

	</script>
        <script>
                    jQuery(document).ready(function(){
                    // binds form submission and fields to the validation engine
                    jQuery("#changagreement").validationEngine();
                    });
                </script>

<script type="text/javascript" src="./core/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.pstrength-min.1.2.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
                <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>
                <link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/>

                <script>
                jQuery(function($){
                   $("#signDate").mask("9999-99-99");
                   $("#date").mask("9999-99-99");
                   $("#signDepSignDate").mask("9999-99-99");
                   $("#signInstSignDate").mask("9999-99-99");
                   $("#signDepSignDate2").mask("9999-99-99");
                   $("#signInstSignDate2").mask("9999-99-99");
                })
                </script>
                
                <script type="text/javascript">
                    alert('sdffsqdf');
             $(document).ready(function(){
                
                var i = $('#coursesTable tr').length-3;;
                $('#addCourse').click(function(){
                    i++;
                  $('#courseCount').val(i);
                    $('#coursesTable > tbody:last').append('<tr><td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="code'+i+'" name="code'+i+'" /></td><td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="title'+i+'" name="title'+i+'"  /></td><td><input type="radio" name="delOrAdd'+i+'" value="1" class="validate[required] radio" id="een2'+i+'" checked /><input type="radio" name="delOrAdd'+i+'" value="0" id="nul2'+i+'" checked></td><td><input class="validate[required,custom[onlyNumberSp]]" type="text" id="ects'+i+'" name="ects'+i+'"  /></td><td><span class="req">*</span></td> </tr>');});
                $('#remCourse').click(function(){
                    
                    if ($('#coursesTable tr').length > 4)
                    {
                        i--;
                        $('#coursesTable tr:last').remove();
                        $('#courseCount').val(i);
                        
                    }
                });


            });
            </script>