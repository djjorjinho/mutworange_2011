
<div class="mainDiv">
{$errorMsg}
<form action="" method="post" enctype="multipart/form-data">
                <fieldset>  
                    <legend>Login</legend>
                <div class="TRdiv">
			<label for="Email"><span>Email</span></label>
			<input class="field" type="text" name="Email" />
                        <span class="req">*</span>
                        
                </div>
                <div class="TRdiv">
			<label for="password"><span>Password</span></label>
			<input class="field" type="password" name="Password"/>
                        <span class="req">*</span>
                </div>    
                <div class="TRdiv">
			<input type="hidden" name="formAction" id="formLogin" value="doLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</div>
                    <p class="password"><a href="index.php?module=login&amp;view=forgot">Forgot your password</a></p>
                </fieldset>
</form>
</div>