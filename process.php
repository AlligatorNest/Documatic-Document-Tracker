<?php
require_once ("assets/includes/global.php");
require_once ("assets/database/MysqliDb.php");
require_once ("assets/database/dbconnect.php");

//determine what action we need to process
$action = $_POST["action"];

// record document download
if ($action == 'download') {

  $dcoumentId =  $_POST["documentId"];
  $userId =  $_POST["userId"];

  $data = Array ("documentId" => $dcoumentId,
                 "userID" => $userId
               );

  $id = $db->insert ('tbldocumentuseraccess', $data);


}
?>
