<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?php echo $appDescription ?>">
    <meta name="author" content="<?php echo $appAuthor ?>">

    <title><?php echo $pageTitle ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/custom-css.css" rel="stylesheet">

    <!-- Jqueru -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

  </head>

  <body>

    <?php
    // function to set active class on selected nav item
    function setActive($requestUri)
    {
        $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
        if ($current_file_name == $requestUri) echo 'class="active"';
    }
    ?>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="/assets/images/logo-ph1.png"></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li <?=setActive("index")?>><a href="index.php">Provider View</a></li>
            <li <?=setActive("report")?>><a href="report.php">Admin: Reporting</a></li>
            <li <?=setActive("upload")?>><a href="upload.php">Admin: Upload</a></li>
            <li <?=setActive("resetdemo")?>><a href="resetdemo.php">Reset Demo</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
