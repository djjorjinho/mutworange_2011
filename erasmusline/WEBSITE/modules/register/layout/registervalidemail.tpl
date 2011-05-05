{option:oSuccess}
    <h2>Your email has been succesfully validated.</h2>
    
    <form action=" " method="post" enctype="multipart/form-data">
                <p>
				<label>Email</label>
				<input class="field" type="text" name="Email" />
			</p>

			<p>
				<label>Password</label>
				<input class="field" type="password" name="Password"/>
                                
			</p>

		<p>
			<input type="hidden" name="formAction" id="formLogin" value="doLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</p>
                    </form>
{/option:oSuccess}

{option:oNoSuccess}
    <h2>Your email couldn't be validated</h2>
{/option:oNoSuccess}
    
