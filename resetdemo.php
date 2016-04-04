<?php
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");


//delete all records from documentuseraccess table
$db->rawQuery('DELETE FROM tbldocumentuseraccess');
$db->rawQuery('DELETE FROM tbldocumentuserxref');
$db->rawQuery('DELETE FROM tbldocumentcategoryxref WHERE adddate > "2016-03-27 15:49:36"');
$db->rawQuery('DELETE FROM tbldocument WHERE adddate > "2016-03-27 15:49:36"');

//html page header and menu
require_once ("assets/includes/header.php");
?>

    <!-- Begin page content -->
    <div class="container">

      <div class="page-header">


      </div>

      <p class="lead">Demo Reset: Document download history deleted.</p>


      <?php
      //html page footer
      require_once ("assets/includes/footer.php");
      ?>
