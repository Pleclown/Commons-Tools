<?php
//include('database.php');

class user{
   const QUERY_USER_BY_NAME = 'select user_id, user_registration, user_editcount from user u where user_name= ?;';
   const QUERY_USER_UPLOADS_SUMMARY = 'select img_media_type as type, DATE_FORMAT(img_timestamp,"%Y-%m") as created_month, sum(img_size) as somme, count(img_name) as compte from image where img_user = ? group by img_media_type, created_month order by created_month;'

   public $loaded = false;
   public $user_id = '';
   public $user_name = '';
   public $user_edit_count = '';
   public $user_registration = '';
   private $user_uploadcount = -1;
   private $user_uploadsize = -1;
   private $user_moves = -1;
   private $user_blocks = -1;
   private $user_delete = -1;
   private $user_uploadlog = -1;
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
<?php if ($this->user_uploadcount>-1) ?> 
<p><strong>Total uploadcount : </strong> <?php echo $this->user_uploadcount; ?></p>
<?php if ($this->user_uploadsize>-1) ?> 
<p><strong>Total size : </strong> <?php echo octets($this->user_uploadsize); ?></p>
</fieldset>
<?
   	}
   }
   
   public function getUserUploadsSummary()
   {

	$result = $this->connection->execute(user::QUERY_USER_UPLOADS_SUMMARY,array($this->user_id));
	if ($result != NULL){
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
