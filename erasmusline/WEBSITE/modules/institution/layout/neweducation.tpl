

<div class="mainDiv">
<h3>Admin panel</h3>
<p>Here you can add a new education</p>
	<form action="" method="post" enctype="multipart/form-data"
		id="addEducation">
		<fieldset>
			<legend><h3>New education form</h3></legend>
			<span class="req" id="error">{$error}</span><br />
			<div class="TRdiv">
				<label for="educationname"><span>Education Name : </span> </label> <input
					class="validate[required,custom[onlyLetterSp]] text-input"
					type="text" name="educationname" id="educationname"
					value="{$educationName|htmlentities}" /> <span class="req"
					id="msgEducationName">{$msgEducationName|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="educationdesc"><span>Education Description : </span> </label>
				<textarea class="validate[required],custom[textarea]" type="text"
					name="educationdesc" id="educationdesc" cols="33" rows="3">{$educationDesc|htmlentities}</textarea>
				<span class="req" id="msgEducationDesc">{$msgEducationDesc|htmlentities}</span>
			</div>
		</fieldset>
		<div class="TRdiv">
			<input type="hidden" name="option" id="option" value="neweducation"></input>
			<input type="hidden" name="formAction" id="formAddEducation" value="doSubmit"></input> 
			<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"></input>
		</div>
	</form>
</div>



<script	type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
<script	type="text/javascript" src="./core/js/progressbar/js/jquery.progressbar.js"></script>
<script	type="text/javascript" src="./core/js/jquery/jquery.pstrength-min.1.2.js"></script>
<script	type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
<script	type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>
<script	type="text/javascript" src="./core/js/jquery/jquery.MultiFile.js"></script>
<link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"></link>
<script type="text/javascript">
           jQuery(document).ready(function(){
           // binds form submission and fields to the validation engine
           jQuery("#register").validationEngine();
       });
</script>
