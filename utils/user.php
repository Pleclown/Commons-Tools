<?php
include('database.php');

class User {
   QUERY_USER_BY_NAME = 'select user_id, user_registration, user_editcount from user u where user_name= ?;';

   public $loaded = false;
   public $user_id = '';
   public $user_name = '';
   public $user_edit_count = '';
   public $user_registration = '';
   private $connection;
   private $result;

   public function load($aConnection, $aName)
   {
       $connection = $aConnection;
       $user_name = $aName;
       $result = $connection->execute(QUERY_USER_BY_NAME,$user_name);
       if ($result != NULL){
         $user_id = $result['user_id']; 
         $user_registration = $result['user_registration'];
         $user_edit_count = $result['user_editcount'];
         $loaded = true;
       }
   } 

   public function printUser
   {
     if $loaded {
?>
<fieldset><legend>User</legend>
<p><strong>Name : </strong> <?php echo $name; ?></p>
<p><strong>User ID : </strong> <?php echo $user_id; ?></p>
<p><strong>Registered : </strong> <?php echo date('d/m/Y',strtotime($user_registration)); ?></p>
</fieldset>
<?php     
     } else {
?>
<fieldset><legend>User</legend>
User NOT found !
</fieldset>
<?php     
 
     }
   }
}


?>
