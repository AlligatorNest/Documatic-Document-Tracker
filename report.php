<?php
require_once ("assets/includes/global.php");
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");
require_once ("assets/includes/cls_user.php");
require_once ("assets/includes/cls_category.php");
require_once ("assets/includes/cls_documents.php");


//get selected userid from form submit
if (isset($_POST['userId'])) {
$useridSelected = $_POST["userId"];
}

//get list of all users
$x = new User();
$users = $x->getAllUsers($db);

//html page header and menu
require_once ("assets/includes/header.php");
?>


    <!-- Begin page content -->
    <div class="container">

      <div class="page-header">
        <h1>Document Access Reporting</h1>
      </div>

      <p class="lead">Document Download History</p>
      <div class="container">

        <form method="post" class="form-inline">

          <div class="form-group">
            <input type="hidden" name="action" value="auditUser">
            <label for="user">Select a user to audit:</label>

            <select name="userId" class="form-control">
              <?php
              $str = '';
              foreach ($users as $user) {
                  echo '<option value="' . $user['userId'] .'"'
                  . ($user['userId']  == $useridSelected  ? ' selected="selected"' : '') . '>'
                  . $user['username'] . '</option>';
              }
              //echo $str;
               ?>
            </select>

          </div>

          <button type="submit" class="btn btn-default">Audit</button>

        </form>
      </div>

      <?php
      //determine what action we need to process
      $action = "";
      if (isset($_POST['action']))
      {
        $action = $_POST["action"];
      }

      /*********************
      *** if form was submitted,
      *** show results.
      ***********************/
      if ($action == 'auditUser') {

        $userid = $_POST["userId"];

        //get current username for selected user
        $username = $x->getUsername($userid,$db);

        // get available documents for this userid assigned by Category
        $d = new Document();
        $documents = $d->getDocumentsCategory($userid,$db);
        $downloadCount = $d->count;

        // get available documents for this userid assigned by userid
        $userdocuments = $d->getDocumentsUser($userid,$db);
        $downloadCount += $d->count;

        if ($downloadCount > 0) {
          $msg =  $downloadCount . " new documents pending download for " . $username .".";
        } else {
          $msg = "No documents pending download.";
        };

        // get download history for this userid
        $downloads = $d->getDocumentHistory($userid,$db);

        ?>
        <p><hr></p>
        <div class="container">
          <div class="row">
            <div class="col-xs-12"><strong><?php echo $msg ?></strong></div>
          </div>
        <?php
        foreach ($documents as $document){
          echo '<div class="row">';
          echo '<div class="col-xs-4">' .$document['documentName'] . '</div><div class="col-xs-8">Pending</div>';
          echo '</div>';
        }

        foreach ($userdocuments as $userdocument){
          echo '<div class="row">';
          echo '<div class="col-xs-4">' .$userdocument['documentName'] . '</div><div class="col-xs-8">Pending</div>';
          echo '</div>';
        }

        foreach ($downloads as $download){
          echo '<div class="row">';
          echo '<div class="col-xs-4">' .$download['documentName'] . '</div><div class="col-xs-8">' .$download['accessDate'] . '</div>';
          echo '</div>';
        }

        ?>
        </div>

        <?php };?>
    </div>

    <?php
    //html page footer
    require_once ("assets/includes/footer.php");
    ?>
