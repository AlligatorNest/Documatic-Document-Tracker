<?php
function documentUpload ($documentName,$documentCategory,$users,$db) {

    //Create array of selected document catetories
    $categoryIdAry = array();
    if (isset($documentCategory)) {

      $categoryId=$documentCategory;

      if ($categoryId)
      {
          foreach ($categoryId as $value)
          {
              array_push($categoryIdAry,$value);
          };
      };
    };

    //Create array of assigned document users
    $userIdAry = array();
    if (isset($users)) {
      $userId=$users;

      if ($userId)
      {
          foreach ($userId as $value)
          {
              array_push($userIdAry,$value);
          };
      };
    };

    //insert document and get id
    $data = Array ("documentName" => $documentName);
    $d = new Document();
    $documentId = $d-> DocumentInsert($documentName,$data,$db);


    //insert documentid and categoryIds
    foreach ($categoryIdAry as $categoryId) {
      $data = Array (
                      "documentId" => $documentId,
                      "categoryId" => $categoryId
                    );
      $documentCatId = $d-> DocumentCategoryInsert($data,$db);
    };

    //insert into documentUserXref table if users selected
    foreach ($userIdAry as $userId) {
      $data = Array (
                      "documentId" => $documentId,
                      "userId" => $userId
                    );
      $documentUserId = $d-> DocumentUserInsert($data,$db);

    };

    $msg = 'Your document has been uploaded.';
    return $msg;

}
 ?>
