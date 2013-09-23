<?php
class database{
public $connection;
public $connected = false;
  public function connect($db){
    //$ts_pw = posix_getpwuid(posix_getuid());
    $ts_mycnf = parse_ini_file("../replica.my.cnf");
    try {
      $this->connection = new PDO('mysql:host=commonswiki.labsdb;dbname=commonswiki_p', $ts_mycnf['user'], $ts_mycnf['password']);
      $this->connected = true;
    
    } catch (PDOException $e) {
      echo '<fieldset><legend>Error</legend>Error connecting to database. '.$e->getMessage() .'</fieldset>';
    }
    return $this->connected;
  }

  public function execute($query,$params){
    $stmt = $this->connection->prepare($query);    
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

}
?>
