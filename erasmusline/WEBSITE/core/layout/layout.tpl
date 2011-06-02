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
		  <a href="#" title="Contact">Contact</a>
		  <a href="index.php?module=about&amp;view=about" title="About">About</a>
{option:oNotLogged}
&nbsp;
{/option:oNotLogged}
      </div>
      <div id="nav-main">
{option:oAdmin}
		<a href="index.php?module=stats" class="{$tabHome}" title="Executive Information System">EIS</a>
           <a href="index.php?module=admin&amp;view=admin" class="{$tabHome}" title="Admin">Admin</a>
{/option:oAdmin}
{option:oLogged}
		<a href="index.php?module=stats" class="{$tabHome}" title="Executive Information System">EIS</a>
      	<a href="index.php?module=profile&amp;view=ownprofile" title="Profile">Profile</a>
        <a href="index.php?module=home&amp;view=userhome" class="{$tabHome}" title="Home">Home</a>
        <a href="index.php?module=residence&amp;view=overview" class="{$tabResidence}" title="Residences" >Residences</a>
{/option:oLogged}
{option:oNotLogged}
	<a href="index.php?module=stats" class="{$tabHome}" title="Executive Information System">EIS</a>
      <a href="index.php?module=home&amp;view=home" title="home">Home</a>
{/option:oNotLogged}
      <a href="index.php?module=info&amp;view=erasmus" class="{$tabInfo}" title="Info">Info</a>      
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
    <li class="level2"><a href="index.php?module=admin&amp;view=toconfirm" title="Users to be confirmed">Users to be confirmed</a></li>
    <li class="level2"><a href="index.php?module=admin&amp;view=students" title="Students">Students</a></li>
    <li class="level2"><a href="index.php?module=admin&amp;view=staff" title="Staff">Staff</a></li>
    <li class="level2"><a href="index.php?module=register&amp;view=register" title="Add staff member">Add staff member</a></li>
</ul>
</div>
{/option:oAdmin}

{option:oOffice}
<div id="subnav">
<ul>
    <li class="level2"><a href="index.php?module=office&amp;view=precandidates">Show Precandidate Forms</a></li>
    <li class="level2"><a href="index.php?module=office&amp;view=applics">Show Student Application Forms</a></li>
    <li class="level2"><a href="index.php?module=office&amp;view=agreements">Show Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=office&amp;view=changes">Show Change of Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=office&amp;view=applics">Students</a></li>
</ul>
</div>
{/option:oOffice}
      
{option:oCoor}
<div id="subnav">
<ul>
    <li class="level2"><a href="index.php?module=staff&amp;view=precandidates" title="Show Precandidate Forms">Show Precandidate Forms</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=applics" title="Show Student Application Forms">Show Student Application Forms</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=agreements" title="Show Learning Agreements">Show Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=changes" title="Show Change of Learning Agreements">Show Change of Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=trrec&amp;view=select">Show Transcript of Records</a></li>
    <li class="level2"><a href="index.php?module=abroad_stay&amp;view=select">Show Certificates</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=applics" title="Students">Students</a></li>
</ul>
</div>
{/option:oCoor}
      
{option:oStudent}
<div id="subnav">
<ul>
    <li class="lavel2"><a href="#" title="Progress">Progess</a></li>
    <li class="level2"><a href="#" title="Edit profile">Edit profile</a></li>
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
