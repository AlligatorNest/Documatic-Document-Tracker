<?php
class User
{

    public function getUsername($userid,$db)
    {
      //get current username
      $db->where ("userId", $userid);
      $user = $db->getOne ("tblusers");
      return $user['username'];
    }

}


?>
