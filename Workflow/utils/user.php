<?php

class user{
   const QUERY_USER = 'select * from user u where id= ?;';
   const INSERT_USER = 'insert into user(name, email) values (?,?);
   const UPDATE_USER = 'update user set name= ?, email= ? where id = ?';
   const DEL_USER = 'delete from user where id = ?';
   
   
   public $loaded = false;
   public $id = '';
   public $name = '';
   public $email = '';

   private $connection;

   function __construct($aConnection)
   {
     $this->connection = $aConnection;
   }

   public function load($id)
   {
       $this->id = $id;
       $result = $this->connection->execute(user::QUERY_USER,array($this->id));
       if ($result != NULL){
         $this->name = $result[0][1];
         $this->email = $result[0][2];
         $this->loaded = true;
       }
   }
   
   public function newUser($aName, $aEmail)
   {
       $result = $this->connection->insert(user::INSERT_USER,array($aName, $aEmail), true);
       if ($result != NULL){
         $this->id = $result;
         $this->name = $aName;
         $this->email = $aEmail;
         $this->loaded = true;
       }
   }
   
   public function Save()
   {
      $this->connection->update(user::UPDATE_USER,array($this->name, $this->email, $this->id));
   }
   
   public function Del($aId)
   {
      $this->connection->update(user::DEL_USER,array($aId));
   }

}   


?>
