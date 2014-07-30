<?php
include_once('functions.php');

class contributions{

  const QUERY_INTERTWINED_CONTRIBS = 'select rev_id, rev_user_text, page_title, rev_timestamp, rev_comment, page_namespace FROM revision_userindex, page WHERE (rev_user_text = ? or rev_user_text = ?) AND page_id=rev_page order by rev_id desc limit 1000';

  public $loaded= false;
  public $user;
  public $intertwineduser;
  public $contributions;
  private $connection;
  private $project;
  private $meta;
  private $namespaces;

  function __construct($aConnection, $aProject, $aName, $aMeta)
  {
    $this->connection = $aConnection;
    $this->user = $aName;
    $this->project = $aProject;
    $this->meta = $aMeta;
  }
  
  public function getIntertwinedContribs($aName)
  {
    $this->intertwineduser = $aName;
    
    $result = $this->connection->execute(contributions::QUERY_INTERTWINED_CONTRIBS,array($this->user, $this->intertwineduser));
    if ($result != NULL){
      foreach ($result as $row)
      {
        $this->contributions[$row['rev_id']]= array($row['rev_user_text'],$row['page_title'],$row['rev_timestamp'],$row['rev_id'],$row['rev_comment'],$row['page_namespace']);
      }
      $this->loaded = true;
    }

  }
  
  private function formatContrib($user,$page_title,$timestamp,$oldid,$comment,$pageNamespace)
  {
    $namespaceName = $this->namespaces[$pageNamespace];
		$page_title = ($namespaceName
					? $namespaceName . ":" . $page_title
					: $page_title);
    
    $page_title_clean = str_replace ( '_' , ' ' , $page_title);
    return '<a href="//fr.wikipedia.org/w/index.php?title='.$page_title.'&oldid='.$oldid.'" title="'.$page_title_clean.'">'.formatMWTimestamp($timestamp).'</a> (<a href="//fr.wikipedia.org/w/index.php?title='.$page_title.'&diff=prev&oldid='.$oldid.'" title="'.$page_title_clean.'">diff</a> | <a href="//fr.wikipedia.org/w/index.php?title='.$page_title.'&action=history" title="'.$page_title_clean.'">hist</a>) <span class="mw-changeslist-separator">. .</span> <span dir=ltr>(<a href="//fr.wikipedia.org/wiki/User:'.$user.'">'.$user.'</a>)</span> <span class="mw-changeslist-separator">. .</span> <a href="//fr.wikipedia.org/wiki/'.$page_title.'" title="'.$page_title_clean.'" class="mw-contributions-title">'.$page_title_clean.'</a> ‎ <span class="comment">('.htmlentities($comment,ENT_QUOTES,"UTF-8").')</span>';
  }
  
  public function printIntertwinedContribs()
  {
?>
<fieldset><legend>List</legend>
<?php
    if ($this->loaded){
      $this->namespaces = getNamespacesAPI($this->meta->wikilist[$project]);
      print_dump($this->namespaces);
      echo '<ul>';
      foreach($this->contributions as $key => $value)
      {
        if ($value[0]==$this->user)
          $color='#C6FFB3';
        else
          $color='#FFCCCC';
        echo ' <li style="background-color: '.$color.' !important;">'.$this->formatContrib($value[0],$value[1],$value[2],$value[3],$value[4],$value[5]).'</li>';
      }
      echo '</ul>';
    }else
    {
      echo 'Not loaded...';
    }
?>
</fieldset>
<?php  
  }

}

?>
