<?php
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");


//get current userid
$useridSelected = $_POST["userId"];

//get list of all users
$users = $db->get('tblusers');

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

                  /*$str.= '<option value="' . $user['userId'] .'"';
                  //if ($useridSelected == $user['userId']){ $str.= 'selected="selected"'; }
                  $str.=  ($user['userId']  == $useridSelected  ? ' selected="selected"' : '');
                  $str.= '>';
                  $str.=  $user['username'] . '</option>';
                  */
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

        //get current username
        $db->where ("userId", $userid);
        $user = $db->getOne ("tblusers");
        $username = $user['username'];

        // get available documents for this userid assigned by Category
        $params = Array($userid,$userid);
        $q = "(
        SELECT DISTINCT d.documentName,d.documentId
        FROM tblusers u
        INNER JOIN tblusercategoryxref ucX ON u.userId = ucx.userId
        INNER JOIN tblcategory c ON ucx.categoryId = c.categoryId
        INNER JOIN tbldocumentcategoryxref dcx on c.categoryId = dcx.categoryId
        INNER JOIN tbldocument d on dcx.documentId = d.documentId
        WHERE d.documentId not in (SELECT documentid from tbldocumentuseraccess where userID = ?) AND
        u.userId = ?
        )";
        $documents = $db->rawQuery ($q, $params);
        $downloadCount = $db->count;

        // get available documents for this userid assigned by userid
        $params = Array($userid,$userid);
        $q = "(
        SELECT DISTINCT d.documentName,d.documentId
        FROM tblusers u
        INNER JOIN tbldocumentuserxref dux on u.userId = dux.userId
        INNER JOIN tbldocument d on dux.documentId = d.documentId
        WHERE d.documentId not in (SELECT documentid from tbldocumentuseraccess where userID = ?) AND
        u.userId = ?
        )";
        $userdocuments = $db->rawQuery ($q, $params);

        $downloadCount += $db->count;

        if ($downloadCount > 0) {
          $msg =  $downloadCount . " new documents pending download for " . $username .".";
        } else {
          $msg = "No documents pending download.";
        };

        // get download history for this userid
        $params = Array($userid);
        $q = "(
        SELECT DISTINCT d.documentName,d.documentId,dua.accessDate
        FROM tblusers u
        INNER JOIN tbldocumentuseraccess dua on u.userId = dua.userId
        INNER JOIN tbldocument d on dua.documentId = d.documentId
        WHERE u.userId = ?
        ORDER BY dua.accessDate DESC

        )";
        $downloads = $db->rawQuery ($q, $params);
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
