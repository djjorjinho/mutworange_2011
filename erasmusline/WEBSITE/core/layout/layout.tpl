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
  <div id="wrapper">
    <div id="header">    
      <div id="headNavi">
		  <a href="#">Contact</a>
		  <a href="index.php?module=about&view=about">About</a>
{option:oNotLogged}
&nbsp;
{/option:oNotLogged}
      </div>
      <div id="nav-main">
{option:oAdmin}
           <a href="index.php?module=admin&view=admin" class="{$tabHome}">Admin</a>
{/option:oAdmin}
{option:oLogged}
      <a href="index.php?module=profile&view=ownprofile">Profile</a>
            <a href="index.php?module=home&view=userhome" class="{$tabHome}">Home</a>
{/option:oLogged}
{option:oNotLogged}
      <a href="index.php?module=home&view=home" title="home">Home</a>
{/option:oNotLogged}
      <a href="index.php?module=info&view=erasmus" class="{$tabInfo}">Info</a>      
{option:oLogged}
      <a href="index.php?module=login&view=logout" title="Logout">Logout</a>
{/option:oLogged}
{option:oNotLogged}
      <a href="index.php?module=login&view=login" title="Login">Login</a>
{/option:oNotLogged}
{option:oAdmin}
     <a href="index.php?module=login&view=logout" title="Logout">Logout</a>
{/option:oAdmin}
      </div>
    </div>
    <div id="main">
      <div id="breadcrumb">#{breadcrumb}</div>
      <div id="content">
<!-- CONTENT -->
{$pageContent}
<!-- END CONTENT -->      
      </div>
{option:oAdmin}
<div id="subnav">
<ul>
    <li class="level2"><a href="index.php?module=admin&view=students">Students</a></li>
    <li class="level2"><a href="index.php?module=admin&view=staff">Staff</a></li>
    <li class="level2"><a href="index.php?module=register&view=register">Add staff member</a></li>
    <li class="level2"><a href="">Subnavbar 1st Level (inactive)</a></li>
</ul>
</div>
{/option:oAdmin}
{option:oLogged}
<div id="subnav">
<ul>
    <li class="active"><a href="">Progess</a></li>
    <li class="level2"><a href="">Profile</a></li>
    <li class="level3"><a href="">Subnavbar 3rd Level</a></li>
    <li><a href="">Subnavbar 1st Level (inactive)</a></li>
</ul>
</div>
{/option:oLogged}
<!-- END SUBNAVBAR -->
      
      <div id="footer">
      &copy; 2011 by EU | Sitemap | Contact | Disclaimer | Legal info
      </div>
    </div>
  </div>
  </body>
</html>
