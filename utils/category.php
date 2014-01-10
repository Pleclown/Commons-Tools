<?php

class category{

const QUERY_USER_IN_CAT ='select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = ? and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_USER_NOT_IN_CAT ='select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = ? and page_id not in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_UPLOADERS_IN_CAT = 'select distinct img_user_text, count(img_name) as compte from image, page, categorylinks where page_namespace = 6 and img_name = page_title and page_id = cl_from and cl_to in (?) group by img_user';

const QUERY_FILES_IN_CAT_BY_MONTH = 'select DATE_FORMAT(img_timestamp,"%Y-%m") as created_month, count(img_name) as compte from image ,page where img_name = page_title and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?)) group by created_month order by created_month;';

  public $catname;
  private $connection;
   
  function __construct($aConnection, $aName)
  {
    $this->connection = $aConnection;
    $this->load($aName);
  }

   public function load($aName)
   {
       $this->catname = $aName;
       $result = $this->connection->execute(user::QUERY_CAT_BY_NAME,array($this->catname));
       if ($result != NULL){
         $this->loaded = true;
       }
   } 


?>
