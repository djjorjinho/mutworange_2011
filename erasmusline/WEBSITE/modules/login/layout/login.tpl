<h2>Login</h2>
{$errorMsg}
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