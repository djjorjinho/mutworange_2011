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
<<<<<<< HEAD
			<input type="hidden" name="formAction" id="formLogin" value="doLogin" />
=======
			<input type="hidden" name="formAction" id="formLogin" value="mainLogin" />
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</p>
                    </form>
{/option:oSuccess}

{option:oNoSuccess}
    <h2>Your email couldn't be validated</h2>
{/option:oNoSuccess}
    
