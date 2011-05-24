<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <title>ErasmusLine - {$siteTitle}</title>

    <link rel="stylesheet" href="./core/css/layout.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./core/css/reset.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="./core/css/screen.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="./core/css/print.css" type="text/css" media="print"/>
    {$pageMeta}
    {$pageJava}
    
  </head>
  
  <body>
  <div id="wrapper">
    <div id="header">    
      <div id="headNavi">
		  <a href="#">Contact</a>
		  <a href="index.php?module=about&amp;view=about">About</a>
{option:oNotLogged}
&nbsp;
{/option:oNotLogged}
      </div>
      <div id="nav-main">
{option:oAdmin}
		<a href="index.php?module=stats" class="{$tabHome}">EIS</a>
           <a href="index.php?module=admin&amp;view=admin" class="{$tabHome}">Admin</a>
{/option:oAdmin}
{option:oLogged}
      <a href="index.php?module=profile&amp;view=ownprofile">Profile</a>
            <a href="index.php?module=home&amp;view=userhome" class="{$tabHome}">Home</a>
            <a href="index.php?module=residence&amp;view=overview" class="{$tabResidence}">Residences</a>
{/option:oLogged}
{option:oNotLogged}
      <a href="index.php?module=home&amp;view=home" title="home">Home</a>
{/option:oNotLogged}
      <a href="index.php?module=info&amp;view=erasmus" class="{$tabInfo}">Info</a>      
{option:oLogged}
      <a href="index.php?module=login&amp;view=logout" title="Logout">Logout</a>
{/option:oLogged}
{option:oNotLogged}
      <a href="index.php?module=login&amp;view=login" title="Login">Login</a>
{/option:oNotLogged}
      </div>
    </div>
    <div id="main">
      <div id="breadcrumb">{$breadcrumb}</div>
      <div id="content">
<!-- CONTENT -->
{$pageContent}
<!-- END CONTENT -->      
      </div>
{option:oAdmin}
<div id="subnav">
<ul>
    <li class="level2"><a href="index.php?module=admin&amp;view=toconfirm">Users to be confirmed</a></li>
    <li class="level2"><a href="index.php?module=admin&amp;view=students">Students</a></li>
    <li class="level2"><a href="index.php?module=admin&amp;view=staff">Staff</a></li>
    <li class="level2"><a href="index.php?module=register&amp;view=register">Add staff member</a></li>
</ul>
</div>
{/option:oAdmin}

{option:oCoor}
<div id="subnav">
<ul>
    <li class="level2"><a href="index.php?module=staff&amp;view=precandidates">Show Precandidate Forms</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=applics">Show Student Application Forms</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=agreements">Show Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=changes">Show Change of Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=applics">Students</a></li>
</ul>
</div>
{/option:oCoor}
      
{option:oStudent}
<div id="subnav">
<ul>
    <li class="lavel2"><a href="">Progess</a></li>
    <li class="level2"><a href="">Edit profile</a></li>
</ul>
</div>
{/option:oStudent}
<!-- END SUBNAVBAR -->
      
      <div id="footer">
      &copy; 2011 by EU | Sitemap | Contact | Disclaimer | Legal info
      </div>
    </div>
  </div>
  </body>
</html>
