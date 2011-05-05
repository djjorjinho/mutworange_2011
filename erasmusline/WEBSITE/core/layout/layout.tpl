<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>ErasmusLine - {$siteTitle}</title>

    <link rel="stylesheet" href="./core/css/layout.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./core/css/reset.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="./core/css/screen.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="./core/css/print.css" type="text/css" media="print"/>
<!--    {$pageMeta}-->
  </head>
  
  <body>
  <div id="wrapper">

    <div id="header">
    
      <div id="headNavi">
{option:oLogged}
		  <a href="#">Profile</a>
		  <a href="#">Notifications</a>
		  <a href="#">Progress</a>
{/option:oLogged}
{option:oNotLogged}
&nbsp;
{/option:oNotLogged}
      </div>
      <div id="nav-main">
      <a href="index.php" class="{$tabHome}">Home</a>
      <a href="index.php" class="{$tabInfo}">Information</a>
{option:oLogged}
      <a href="index.php?module=home&view=logout" title="Logout">Logout</a>
{/option:oLogged}
{option:oNotLogged}
      <a href="index.php?module=login" title="Login">Login</a>
{/option:oNotLogged}
{option:oAdmin}
      <a href="index.php?module=admin&view=users" class="{$tabAdmin}" title="Users">Admin</a>
{/option:oAdmin}

      </div>
    </div>
    <div id="main">
      <div id="breadcrumb">#{breadcrumb}</div>
      <div id="content">
<!-- CONTENT -->
<h2>{$title}</h2>
{$pageContent}
<!-- END CONTENT -->      
      </div>
      <div id="subnav">
<!-- SUBNAVBAR -->
<ul>
<li class="active"><a href="">Subnavbar 1st Level (active)</a></li>
  <li class="level2"><a href="">Subnavbar 2nd Level</a></li>
    <li class="level3"><a href="">Subnavbar 3rd Level</a></li>
<li><a href="">Subnavbar 1st Level (inactive)</a></li>
</ul>
<!-- END SUBNAVBAR -->
      </div>
      <div id="footer">
      &copy; 2011 by EU | Sitemap | Contact | Disclaimer | Legal info
      </div>
    </div>
  </div>
  </body>
</html>
