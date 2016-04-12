<?php
class Document
{

    //get documents assigned by user category
    public function getDocumentsCategory($userid,$db)
    {
      $params = Array($userid,$userid);
      $q = "(
      SELECT DISTINCT d.documentName,d.documentId,d.documentPath
      FROM tblusers u
      INNER JOIN tblusercategoryxref ucX ON u.userId = ucx.userId
      INNER JOIN tblcategory c ON ucx.categoryId = c.categoryId
      INNER JOIN tbldocumentcategoryxref dcx on c.categoryId = dcx.categoryId
      INNER JOIN tbldocument d on dcx.documentId = d.documentId
      WHERE d.documentId not in (SELECT documentid from tbldocumentuseraccess where userID = ?) AND
      u.userId = ?
      )";

      $documents = $db->rawQuery ($q, $params);
      $this->count = $db->count;
      return $documents;
    }

    //get documents assigned to individual users
    public function getDocumentsUser($userid,$db)
    {
      $params = Array($userid,$userid);
      $q = "(
      SELECT DISTINCT d.documentName,d.documentId,d.documentPath
      FROM tblusers u
      INNER JOIN tbldocumentuserxref dux on u.userId = dux.userId
      INNER JOIN tbldocument d on dux.documentId = d.documentId
      WHERE d.documentId not in (SELECT documentid from tbldocumentuseraccess where userID = ?) AND
      u.userId = ?
      )";

      $userdocuments = $db->rawQuery ($q, $params);
      $this->count = $db->count;
      return $userdocuments;
    }

    //get documents download history
    public function getDocumentHistory($userid,$db)
    {
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
      $this->count = $db->count;
      return $downloads;
    }

    //insert new document
    public function DocumentInsert($data,$db)
    {
      $documentId = $db->insert ('tbldocument', $data);
      return $documentId;
    }

    //insert new documentCategoryxRef
    public function DocumentCategoryInsert($data,$db)
    {
      $documentCatId = $db->insert ('tbldocumentcategoryxref', $data);
      return $documentCatId;
    }

    //insert new documentCategoryxRef
    public function DocumentUserInsert($data,$db)
    {
      $documentUserId = $db->insert ('tbldocumentUserXref', $data);
      return $documentUserId;
    }



}


?>
