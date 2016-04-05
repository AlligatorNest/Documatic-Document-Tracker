<?php
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");
require_once ("assets/includes/cls_user.php");
require_once ("assets/includes/cls_category.php");
require_once ("assets/includes/cls_documents.php");
require_once ("assets/includes/upload_process.php");

//get list of all users for drop down
$x = new User();
$users = $x->getAllUsers($db);

$msg = '';

//upload the document
if($_POST && isset($_POST['action'], $_POST['documentName']) && (isset($_POST['documentCategory']) || isset($_POST['users'])))

{
  $action = $_POST["action"];

  if ($action == 'uploadDocument') {

    $documentName = $_POST['documentName'];

    if (isset($_POST['documentCategory'])) {
      $documentCategorySelected = $_POST['documentCategory'];
    } else {
      $documentCategorySelected = '';
    };

    if (isset($_POST['users'])) {
      $usersSelected = $_POST['users'];
    } else {
      $usersSelected = '';
    };

      $msg = documentUpload ($documentName,$documentCategorySelected,$usersSelected,$db);

  };
};


//get all categories for drop down
$q = "(SELECT category,categoryId FROM tblcategory)";
$categories = $db->rawQuery ($q);


//html page header and menu
require_once ("assets/includes/header.php");
?>

    <!-- Begin page content -->
    <div class="container">

      <div class="page-header">
        <h1>Document Upload</h1>
      </div>

      <p class="lead">Select category for document</p>
      <p class="bg-success"><?php echo $msg ?></p>
      <div class="container">

        <form method="post">
          <div class="form-group">
            <input type="hidden" name="action" value="uploadDocument">
            <label for="documentName">Document Name</label>
            <input type="text" class="form-control" id="documentName" name="documentName" placeholder="Document name">
          </div>

          <div class="form-group">
            <label for="documentFile">Select File</label>
            <input type="file" id="documentFile" name="documentFile">
            <p class="help-block">Select file to upload from your local computer.</p>
          </div>

          <label for="documentFile">Select Categoies</label>
          <select id="documentCategory[]" name="documentCategory[]" multiple class="form-control">
            <?php
            foreach ($categories as $category) {
                echo '<option value="' . $category['categoryId'] .'">' . $category['category'] . '</option>';
            }
             ?>
          </select>
          <p class="help-block">Hold down Ctrl to select multiple categories.</p>

          <label for="Users">Select Users</label>
          <select id="users[]" name="users[]" multiple class="form-control">
            <?php
            foreach ($users as $user) {
                echo '<option value="' . $user['userId'] .'">' . $user['username'] . '</option>';
            }
             ?>
          </select>
          <p class="help-block">Hold down Ctrl to select multiple Users.</p>

          <button type="submit" class="btn btn-default">Submit</button>
          <button type="reset" class="btn btn-default" value="Reset">Reset</button>
        </form>

      </div>



    </div>

    <?php
    //html page footer
    require_once ("assets/includes/footer.php");
    ?>
