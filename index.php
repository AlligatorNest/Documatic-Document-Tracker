<?php
require_once ("assets/includes/global.php");
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");
require_once ("assets/includes/cls_user.php");
require_once ("assets/includes/cls_category.php");
require_once ("assets/includes/cls_documents.php");

//get current userid
$userid = 1;

//get username
$x = new User();
$username = $x->getUsername($userid,$db);

//get categries for userid
$c = new Category();
$categories = $c->getUserCategories($userid,$db);

// get available documents for this userid BY CATEGORY
$d = new Document();
$documents = $d->getDocumentsCategory($userid,$db);
$downloadCount = $d->count;

// get available documents for this userid BY USERID
// these are documents assigned to particular user
$userdocuments = $d->getDocumentsUser($userid,$db);
$downloadCount += $d->count;


if ($downloadCount > 0) {
  $msg = "You have " . $downloadCount . " new documents available for download!";
} else {
  $msg = "You have no new documents available for download";
};

//html page header and menu
require_once ("assets/includes/header.php");
?>


    <!-- Begin page content -->
    <div class="container">

      <div class="page-header">
        <h1>Current User: <?php echo $username ?></h1>

        <p><strong>Categories</strong>:
        <?php
        $str = '';
        foreach ($categories as $category) {
            $str.= $category['category'] .',';
        }
        $str = rtrim($str, ',');
        echo $str;
         ?>
       </p>

      </div>

      <p class="lead"><?php echo $msg?></p>
      <p>Documents Specific to these Categories:</p>
      <hr>
      <div class="container">
      <?php
      foreach ($documents as $document){
        echo '<div class="row">';
        echo '<div class="col-xs-4">' .$document['documentName'] . '</div><div class="col-xs-8">' . '<a href="'. $upload_dir . $document['documentPath'] .'"><input id="' . $document['documentId'] . ',' . $userid . '" type="button" name="download" value="Download"></a></div>';
        echo '</div>';
      }
      ?>
      </div>

      <hr>
      <p>Documents Specific to this User:</p>
      <hr>
      <div class="container">
      <?php
      foreach ($userdocuments as $userdocument){
        echo '<div class="row">';
        echo '<div class="col-xs-4">' .$userdocument['documentName'] . '</div><div class="col-xs-8">' . '<a href="'. $upload_dir . $userdocument['documentPath'] .'"><input id="' . $userdocument['documentId'] . ',' . $userid . '" type="button" name="download" value="Download"></a></div>';
        echo '</div>';
      }
      ?>
      </div>

    </div>



    <?php
    //html page footer
    require_once ("assets/includes/footer.php");
    ?>


    <!-- Script to capture document downloaded. -->
    <script>
    $(document).ready(function() {
        $('[name="download"]').click(function() {

            //get documentid and user id (documentId,userId)
            var $strId = this.id;
            //split on comma
            var $ary = $strId.split(',');

            var $documentId = $ary[0];
            var $userId = $ary[1];

            //ajax post to script to record download
            var request = $.ajax({
              url: "process.php",
              type: "POST",
              data: {action: "download", userId : $userId, documentId : $documentId},
              dataType: "html",
              success: function(data) {
                  //alert(data);
                  location.reload();
              }
            });


        });
    });
    </script>
