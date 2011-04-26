<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="cert">	
<fieldset>
    <legend>Certificate of Stay</legend>    
    <div class="TRdiv">
            <label for="familyName"><span>Last Name : </span></label>
            <input class="validate[required] text-input" type="text" name="familyName" id="familyName" value="{$familyName|htmlentities}"/>
            <span class="req" id="msgName">{$msgFamilyName|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="firstName"><span>First Name : </span></label>
            <input class="validate[required] text-input" type="text" name="firstName" id="firstName" value="{$firstName|htmlentities}"/>
            <span class="req" id="msgFirstName">{$msgFirstName|htmlentities}</span>	
	</div>
</fieldset>
        <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formCert" value="doSubmit" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div>    
</form>