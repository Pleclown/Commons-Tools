<?php
//include('database.php');

class user{
   const QUERY_USER_BY_NAME = 'select user_id, user_registration, user_editcount from user u where user_name= ?;';
   const QUERY_USER_UPLOADS_SUMMARY = 'select img_media_type as type, DATE_FORMAT(img_timestamp,"%Y-%m") as created_month, sum(img_size) as somme, count(img_name) as compte from image where img_user = ? group by img_media_type, created_month order by created_month;';
   const QUERY_USER_ACTIONS = 'select log_type, count(*) as compte from logging where log_user = ? group by log_type;';
   
   public $loaded = false;
   public $uploadsum = false;
   public $useractions = false;
   public $user_id = '';
   public $user_name = '';
   public $user_edit_count = '';
   public $user_registration = '';
   private $user_uploadcount = 0;
   private $user_uploadsize = 0;
   
   private $userabuses = 0;
   private $userblocks = 0;
   private $userdeletions = 0;
   private $usergblblocks = 0;
   private $usergblrights = 0;
   private $userglobalauth = 0;
   private $userimports = 0;
   private $usermoves = 0;
   private $usercreations = 0;
   private $usertranslations = 0;
   private $userpatrols = 0;
   private $userprotections = 0;
   private $userrenames = 0;
   private $userrights = 0;
   private $userblacklist = 0;
   private $userthanks = 0;
   private $userreview = 0;
   private $useruploads = 0;
   
   
   private $array_total_size= array();
   private $array_total_count= array();
   private $array_month_size= array();
   private $array_month_count= array();


   private $connection;
   private $result;

   function __construct($aConnection, $aName)
   {
     $this->connection = $aConnection;
     load($aName);
   }

   public function load($aName)
   {
       $this->user_name = $aName;
       $result = $this->connection->execute(user::QUERY_USER_BY_NAME,array($this->user_name));
       if ($result != NULL){
         $this->user_id = $result[0][0]; 
         $this->user_registration = $result[0]['user_registration'];
         $this->user_edit_count = $result[0]['user_editcount'];
         $this->loaded = true;
       }
   } 


   public function getUserActions()
   {
   	$result = $this->connection->execute(user::QUERY_USER_ACTIONS,array($this->user_id));
	if ($result != NULL){
	  $this->useractions = true;
	  foreach($result as $row)
	  {
	    switch ($row['log_type']){
	    	case 'abusefilter': $this->userabuses = $row['compte'];
                              break;	    	
	    	case 'block': $this->userblocks = $row['compte'];
                              break;	    	
	    	case 'delete': $this->userdeletions = $row['compte'];
                              break;	    	
	    	case 'gblblock': $this->usergblblocks = $row['compte'];
                              break;	    	
	    	case 'gblrights': $this->usergblrights = $row['compte'];
                              break;	    	
	    	case 'globalauth': $this->userglobalauth = $row['compte'];
                              break;	    	
	    	case 'import': $this->userimports = $row['compte'];
                              break;	    	
	    	case 'move': $this->usermoves = $row['compte'];
                              break;	    	
	    	case 'newusers': $this->usercreations = $row['compte'];
                              break;	    	
	    	case 'pagetranslation': $this->usertranslations = $row['compte'];
                              break;	    	
	    	case 'patrol': $this->userpatrols = $row['compte'];
                              break;	    	
	    	case 'protect': $this->userprotections = $row['compte'];
                              break;	    	
	    	case 'renameuser': $this->userrenames = $row['compte'];
                              break;	    	
	    	case 'rights': $this->userrights = $row['compte'];
                              break;	    	
	    	case 'spamblacklist': $this->userblacklist = $row['compte'];
                              break;	    	
	    	case 'thanks': $this->userthanks = $row['compte'];
                              break;	    	
	    	case 'translationreview': $this->userreview = $row['compte'];
                              break;	    	
	    	case 'upload': $this->useruploads = $row['compte'];
                              break;
	    } 
	  }
	
	}
   }
   
   public function printUser()
   {
     
      if ($this->loaded) {
?>
<fieldset><legend>User</legend>
<p><strong>Name : </strong> <?php echo $this->user_name; ?></p>
<p><strong>User ID : </strong> <?php echo $this->user_id; ?></p>
<p><strong>Registered : </strong> <?php echo date('d/m/Y',strtotime($this->user_registration)); ?></p>
</fieldset>
<?php     
     } else {
       echo '<fieldset><legend>User</legend>
User NOT found !
</fieldset>';
  
 
     }
   }
   
   public function printGeneralInfos()
   {
   	if ($this->loaded) {
?>
<fieldset><legend>General informations</legend>
<p><strong>Total editcount : </strong> <?php echo $this->user_edit_count; ?></p>
<?php if ($this->uploadsum) {?> 
<p><strong>Total uploadcount : </strong> <?php echo $this->user_uploadcount; ?></p>
<p><strong>Total size : </strong> <?php echo octets($this->user_uploadsize); ?></p>
<?php
}
?>
<?php if ($this->useractions) {?> 
<p><strong>Log type 'abusefilter': </strong> <?php echo $this->userabuses; ?></p>
<p><strong>Log type 'block': </strong> <?php echo $this->userblocks; ?></p>
<p><strong>Log type 'delete': </strong> <?php echo $this->userdeletions; ?></p>
<p><strong>Log type 'gblblock': </strong> <?php echo $this->usergblblocks; ?></p>
<p><strong>Log type 'gblrights': </strong> <?php echo $this->usergblrights; ?></p>
<p><strong>Log type 'globalauth': </strong> <?php echo $this->userglobalauth; ?></p>
<p><strong>Log type 'import': </strong> <?php echo $this->userimports; ?></p>
<p><strong>Log type 'move': </strong> <?php echo $this->usermoves; ?></p>
<p><strong>Log type 'newusers': </strong> <?php echo $this->usercreations; ?></p>
<p><strong>Log type 'pagetranslation': </strong> <?php echo $this->usertranslations; ?></p>
<p><strong>Log type 'patrol': </strong> <?php echo $this->userpatrols; ?></p>
<p><strong>Log type 'protect': </strong> <?php echo $this->userprotections; ?></p>
<p><strong>Log type 'renameuser': </strong> <?php echo $this->userrenames; ?></p>
<p><strong>Log type 'rights': </strong> <?php echo $this->userrights; ?></p>
<p><strong>Log type 'spamblacklist': </strong> <?php echo $this->userblacklist; ?></p>
<p><strong>Log type 'thanks': </strong> <?php echo $this->userthanks; ?></p>
<p><strong>Log type 'translationreview': </strong> <?php echo $this->userreview; ?></p>
<p><strong>Log type 'upload': </strong> <?php echo $this->useruploads; ?></p><?php
}
?>
</fieldset>
<?
   	}
   }
   
   public function getUserUploadsSummary()
   {

	$result = $this->connection->execute(user::QUERY_USER_UPLOADS_SUMMARY,array($this->user_id));
	if ($result != NULL){
	  $this->uploadsum = true;
		foreach($result as $row)
		{
			$this->user_uploadcount += $row['compte'];
			$this->user_uploadsize  += $row['somme'];
			if (array_key_exists($row['type'],$array_total_size)) {
				$this->array_total_size[$row['type']] += $row['somme'];
				$this->array_total_count[$row['type']] += $row['compte'];
			}
			else {
				$this->array_total_size[$row['type']] = $row['somme'];
				$this->array_total_count[$row['type']] = $row['compte'];
			}

			if (array_key_exists($row['created_month'], $array_month_size)) {
				$this->array_month_size[$row['created_month']] += $row['somme'];
				$this->array_month_count[$row['created_month']] += $row['compte'];
			}
			else {
				$this->array_month_size[$row['created_month']] = $row['somme'];
				$this->array_month_count[$row['created_month']] = $row['compte'];
			}
		}
	  	
	}
   }
   
   public function PrintUploadsSummary()
   {
	$str_descsize = '';
	$str_desccount = '';


	foreach ($this->array_total_size as $type => $somme){
		$str_descsize .= '<p><strong>'.$type.' :</strong> '.octets($somme).'</p>';
	}
	foreach ($this->array_total_count as $type => $compte){
		$str_desccount .= '<p><strong>'.$type.' :</strong> '.$compte.' files.</p>';
	}
?>
<fieldset><legend>Size</legend>
	<script type="text/javascript">
<?php
  echo PieChart($this->array_total_size,'Size','Size of file types','chart_div_a');
?>
    </script>
<div id="chart_div_a" style="float:right"></div>
<?php
 echo $str_descsize;
?>
<p><strong>Total : </strong><?php echo octets($this->user_uploadsize); ?><p>
</fieldset>
<fieldset><legend>Count</legend>
	<script type="text/javascript">
<?php
  echo PieChart($this->array_total_count,'Count','Count of file types','chart_div_b');
?>
<div id="chart_div_b" style="float:right"></div>
<?php
 echo $str_desccount;
?>
<p><strong>Total : </strong><?php echo $this->user_uploadcount; ?> files.<p>
</fieldset>
<fieldset><legend>Month size</legend>
	
	<script type="text/javascript">
<?php
  echo MonthBarGraph($this->array_month_size,'Size','Upload size by month for '.$this->user_name,'bar_div_a');
?>
    </script>
<div id="bar_div_a" style="float:right"></div>
<p><strong>Total : </strong><?php echo octets($this->user_uploadsize); ?><p>
</fieldset>
<fieldset><legend>Month count</legend>
	
	<script type="text/javascript">
<?php
  echo MonthBarGraph($this->array_month_count,'Count','Upload count by month for '.$this->user_name,'bar_div_b');
?>
    </script>
<div id="bar_div_b" style="float:right"></div>
<p><strong>Total : </strong><?php echo $this->user_uploadcount; ?> files.<p>
</fieldset>
<?php
   }
 
}


?>
