<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="register">	
<fieldset>
    <legend>Register form</legend>     
    <span class="req" id="error">{$error}</span><br />
	<div class="TRdiv">
            <label for="familyName"><span>Last Name : </span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="familyName" id="familyName" value="{$familyName|htmlentities}"/>
            <span class="req" id="msgName">{$msgFamilyName|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="firstName"><span>First Name : </span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="firstName" id="firstName" value="{$firstName|htmlentities}"/>
            <span class="req" id="msgFirstName">{$msgFirstName|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="email"><span>Email address : </span></label>
            <input class="validate[required,custom[email]] text-input" type="text" name="email" id="email" value="{$email|htmlentities}"/>  
            <span class="req" id="msgEmail">{$msgEmail|htmlentities}</span>
	</div>
        <div class="TRdiv">
            <label for="password"><span>Password : </span></label>
            <input value="{$password|htmlentities}" class="validate[required,custom[onlyLetterNumber]] text-input" type="password" name="password" id="password" />
            <span class="req" id="msgPassword">{$msgPassword|htmlentities}</span>	
              <script type="text/javascript">
                jQuery('#password').pstrength();
            </script>  
        </div>
        <div class="TRdiv">
            <label for="password2"><span>Confirm password : </span></label>
            <input value="{$password2|htmlentities}" class="validate[required,equals[password],custom[onlyLetterNumber]] text-input" type="password" name="password2" id="password2" />
            <span class="req" id="msgPassword2">{$msgPassword2|htmlentities}</span>	
       </div>  
        <div class="radioResidences">
                <div class="radioResidence">
            <label for="sex"><span>Gender : </span></label>
            M<input type="radio" {$sexTrue} id="M" name="sex" value="1" class="validate[required] radio" />
            F<input type="radio" {$sexFalse} id="F" name="sex" value="0" class="validate[required] radio" />
            <span class="req" id="msgKitchen">*</span><br />
        </div></div>
        <div class="TRdiv">
            <label for="birthDate"><span>Birth Date</span></label>
            <input class="validate[required,custom[date]]" text-input" value="{$birthDate|htmlentities}" name="birthDate" id="birthDate" />
        </div>
        <div class="TRdiv">
            <label for="birthPlace"><span>Birth Place</span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="birthPlace" id="birthPlace" value="{$birthPlace|htmlentities}"/>
            <span class="req" id="msgBirthPlace">{$msgBirthPlace|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="telephone"><span>Telephone</span></label>
            <input class="validate[required,custom[onlyNumberSp]] text-input" type="text" name="telephone" id="telephone" value="{$telephone|htmlentities}"/>
            <span class="req" id="msgTelephone">{$msgTelephone|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="mobilePhone"<span>Mobile Phone</span></label>
            <input class="validate[required,custom[onlyNumberSp]] text-input" type="text" name="mobilePhone" id="mobilePhone" value="{$mobilePhone|htmlentities}"/>
            <span class="req" id="msgMobilePhone">{$msgMobilePhone|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="street"><span>Street + Nr</span></label>
            <input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" name="street" id="street" value="{$street|htmlentities}"/>
            <span class="req" id="msgStreet">{$msgStreet|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="city"><span>City</span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="city" id="city" value="{$city|htmlentities}"/>
            <span class="req" id="msgCity">{$msgCity|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="postalCode"><span>Postal Code</span></label>
            <input class="validate[required,maxSize[4]] text-input" type="text" name="postalCode" id="postalCode" value="{$postalCode|htmlentities}"/>
            <span class="req" id="msgPostalCode">{$msgPostalCode|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="nationality"><span>Nationality</span></label>
            <select name="nationality" value="{$selectedNationality}">
               {iteration:iCountries}
                     {$nationality}
               {/iteration:iCountries}
            </select>
            <span class="req" id="msgNationality">{$msgNationality|htmlentities}</span>
	</div>
        
        <div class="TRdiv">
            {option:oAdmin}
            <label for="userLevel"><span>User Level</span></label>
            <select name="userLevel" value="{$selectedUserLevel}">
               {iteration:iUserLevel}
                     {$userLevel}
               {/iteration:iUserLevel}
            </select>
            <span class="req" id="msgUserLevel">{$msgUserLevel|htmlentities}</span>
            {/option:oAdmin}
	</div>
        <div class="TRdiv">
        <label for="cv"><span>Upload your profile picture here</span></label>
        <input type="file" class="multi" maxlength="1" accept="jpg" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
    </div>
</fieldset>
        <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formRegister" value="doSubmit" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div>    
</form>
</div>