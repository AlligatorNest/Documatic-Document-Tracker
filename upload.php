<?php
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");

//get list of all users
$users = $db->get('tblusers');

$msg = '';

//upload the document
//if($_POST && isset($_POST['action'], $_POST['documentName'], $_POST['documentCategory'], $_POST['users']))
if($_POST && isset($_POST['action'], $_POST['documentName']) && (isset($_POST['documentCategory']) || isset($_POST['users'])))

{
  $action = $_POST["action"];

  if ($action == 'uploadDocument') {

    $documentName = $_POST["documentName"];

    //get categorys to assign document to - in array if multiple
    $categoryIdAry = array();
    $categoryId=$_POST['documentCategory'];

    if ($categoryId)
    {
        foreach ($categoryId as $value)
        {
            array_push($categoryIdAry,$value);
        };
    };

    //get userIds to assign document to - in array if multiple
    $userIdAry = array();
    $userId=$_POST['users'];

    if ($userId)
    {
        foreach ($userId as $value)
        {
            array_push($userIdAry,$value);
        };
    };

    //insert document and get id
    $data = Array ("documentName" => $documentName);
    $documentId = $db->insert ('tbldocument', $data);

    //insert documentid and categoryIds
    foreach ($categoryIdAry as $categoryId) {
      $data = Array (
                      "documentId" => $documentId,
                      "categoryId" => $categoryId
                    );
      $documentCatId = $db->insert ('tbldocumentcategoryxref', $data);
    };

    //insert into documentUserXref table if users selected
    foreach ($userIdAry as $userId) {
      $data = Array (
                      "documentId" => $documentId,
                      "userId" => $userId
                    );
      $documentUserId = $db->insert ('tbldocumentUserXref', $data);
    };


    $msg = 'Your document has been uploaded.';

  };
};


//get categries for userid
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
