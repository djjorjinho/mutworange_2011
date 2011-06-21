<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
{$errorMsg}
<form action="" method="post" enctype="multipart/form-data">
                <fieldset>  
                    <legend><h3>Login</h3></legend>
                <div class="TRdiv">
			<label for="Email"><span>Email</span></label>
			<input class="field" type="text" name="Email" />
                        <span class="req">*</span>
                        <p class="password"><a href="index.php?module=login&amp;view=forgot">Forgot your password</a></p>
                </div>
                <div class="TRdiv">
			<label for="password"><span>Password</span></label>
			<input class="field" type="password" name="Password"/>
                        <span class="req">*</span>
                </div>    
                            
                </fieldset>
                <div class="TRdiv">
			<input type="hidden" name="formAction" id="formLogin" value="doLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</div>
</form>
</div>