
<div class="mainDiv">
    
{$errorMsg}
<form action="" method="post" enctype="multipart/form-data">
                <fieldset>  
                    <legend>Forgot your password</legend>
                <div class="TRdiv">
			<label for="Email"><span>Email</span></label>
			<input class="field" type="text" name="Email" />
                        <span class="req">*</span>
                </div>                              
                </fieldset>
                <div class="TRdiv">
			<input type="hidden" name="formAction" id="formPassword" value="doPassword" />
			<input class="button" name="btnPassword" id="btnPassword" type="submit" value="Send a new password"/>
		</div>
</form>
</div>