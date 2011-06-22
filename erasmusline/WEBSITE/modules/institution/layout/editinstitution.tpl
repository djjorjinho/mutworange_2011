
<div class="mainDiv">
<h3>Admin panel</h3>
<p>Here you can edit the Institution data</p>

	<form action="" method="post" enctype="multipart/form-data"
		id="editInstitution">
		<fieldset>
			<legend>Edit Institution form</legend>
			<span class="req" id="error">{$error}</span><br />
			<div class="TRdiv">
				<label for="coursecode"><span>Institution Email : </span> </label> <input
					class="text-input"
					disabled="disabled" type="text" name="institutionEmail" id="institutionEmail"
					value="{$institutionEmail|htmlentities}" />
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Name : </span> </label>
				<input class="validate[required,custom[onlyLetterSp]] text-input"
					type="text" name="institutionName" id="institutionName"
					value="{$institutionName|htmlentities}" /> <span class="req"
					id="msgInstitutionName">{$msgInstitutionName|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Street Number : </span>
				</label> <input
					class="validate[required] text-input"
					type="text" name="institutionStrNr" id="institutionStrNr"
					value="{$institutionStrNr|htmlentities}" /> <span class="req"
					id="msgInstitutionStrNr">{$msgInstitutionStrNr|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution City : </span> </label>
				<input class="validate[required,custom[onlyLetterSp]] text-input"
					type="text" name="institutionCity" id="institutionCity"
					value="{$institutionCity|htmlentities}" /> <span class="req"
					id="msgInstitutionCity">{$msgInstitutionCity|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Postal Code : </span>
				</label> <input
					class="validate[required] text-input"
					type="text" name="institutionPostalCode" id="institutionPostalCode"
					value="{$institutionPostalCode|htmlentities}" /> <span class="req"
					id="msgInstitutionPostalCode">{$msgInstitutionPostalCode|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Country : </span> </label>
				<input class="validate[required,custom[onlyLetterSp]] text-input"
					type="text" name="institutionCountry" id="institutionCountry"
					value="{$institutionCountry|htmlentities}" /> <span class="req"
					id="msgInstitutionCountry">{$msgInstitutionCountry|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Tel : </span> </label>
				<input class="validate[required,custom[onlyNumberSp]] text-input"
					type="text" name="institutionTel" id="institutionTel"
					value="{$institutionTel|htmlentities}" /> <span class="req"
					id="msgInstitutionTel">{$msgInstitutionTel|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Fax : </span> </label>
				<input class="validate[required,custom[onlyNumberSp]] text-input"
					type="text" name="institutionFax" id="institutionFax"
					value="{$institutionFax|htmlentities}" /> <span class="req"
					id="msgInstitutionFax">{$msgInstitutionFax|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Description : </span>
				</label> <input
					class="validate[required] text-input"
					type="text" name="institutionDesc" id="institutionDesc"
					value="{$institutionDesc|htmlentities}" /> <span class="req"
					id="msgInstitutionDesc">{$msgInstitutionDesc|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Website : </span> </label>
				<input class="validate[required] text-input"
					type="text" name="institutionWeb" id="institutionWeb"
					value="{$institutionWeb|htmlentities}" /> <span class="req"
					id="msgInstitutionWeb">{$msgInstitutionWeb|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Type : </span> </label>
				<input class="validate[required,custom[onlyNumberSp]] text-input"
					type="text" name="institutionType" id="institutionType"
					value="{$institutionType|htmlentities}" /> <span class="req"
					id="msgInstitutionType">{$msgInstitutionType|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Url : </span> </label>
				<input class="validate[required] text-input"
					type="text" name="institutionUrl" id="institutionUrl"
					value="{$institutionUrl|htmlentities}" /> <span class="req"
					id="msgInstitutionUrl">{$msgInstitutionUrl|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Grade : </span>
				</label> <input
					class="validate[required,custom[onlyNumberSp]] text-input"
					type="text" name="institutionScale" id="institutionScale"
					value="{$institutionScale|htmlentities}" /> <span class="req"
					id="msgInstitutionScale">{$msgInstitutionScale|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Digital : </span> </label>
				<input class="validate[required,custom[onlyNumberSp]] text-input"
					type="text" name="institutionDigital" id="institutionDigital"
					value="{$institutionDigital|htmlentities}" /> <span class="req"
					id="msgInstitutionDigital">{$msgInstitutionDigital|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution iBan : </span> </label>
				<input class="validate[required] text-input"
					type="text" name="institutionIban" id="institutionIban"
					value="{$institutionIban|htmlentities}" /> <span class="req"
					id="msgInstitutionIban">{$msgInstitutionIban|htmlentities}</span>
			</div>
			<div class="TRdiv">
				<label for="institutionname"><span>Institution Bic : </span> </label>
				<input class="validate[required] text-input"
					type="text" name="institutionBic" id="institutionBic"
					value="{$institutionBic|htmlentities}" /> <span class="req"
					id="msgInstitutionBic">{$msgInstitutionBic|htmlentities}</span>
			</div>

		</fieldset>
		<div class="TRdiv">
			<input type="hidden" name="option" id="option" value="editinstitution"></input>
			<input type="hidden" name="hiddenid" id="hiddenid"
				value="{$hid|htmlentities}"></input> <input type="hidden"
				name="formAction" id="formEditInstitution" value="doSubmit"></input> <input
				class="button" name="btnSend" id="btnSend" type="submit"
				value="Submit"></input>
		</div>
	</form>
</div>

<script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>

<script
	type="text/javascript"
	src="./core/js/jquery/jquery.validationEngine.js"></script>
<script
	type="text/javascript"
	src="./core/js/jquery/jquery.validationEngine-en.js"></script>
<link
	rel="stylesheet" href="./core/css/validationEngine.jquery.css"
	type="text/css"></link>
<script type="text/javascript">
           jQuery(document).ready(function(){
           // binds form submission and fields to the validation engine
           jQuery("#editInstitution").validationEngine();
       });
</script>
