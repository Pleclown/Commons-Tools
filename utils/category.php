<?php

class category{

const QUERY_CAT_BY_NAME ='select cat_pages, cat_subcats, cat_files from category where cat_title = ?;';

const QUERY_USER_IN_CAT ='select distinct page_title from page, image, actor where page_namespace = 6 and img_name = page_title and img_actor = actor_id and actor_name = ? and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_USER_NOT_IN_CAT ='select distinct page_title from page, image, actor where page_namespace = 6 and img_name = page_title and img_actor = actor_id and actor_name = ? and page_id not in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_UPLOADERS_IN_CAT = 'select distinct actor_name, count(img_name) as compte from image, page, categorylinks, actor where page_namespace = 6 and img_name = page_title and page_id = cl_from and cl_to in (?) and actor_id=img_actor group by img_actor order by count(img_name) desc';

const QUERY_FILES_IN_CAT_BY_MONTH = 'select DATE_FORMAT(img_timestamp,"%Y-%m") as created_month, count(img_name) as compte from image ,page where img_name = page_title and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?)) group by created_month order by created_month;';

  public $loaded= false;
  public $catname;
  public $cattitle;
  public $catfiles;
  public $catpages;
  public $catsubcats;
  public $uploaders;
  public $uploadersCounter;
  public $filesInCatFor;
  public $filesIncatForCounter;
  public $filesInCatByMonth;
  private $connection;
   
  function __construct($aConnection, $aName)
  {
    $this->connection = $aConnection;
    $this->load($aName);
  }

   public function load($aName)
   {
       $this->cattitle = $aName;
       $this->catname = str_replace ( ' ' , '_' , $this->cattitle);
       $result = $this->connection->execute(category::QUERY_CAT_BY_NAME,array($this->catname));
       if ($result != NULL){
         $this->catfiles = $result[0]['cat_files'];
         $this->catpages = $result[0]['cat_pages'];
         $this->catsubcats = $result[0]['cat_subcats'];
         $this->loaded = true;
       }
   } 

   public function printCat()
   {
     
      if ($this->loaded) {
?>
<fieldset><legend>Cat</legend>
<p><strong>Name    : </strong> <?php echo $this->cattitle; ?></p>
<p><strong>Subcats : </strong> <?php echo $this->catsubcats; ?></p>
<p><strong>Pages   : </strong> <?php echo $this->catpages; ?></p>
<p><strong>Files   : </strong> <?php echo $this->catfiles; ?></p>
</fieldset>
<?php
     } else {
       echo '<fieldset><legend>Cat</legend>
Cat NOT found !
</fieldset>';
    }
  }

  public function getUploadersInCat()
  {
    $result = $this->connection->execute(category::QUERY_UPLOADERS_IN_CAT,array($this->catname));

    if ($result != NULL){
      $this->uploadersCounter = 0;
      foreach ($result as $row)
      {
        $this->uploaders[$row['actor_name']]= $row['compte'];
        $this->uploadersCounter++;
      }
    }
  }

  public function printUploadersInCatPieChart()
  {
?>
<fieldset><legend>PieChart</legend>
        <script type="text/javascript">
<?php
  echo PieChart($this->uploaders,'Uploaders','Number of files by uploader','chart_div_a');
?>
    </script>
<div id="chart_div_a" style="float:right"></div>
<p><strong>Total : </strong><?php echo $this->uploadersCounter; ?> uploaders.<p>
</fieldset>
<?php
  }
  
  public function printUploadersInCatList()
  {
?>
<fieldset><legend>List</legend>
<?php
    foreach($this->uploaders as $key => $value)
    {
      echo '<a href="//commons.wikimedia.org/wiki/User:'.$key.'" >User:'.$key.'</a> : '.$value.' files.<br/>';
    }
?>
</fieldset>
<?php
  }
  

  public function getFilesInCatFor($aUser,$aReverse)
  {
    if ($aReverse=='true')
    {
      $result=$this->connection->execute(category::QUERY_USER_NOT_IN_CAT,array($aUser,$this->catname)); 
    }
    else
    {
      $result=$this->connection->execute(category::QUERY_USER_IN_CAT,array($aUser,$this->catname)); 
    }
    
    if ($result != NULL){
      $this->filesInCatForCounter = 0;
      foreach ($result as $row)
      {
        $this->filesInCatFor[]= $row['page_title'];
        $this->filesInCatForCounter++;
      }
    }
  }

  public function printFilesInCatFor()
  {
?>
<fieldset><legend>List</legend>
<?php
    echo '<strong>'.$this->filesInCatForCounter.' files for the user in cat.</strong><br>';
    foreach ($this->filesInCatFor as $key => $value)
    {
      echo '<a href="//commons.wikimedia.org/wiki/File:'.$value.'" >File:'.$value.'</a><br/>';
    }
?>
</fieldset>
<?php
   
  }

  public function getFilesInCatByMonth()
  {
    $result=$this->connection->execute(category::QUERY_FILES_IN_CAT_BY_MONTH,array($this->catname));
    if ($result != NULL){
      foreach ($result as $row)
      {
        $this->filesInCatByMonth[$row['created_month']]= $row['compte'];
      }
    }
    
  }

  public function printFilesInCatByMonth()
  {
?>
<fieldset><legend>By month</legend>
        
        <script type="text/javascript">
<?php
  echo MonthBarGraph($this->filesInCatByMonth,'Count','Upload count by month','bar_div_b');
?>
    </script>
<div id="bar_div_b" style="float:right"></div>
<p><strong>Total : </strong><?php echo $this->catfiles; ?> files.<p>
</fieldset>
<?php
  }













   }
?>
