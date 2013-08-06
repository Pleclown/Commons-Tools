<?php
/*
$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file("../replica.my.cnf");
$db = mysql_connect();
unset($ts_mycnf, $ts_pw);
 
mysql_select_db('commonswiki_p', $db);

 
// YOUR REQUEST HERE
$result = mysql_query('select user_id, user_registration, user_editcount from user u where user_name="'.$name.'";');
if (!$result){
die('Invalid query: ' .mysql_error());
}
while ($row = mysql_fetch_assoc($result)) {
    $user_id = $row['user_id']; 
    $user_registration = $row['user_registration'];
    $user_edit_count = $row['user_editcount'];
}

mysql_free_result($result); 
*/
class database{
private $connection;
public $connected = false;
  public function connect($db){
    $ts_pw = posix_getpwuid(posix_getuid());
    $ts_mycnf = parse_ini_file("../replica.my.cnf");
    $connection = new mysqli('commonswiki.labsdb', $ts_mycnf['user'], $ts_mycnf['password'],'commonswiki_p');
    if ($connection->connect_error){
      echo '<fieldset><legend>Error</legend>Error connecting to database.</fieldset>';
    }else
    {
      $connected = true;
    }
    return $connected;
  }

  public function execute($query,$params){
    $stmt =  $connection->stmt_init();    
    if ($stmt->prepare($query)) {
      call_user_func_array(array($stmt, 'bind_param'), refValues($params));
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_array(MYSQLI_NUM);
    }
    else
    {
      return NULL;
    }   
  }
}
?>
