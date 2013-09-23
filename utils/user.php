<?php
//include('database.php');

class user{
   const QUERY_USER_BY_NAME = 'select user_id, user_registration, user_editcount from user u where user_name= ?;';

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
       $this->user_name = $aName;
       $result = $connection->execute(user::QUERY_USER_BY_NAME,array($this->user_name));
       if ($result != NULL){
         $this->user_id = $result[0][0]; 
         $this->user_registration = $result[0]['user_registration'];
         $this->user_edit_count = $result[0]['user_editcount'];
         $this->loaded = true;
       }
   } 

   public function printUser()
   {
     
      if ($this->loaded) {
?>
<fieldset><legend>User</legend>
<p><strong>Name : </strong> <?php echo $this->user_name; ?></p>
<p><strong>User ID : </strong> <?php echo $this->user_id; ?></p>
<p><strong>Registered : </strong> <?php echo date('d/m/Y',strtotime($this->user_registration)); ?></p>
</fieldset>
<?php     
     } else {
       echo '<fieldset><legend>User</legend>
User NOT found !
</fieldset>';
  
 
     }
   }
   
 
}

/*
 */

?>
