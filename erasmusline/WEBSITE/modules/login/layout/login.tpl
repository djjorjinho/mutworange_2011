<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
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
			<label for="password">Password</span></label>
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