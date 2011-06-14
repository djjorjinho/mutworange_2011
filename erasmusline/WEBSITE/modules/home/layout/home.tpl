<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
 <form action="" method="post" enctype="multipart/form-data">   
     <fieldset>
                  <legend><h3>Sign up now</h3></legend>
                      <p>Welcome to the ErasmusLine Webapplication</p>
                      <p>To start your application for Erasmus, click on the button below</p>
                      <p>If you are already registered, log in and see your progress</p>
                      <div class="TRdiv">
			<input type="hidden" name="formAction" id="formRegister" value="doRegister" />
			<input class="button" name="btnRegister" id="btnRegister" type="submit" value="Register"/>
		</div>    
     </fieldset>
   
   </form> 
    
<form action="" method="post" enctype="multipart/form-data">
             <fieldset>
                  <legend><h3>Login</h3></legend>
             <div class="TRdiv">
			<label for="Email"><span>Email</span></label>
			<input class="field" type="text" name="Email" />
                </div>
                <div class="TRdiv">
			<label for="password"><span>Password</span></label>
			<input class="field" type="password" name="Password"/>
                </div>
                  <div class="TRdiv">
			<input type="hidden" name="formAction" id="formLogin" value="mainLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</div>   
               </fieldset>
                
	</form>
 </div>