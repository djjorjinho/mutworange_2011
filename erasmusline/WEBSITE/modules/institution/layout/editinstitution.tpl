<h2>Admin panel</h2>
<p>Here you can edit the Institution data</p>

<div class="mainDiv">
	<form action="" method="post" enctype="multipart/form-data"
		id="editInstitution">
		<fieldset>
			<legend>Edit Institution form</legend>
			<span class="req" id="error">{$error}</span><br />
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Name : </span> </label> <input
					class="validate[required,custom[onlyLetterSp]] text-input"
					type="text" name="institutionname" id="institutionname"
					value="{$institutionName|htmlentities}" /> <span class="req"
					id="msgInstitutionName">{$msgInstitutionName|htmlentities}</span>
			</div>
			
		</fieldset>
		<div class="TRdiv">
			<input type="hidden" name="option" id="option" value="editeducation"></input>
			<input type="hidden" name="hiddenid" id="hiddenid" value="{$hid|htmlentities}"></input>
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
