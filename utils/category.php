<?php

class category{

const QUERY_CAT_BY_NAME ='select cat_pages, cat_subcats, cat_files from category where cat_title = ?;';

const QUERY_USER_IN_CAT ='select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = ? and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_USER_NOT_IN_CAT ='select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = ? and page_id not in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_UPLOADERS_IN_CAT = 'select distinct img_user_text, count(img_name) as compte from image, page, categorylinks where page_namespace = 6 and img_name = page_title and page_id = cl_from and cl_to in (?) group by img_user';

const QUERY_FILES_IN_CAT_BY_MONTH = 'select DATE_FORMAT(img_timestamp,"%Y-%m") as created_month, count(img_name) as compte from image ,page where img_name = page_title and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?)) group by created_month order by created_month;';

  public $loaded= false;
  public $catname;
  public $cattitle;
  public $catfiles;
  public $catpages;
  public $catsubcats;
  public $uploaders;
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
      foreach ($result as $row)
      {
        $this->uploaders[$row['img_user_text']]= $row['compte'];
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
</fieldset>
<?php
  }
  
  public function printUploadersInCatList()
  {
?>
<fieldset><legend>List</legend>
<?php
    foreach($array as $key => $value)
    {
      echo '<a href="//commons.wikimedia.org/wiki/User:'.$key.'" >User:'.$row['img_user_text'].'</a> : '.$value.' files.<br/>';
    }
?>
</fieldset>
<?php
  }
  

   }
?>
