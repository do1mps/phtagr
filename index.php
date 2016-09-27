<?php
session_start();
include('plugins/database.php');
include('plugins/log.php');

/*
$plugins = array();
$alleplugins = scandir('plugins');
foreach ($alleplugins as $plugin) {
   include('plugins/' . $plugin);
   $pluginnames .= $plugin;
};
*/

$log = new Log();
$log->write('Index.php called');
$db = new Database();

//Router => Welche Seite dargestellt werden soll, steht in der Session.
//Wenn die Session neu ist, dann wird "rndnew" verwendet.

//Achtung, darf der User überhaupt eine Seite sehen (Grundlegender Login notwendig?)
// => Userverwaltung!

//Seite darstellen:
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>phtagr - Organize, Browse, and Share Your Photos</title>

    <!-- Bootstrap -->
    <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/phtagr.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="">phTagr <div id='phtagr.version'>loading...</div></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li id="phtagr.menu.button.home" class="phtagr.menu.button"><div id="phtagr.menu.text.home">Home</div></li>
        <li id="phtagr.menu.button.explorer" class="phtagr.menu.button"><div id="phtagr.menu.text.explorer">Explorer</div></li>
        <li id="phtagr.menu.button.mb3" class="phtagr.menu.button"><div id="phtagr.menu.text.mb3">MenuButton3</div></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li id="phtagr.menu.quicksearch">
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input id="phtagr.menu.quicksearch.input" type="text" placeholder="Quicksearch" class="form-control">
            </div>
          </form>
        </li>
        <li id="phtagr.menu.button.login" class="phtagr.menu.button"><div id="phtagr.menu.text.login">Login</div></li>
        <li id="phtagr.menu.button.impressum" class="active phtagr.menu.button"><div id="phtagr.menu.text.impressum">Impressum</div></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>

  <!-- Pagination / Filter Bar -->
  <nav id='phtagr.paginationnavbar' class="navbar navbar-fixed second-navbar">
    <div class="container footer-container">
      <ul class="nav navbar-nav second-navbar">
        <li><div aria-label="Previous" id="phtagr.pagination.prev" class="phtagr.pagination.button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span></div></li>
        <li><div aria-label="Page 1"   id="phtagr.pagination.1"    class="phtagr.pagination.button">1 </div></li>
        <li><div aria-label="Page 2"   id="phtagr.pagination.2"    class="phtagr.pagination.button">2 </div></li>
        <li><div aria-label="Page 3"   id="phtagr.pagination.3"    class="phtagr.pagination.button">3 </div></li>
        <li><div aria-label="Page 4"   id="phtagr.pagination.4"    class="phtagr.pagination.button">4 </div></li>
        <li><div aria-label="Page 5"   id="phtagr.pagination.5"    class="phtagr.pagination.button">5 </div></li>
        <li><div aria-label="Next"     id="phtagr.pagination.next" class="phtagr.pagination.button"><span class="glyphicon glyphicon-forward" aria-hidden="true"></span></div></li>
      </ul>
      <ul class="nav navbar-nav second-navbar">
        <li><div aria-label="Active Filter" id="phtagr.submenu.activefilter" class="phtagr.pagination.filterline">Filter: keiner</div></li>
      </ul>
    </div>
  </nav>

  <div id='phtagr.main-content'></div>

   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <script src="jQuery-3.1.0/jquery-3.1.0.min.js"></script>
   <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
   <script src="js/phtagr.main.js"></script>
 </body>
</html>
