<?php
class database{
private $connection;
public $connected = false;
  public function connect($db){
    $ts_pw = posix_getpwuid(posix_getuid());
    $ts_mycnf = parse_ini_file("../replica.my.cnf");
    try {
      $connection = new PDO('mysql:host=commonswiki.labsdb;dbname=commonswiki_p', $ts_mycnf['user'], $ts_mycnf['password']);
      $connected = true;
    
    } catch (PDOException $e) {
      echo '<fieldset><legend>Error</legend>Error connecting to database. '.$e->getMessage() .'</fieldset>';
    }
    return $connected;
  }

  public function execute($query,$params){
    $stmt = $connection->prepare($query);    
    call_user_func_array(array($stmt, 'bindParam'), refValues($params));
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
?>
