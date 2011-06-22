<div class="mainDiv">
{option:oSuccess}
    <h3>Your email has been succesfully validated.</h3>
    
    <form action=" " method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Login</legend>
                <div class="TRdiv">
				<label>Email</label>
				<input class="field" type="text" name="Email" />
			</div>

			<div class="TRdiv">
				<label>Password</label>
				<input class="field" type="password" name="Password"/>
                                
			</div>

		<div class="TRdiv">
			<input type="hidden" name="formAction" id="formLogin" value="mainLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</div>
                </fieldset>
                    </form>
{/option:oSuccess}

{option:oNoSuccess}
    <h3>Your email couldn't be validated</h3>
{/option:oNoSuccess}
    
    </div>
    
