<?php
function documentUpload ($documentName,$documentCategory,$users,$file_name,$file_size,$file_tmp,$upload_dir,$upload_max_size,$upload_file_types,$db) {

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
    $target_dir = $upload_dir ."/";
    $target_file = $target_dir . basename($file_name);
    $uploadOk = 1;
    $target_FileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Check file size
    if ($file_size > $upload_max_size) {
        $msg = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Get list of allowed docs to display in error message
    foreach ($upload_file_types as $file_type) {$sFileTypes .=  $file_type . ',';}
    // Remove trailing comma
    $file_type = rtrim($file_type, ',');

    // Loop through to see if uploaded doc type is allowed
    foreach ($upload_file_types as $file_type) {
      if($target_FileType == $file_type){
        $uploadOk = 1;
        break;
      } else {
        $uploadOk = 0;
        $msg = "Sorry, only <strong>" . $sFileTypes . "</strong> document files are allowed. You tried to upload a <strong>" . $target_FileType . '</strong>.';
      }
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
    $documentId = $d-> DocumentInsert($data,$db);


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
