
<div class="mainDiv">
    <h3>Edit personal info</h3>
<form action="" method="post" enctype="multipart/form-data" id="register">	
<fieldset>  
	<div class="TRdiv">
            <label for="familyName"><span>Last Name: </span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="familyName" id="familyName" value="{$familyName|htmlentities}"/>
            <span class="req" id="msgName">{$msgFamilyName|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="firstName"><span>First Name: </span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="firstName" id="firstName" value="{$firstName|htmlentities}"/>
            <span class="req" id="msgFirstName">{$msgFirstName|htmlentities}</span>	
	</div>
        <div class="radioResidences">
                <div class="radioResidence">
            <label for="sex"><span>Gender: </span></label>
            M<input type="radio" {$sexTrue} id="M" name="sex" value="1" class="validate[required] radio" />
            F<input type="radio" {$sexFalse} id="F" name="sex" value="0" class="validate[required] radio" />
            <span class="req" id="msgKitchen">*</span><br />
        </div></div>
        <div class="TRdiv">
            <label for="birthDate"><span>Birth Date:</span></label>
            <input class="validate[required,custom[date]]" text-input" value="{$birthDate|htmlentities}" name="birthDate" id="birthDate" />
            <span class="req" id="msgBirthDate">*</span>
        </div>
        <div class="TRdiv">
            <label for="birthPlace"><span>Birth Place:</span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="birthPlace" id="birthPlace" value="{$birthPlace|htmlentities}"/>
            <span class="req" id="msgBirthPlace">{$msgBirthPlace|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="tel"><span>Telephone:</span></label>
            <input class="validate[required,custom[onlyNumberSp]] text-input" type="text" name="tel" id="tel" value="{$tel|htmlentities}"/>
            <span class="req" id="msgTel">{$msgTel|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="mobilePhone"><span>Mobile Phone:</span></label>
            <input class="validate[required,custom[onlyNumberSp]] text-input" type="text" name="mobilePhone" id="mobilePhone" value="{$mobilePhone|htmlentities}"/>
            <span class="req" id="msgMobilePhone">{$msgMobilePhone|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="streetNr"><span>Street + Nr:</span></label>
            <input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" name="streetNr" id="streetNr" value="{$streetNr|htmlentities}"/>
            <span class="req" id="msgStreetNr">{$msgStreetNr|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="city"><span>City:</span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="city" id="city" value="{$city|htmlentities}"/>
            <span class="req" id="msgCity">{$msgCity|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="postalCode"><span>Postal Code:</span></label>
            <input class="validate[required,maxSize[4]] text-input" type="text" name="postalCode" id="postalCode" value="{$postalCode|htmlentities}"/>
            <span class="req" id="msgPostalCode">{$msgPostalCode|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="country"><span>Nationality:</span></label>
            <select name="country">
               {iteration:iCountries}
                     {$nationality}
               {/iteration:iCountries}
            </select>
            <span class="req" id="msgCountry">{$msgCountry|htmlentities}</span>
	</div>
        <div class="TRdiv">
        <label for="cv"><span>Profile picture:</span></label>
        <input type="file" class="multi" maxlength="1" accept="jpg" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
    </div>
    
    <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formRegister" value="doEdit" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div> 
</fieldset>
           
</form>
</div>