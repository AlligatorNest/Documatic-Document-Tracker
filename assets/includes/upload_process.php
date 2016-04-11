<?php
function documentUpload ($documentName,$documentCategory,$users,$file_name,$file_size,$file_tmp,$db) {

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

    //Get uploaded document info
    $target_dir = $upload_dir;
    $target_file = $target_dir . basename($file_name);
    $uploadOk = 1;
    $target_FileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Check file size
    if ($file_size > 500000) {
        $msg = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($target_FileType != "docx" && $target_FileType != "doc" && $target_FileType != "pdf"
    && $target_FileType != "txt" ) {
        $msg = "Sorry, only document files are allowed.";
        $uploadOk = 0;
    }

    // Upload the document
    if ($uploadOk !== 0) {
        if (move_uploaded_file($file_tmp, $target_file)) {
          $msg = "The file ". basename( $file_name ). " has been uploaded.";
        } else {
          $msg = "Sorry, there was an error uploading your file.";
        }
    }



    //insert document name and path and get id
    $data = Array ("documentName" => $documentName, "documentPath" => $target_file);
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

    return $msg;

}
 ?>
