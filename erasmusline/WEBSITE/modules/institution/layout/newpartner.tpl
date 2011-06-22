

<div class="mainDiv">
<h3>Admin panel</h3>
<p>Here you can add a new partner</p>
	<form action="" method="post" enctype="multipart/form-data"
		id="addPartner">
		<fieldset>
			<legend>New partnership form</legend>
			<span class="req" id="error">{$error}</span><br />
			<div class="TRdiv">
				<label for="url"><span>Institution URL : </span> </label> <input
					class="validate[required,custom[url]] text-input"
					type="text" name="url" id="url"
					value="{$url|htmlentities}" /> <span class="req"
					id="msgUrl">{$msgUrl|htmlentities}</span>
			</div>
		</fieldset>
		<div class="TRdiv">
			<input type="hidden" name="option" id="option" value="newpartner"></input>
			<input type="hidden" name="formAction" id="formAddEducation" value="doSubmit"></input> 
			<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"></input>
		</div>
	</form>
</div>
<script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
<script	type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
<script	type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>
<link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"></link>
<script type="text/javascript">
           jQuery(document).ready(function(){
           // binds form submission and fields to the validation engine
           jQuery("#addPartner").validationEngine();
       });
</script>
