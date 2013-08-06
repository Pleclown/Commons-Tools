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
public $connection;
public $connected = false;
  public function connect($db){
    //$ts_pw = posix_getpwuid(posix_getuid());
    $ts_mycnf = parse_ini_file("../replica.my.cnf");
    $this->connection = new mysqli('commonswiki.labsdb', $ts_mycnf['user'], $ts_mycnf['password'],'commonswiki_p');
    if ($this->connection->connect_error){
      echo '<fieldset><legend>Error</legend>Error connecting to database. '.$connection->connect_error.' '.$ts_mycnf['user'].'</fieldset>';
    }else
    {
      $this->connected = true;
    }
    return $this->connected;
  }

  public function execute($query,$params){
	  $stmt =  $this->connection->stmt_init();    
	  if ($stmt->prepare($query)) {
	    echo 'prepared';
	    call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
      $stmt->execute();
      
      $result = $stmt->get_result();
      return $result->fetch_array(MYSQLI_NUM);
    }
    else
    {
	    return NULL;
    }   
  }

  private function refValues($arr){
	          if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
			          {
					              $refs = array();
						                  foreach($arr as $key => $value)
									                  $refs[$key] = &$arr[$key];
								              return $refs;
								          }
		          return $arr;
		      }
}
?>
