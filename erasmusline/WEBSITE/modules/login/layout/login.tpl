<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h2>Login</h2>
{$errorMsg}
<form action="" method="post" enctype="multipart/form-data">
                <div class="TRdiv">
			<label for="Email"><span>Email</span></label>
			<input class="field" type="text" name="Email" />
                </div>
                <div class="TRdiv">
			<label for="password">Password</span></label>
			<input class="field" type="password" name="Password"/>
                </div>    
                <div class="TRdiv">
			<input type="hidden" name="formAction" id="formLogin" value="doLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</div>
</form>
</div>