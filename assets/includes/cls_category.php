<?php
class Category
{

    //get categories for given userId
    public function getUserCategories($userid,$db)
    {
      $params = Array($userid);
      $q = "(
      SELECT c.category
      FROM tblusers u
      INNER JOIN tblusercategoryxref ucX ON u.userId = ucx.userId
      INNER JOIN tblcategory c ON ucx.categoryId = c.categoryId
      WHERE u.userId = ?
      )";
      return $categories = $db->rawQuery ($q, $params);
    }

}


?>
