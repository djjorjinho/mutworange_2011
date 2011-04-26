<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ErasmusLine - {$siteTitle}</title>

<link rel="stylesheet" href="./core/css/layout.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./core/css/reset.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="./core/css/screen.css" type="text/css" media="screen"/>


{$pageMeta}
{$pageJava}
</head>

<body>
<div id="topPan">
	
     <h2><a href="{$home}">ErasmusLine</a></h2>
{option:oLogged}
<ul class="loggedHome">
		<li><a href="{$profile}">Profile</a></li>
		<li><a href="#">Notifications</a></li>
		<li><a href="#">Progress</a></li>
	</ul>
{/option:oLogged}

{option:oAdmin}
    <ul class="loggedHome">
        <li><a href="index.php?module=admin&view=students" title="Users">AdminPanel</a></li>
    </ul>
{/option:oAdmin}
   
	<ul>

		<li class="home"><a href="index.php?module=home" title="Home">Home</a></li>
		<li><a href="index.php?module=info&view=erasmus" title="Erasmus">Erasmus</a></li>
                <li><a href="index.php?module=info&view=erasmusline" title="ErasmusLine">ErasmusLine</a></li>
                <li><a href="index.php?module=info&view=partners" title="Partners">Partners</a></li>
		<li><a href="index.php?module=info&view=faq" title="FAQ">FAQ</a></li>
                
                {option:oNotLogged}
                <form action=" " method="post" enctype="multipart/form-data">
                <p>
				<label>Email</label>
				<input class="field" type="text" name="Email" id="Email"/>
			</p>

			<p>
				<label>Password</label>
				<input class="field" type="password" name="Password" id="Email"/>
			</p>

		<p>
			<input type="hidden" name="formAction" id="formLogin" value="mainLogin" />
			<input class="button" name="btnLogin" id="btnLogin" type="submit" value="Login"/>
		</p>
                    </form>
                {/option:oNotLogged}
                
                {option:oLogged}
                <form action=" " method="post" enctype="multipart/form-data">
                    <p>
			<input type="hidden" name="formAction" id="formLogout" value="mainLogout" />
			<input class="button" name="btnLogout" id="btnLogout" type="submit" value="Logout"/>
                    </p>
                </form>
                {/option:oLogged}
                
	</ul>


</div>
<div id="bodytopmainPan">
<div id="bodymiddlePan">
<!-- content -->
			<div id="content">
				{$pageContent}
			</div>
</div>
</div>

<div id="footermainPan">
  <div id="footerPan">
		<ul>
  		<li class="home"><a href="index.php?module=home" title="Home">Home</a>&#124;</li>
		<li><a href="index.php?module=info&view=erasmus" title="What is Erasmus">What is Erasmus</a>&#124;</li>
                <li><a href="index.php?module=info&view=erasmusline" title="About ErasmusLine">About ErasmusLine</a>&#124;</li>
                <li><a href="index.php?module=info&view=partners" title="Partners" >Partners</a>&#124;</li>
		<li><a href="index.php?module=info&view=faq">FAQ</a>&#124;</li>
 	 </ul>
  	<p class="copyright">©geniousweb. all right reserved.</p>
  	<ul class="templateworld">
  		<li>Design By:</li>
		<li><a href="http://www.templateworld.com" target="_blank">Template World</a></li>
    </ul>
	</div>
	</div>
</body>
</html>
