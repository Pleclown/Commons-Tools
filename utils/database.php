<?php
class database{
public $connection;
public $connected = false;
  public function connect($dbname){
    //$ts_pw = posix_getpwuid(posix_getuid());
    $prefix = getenv("DOCUMENT_ROOT") . "/..";
    if (getenv("DOCUMENT_ROOT") === false) {
        $prefix = getenv("HOME");
    }
    $ts_mycnf = parse_ini_file($prefix. "/replica.my.cnf");
    try {
      if ($dbname == 'meta')
      {
        $db = 'enwiki';
      }
      else
      {
        $db = $dbname;
      }
      $this->connection = new PDO('mysql:host='.$db.'.labsdb;dbname='.$dbname.'_p', $ts_mycnf['user'], $ts_mycnf['password']);
      $this->connected = true;
    
    } catch (PDOException $e) {
      echo '<fieldset><legend>Error</legend>Error connecting to database. '.$e->getMessage() .'</fieldset>';
    }
    return $this->connected;
  }

  public function query($query){
    $stmt = $this->connection->query($query);
    return $stmt->fetchAll();
  }


  public function execute($query,$params){
    $stmt = $this->connection->prepare($query);    
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  public function insert($query,$params,$returnid = false)
  {
    $result = -1;
    $stmt = $this->connection->prepare($query);  
    try {
        $this->connection->beginTransaction();
        $stmt->execute($params);
        if ($returnid = true)
          $result = $this->connection->lastInsertId();
        $this->connection->commit();
    } catch(PDOException $e) {
        $this->connection->rollback();
        echo 'Error!: ' . $e->getMessage() . '</br>';
    }
    return $result;
  }
  
  public function update($query,$params)
  {
    $stmt = $this->connection->prepare($query);  
    try {
        $this->connection->beginTransaction();
        $stmt->execute($params);
        $this->connection->commit();
    } catch(PDOException $e) {
        $this->connection->rollback();
        echo 'Error!: ' . $e->getMessage() . '</br>';
    }   
  }
}

class metadatabase extends database
{
  public $wikilist;
  public $loaded = false;
  function __construct()
  {
    $this->connect('meta');
    if ($this->connected)
    {
      $result = $this->query('SELECT dbname, url FROM wiki ORDER BY url;');
      if ($result != NULL)
      {
        $this->loaded = true;
        foreach ($result as $row)
        {
          $this->wikilist[$row['dbname']]= preg_replace('#https?://#', '', $row['url']);

        }
  
      }

    }
    else
    {
      echo 'raté';
    }
    
  }
  
  function listSelectWiki($aSelectedWiki)
  {
    $result = '';
    foreach($this->wikilist as $key=>$value)
    {
        $selected = ($aSelectedWiki == $key ? ' selected' : '');
        $result = $result.'<option value="'.$key.'"'.$selected.' >'.$value.'</option>\n';
    }
    return $result;
  }
  
  
}

?>
