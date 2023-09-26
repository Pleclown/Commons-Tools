<?php
include_once('functions.php');

class contributions{

  const QUERY_INTERTWINED_CONTRIBS_BASE = 'select rev_id, page_title, rev_timestamp, page_namespace,actor_name, comment_text  
FROM revision_userindex r
left join page p on p.page_id = r.rev_page
left join actor a on a.actor_id = r.rev_actor
left join comment c on c.comment_id=r.rev_comment_id
WHERE (actor_name = ? or actor_name = ?)';

	const QUERY_INTERTWINED_CONTRIBS_AFTER = ' AND rev_timestamp > TIMESTAMP(?)';
	const QUERY_INTERTWINED_CONTRIBS_BETWEEN = ' AND rev_timestamp > TIMESTAMP(?) AND rev_timestamp < TIMESTAMP(?) '; 
	const QUERY_INTERTWINED_CONTRIBS_ORDER = ' order by rev_id desc';
	const QUERY_INTERTWINED_CONTRIBS_LIMIT = ' limit 1000';

  public $loaded= false;
  public $user;
  public $intertwineduser;
  public $contributions;
  private $connection;
  private $project;
  private $meta;
  private $namespaces;
  private $wikiHost;

  function __construct($aConnection, $aProject, $aName, $aMeta)
  {
    $this->connection = $aConnection;
    $this->user = $aName;
    $this->project = $aProject;
    $this->meta = $aMeta;
  }
  
  public function getIntertwinedContribs($aName,$aAfter,$aBefore)
  {
    $this->intertwineduser = $aName;
    $this->after = $aAfter;
    $this->before = $aBefore;

    if ($this->after != ''){
	if ($this->before != ''){
	    $this->type=3;
	}else{
	    $this->type=2;
	}
    }else{
        $this->type=1;
    }
	  
	  
    $query = contributions::QUERY_INTERTWINED_CONTRIBS_BASE;
    switch ($this->type) {
        case 1:	  
	    $query .= contributions::QUERY_INTERTWINED_CONTRIBS_ORDER;
	    $query .= contributions::QUERY_INTERTWINED_CONTRIBS_LIMIT;
            $result = $this->connection->execute($query,array($this->user, $this->intertwineduser));
	    break;
        case 2:
	    $query .= contributions::QUERY_INTERTWINED_CONTRIBS_AFTER;
	    $query .= contributions::QUERY_INTERTWINED_CONTRIBS_ORDER;
	    $result = $this->connection->execute($query,array($this->user, $this->intertwineduser,$this->after));
            break;	
        case 3:
	    $query .= contributions::QUERY_INTERTWINED_CONTRIBS_BETWEEN;
	    $query .= contributions::QUERY_INTERTWINED_CONTRIBS_ORDER;
	    $result = $this->connection->execute($query,array($this->user, $this->intertwineduser,$this->after,$this->before));
            break;
    }
    
    if ($result != NULL){
      foreach ($result as $row)
      {
        $this->contributions[$row['rev_id']]= array($row['actor_name'],$row['page_title'],$row['rev_timestamp'],$row['rev_id'],$row['comment_text'],$row['page_namespace']);
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
    return '<a href="//'.$this->wikiHost.'/w/index.php?title='.$page_title.'&oldid='.$oldid.'" title="'.$page_title_clean.'">'.formatMWTimestamp($timestamp).'</a> (<a href="//'.$this->wikiHost.'/w/index.php?title='.$page_title.'&diff=prev&oldid='.$oldid.'" title="'.$page_title_clean.'">diff</a> | <a href="//'.$this->wikiHost.'/w/index.php?title='.$page_title.'&action=history" title="'.$page_title_clean.'">hist</a>) <span class="mw-changeslist-separator">. .</span> <span dir=ltr>(<a href="//'.$this->wikiHost.'/wiki/User:'.$user.'">'.$user.'</a>)</span> <span class="mw-changeslist-separator">. .</span> <a href="//'.$this->wikiHost.'/wiki/'.$page_title.'" title="'.$page_title_clean.'" class="mw-contributions-title">'.$page_title_clean.'</a> ‎ <span class="comment">('.htmlentities($comment,ENT_QUOTES,"UTF-8").')</span>';
  }
  
  public function printIntertwinedContribs()
  {
?>
<fieldset><legend>List</legend>
<?php
    if ($this->loaded){
      $this->wikiHost = $this->meta->wikilist[$this->project];
      $this->namespaces = getNamespacesAPI($this->wikiHost);
      
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
