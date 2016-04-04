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

    public function getAllUsers($userid,$db)
    {
      //get all users
      $users = $db->get('tblusers');
      return $users;
    }

}


?>
