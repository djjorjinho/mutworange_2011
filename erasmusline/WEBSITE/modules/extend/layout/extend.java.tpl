<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/>
                    <script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
                    <script type="text/javascript" src="./core/js/datepicker/js/jquery-ui-1.8.9.custom.min.js"></script>
                    <script>
	jQuery(function() {
		jQuery( "#from" ).datepicker();
	});
        $(function() {
		jQuery( "#until" ).datepicker();
	});
 </script>
        <script type="text/javascript" src="core/js/jquery/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="core/js/jquery/jquery.validationEngine-en.js"></script>
        <link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/>
        <script type="text/javascript">
           jQuery(document).ready(function(){
           // binds form submission and fields to the validation engine
           jQuery("#extend").validationEngine();
       });
</script> 
