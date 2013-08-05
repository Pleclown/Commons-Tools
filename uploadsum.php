<?php
include('utils/functions.php');
$title = 'Uploadcounter';
$h1='Commons Uploadcounter';
include('utils/header.php');


if (!empty($_GET)) {
	$name=$_GET['user'];
}else{
	$name='';
}
$name = htmlspecialchars($name);
?>
<fieldset><legend>Uploadcounter</legend>
<form method="get" action="uploadsum.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table"> 
<tr><td class='mw-label'><label for="username">Username :</label></td><td class='mw-input'><input id="user" name="user" type="text" value="<?php echo $name; ?>"/></td><tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php
if ($name != '') {
$ts_pw = posix_getpwuid(posix_getuid());
$ts_mycnf = parse_ini_file("../replica.my.cnf");
$db = mysql_connect('commonswiki.labsdb', $ts_mycnf['user'], $ts_mycnf['password']);
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

$array_total_size= array();
$array_total_count= array();
$array_month_size= array();
$array_month_count= array();

$result = mysql_query('select img_media_type as type, DATE_FORMAT(img_timestamp,"%Y-%m") as created_month, sum(img_size) as somme, count(img_name) as compte from image i,user u where img_user =u.user_id and u.user_name="'.$name.'" group by img_media_type, created_month order by created_month;');


//$result = mysql_query('select img_media_type as type, sum(img_size) as somme, count(img_name) as compte from image i,user u where img_user =u.user_id and u.user_name="'.$name.'"  group by img_media_type;');
if (!$result){
	die('Invalid query: ' .mysql_error());
}
$str_piesize = '';
$str_descsize = '';
$str_piecount= '';
$str_desccount = '';
/*
while ($row = mysql_fetch_assoc($result)) {
    $type = $row['type']; 
    $somme = $row['somme'];
    $compte = $row['compte'];
    
    $somme2 += $somme;
    $compte2 += $compte;
    
    $str_piesize .= '[\''.$type.'\', '.$somme.'],';
    $str_descsize .= '<p><strong>'.$type.' :</strong> '.octets($somme).'</p>';
    $str_piecount .= '[\''.$type.'\', '.$compte.'],';
    $str_desccount .= '<p><strong>'.$type.' :</strong> '.$compte.' files.</p>';
}
 */
$somme2 = 0;
$compte2 = 0;
while ($row = mysql_fetch_assoc($result)) {
    $type = $row['type']; 
    $somme = $row['somme'];
    $compte = $row['compte'];
    $month = $row['created_month'];

    $somme2 += $somme;
    $compte2 += $compte;

    if (array_key_exists($type,$array_total_size)) {
        $array_total_size[$type] += $somme;
        $array_total_count[$type] += $compte;
    }
    else {
        $array_total_size[$type] = $somme;
        $array_total_count[$type] = $compte;
    }

    if (array_key_exists($month, $array_month_size)) {
        $array_month_size[$month] += $somme;
        $array_month_count[$month] += $compte;
    }
    else {
        $array_month_size[$month] = $somme;
        $array_month_count[$month] = $compte;
    }

}
foreach ($array_total_size as $type => $somme){
    $str_piesize .= '[\''.$type.'\', '.$somme.'],';
    $str_descsize .= '<p><strong>'.$type.' :</strong> '.octets($somme).'</p>';
}
foreach ($array_total_count as $type => $compte){
    $str_piecount .= '[\''.$type.'\', '.$compte.'],';
    $str_desccount .= '<p><strong>'.$type.' :</strong> '.$compte.' files.</p>';
}

mysql_free_result($result); 
?>
<fieldset><legend>General informations</legend>

<p><strong>Name : </strong> <?php echo $name; ?></p>
<p><strong>User ID : </strong> <?php echo $user_id; ?></p>
<p><strong>Registered : </strong> <?php echo date('d/m/Y',strtotime($user_registration)); ?></p>
<?php //<p><strong>Last activity : </strong> 10 August 2012</p>
?>
<p><strong>Total editcount : </strong> <?php echo $user_edit_count; ?></p>
<p><strong>Total uploadcount : </strong> <?php echo $compte2; ?></p>
<p><strong>Total size : </strong> <?php echo octets($somme2); ?></p>
</fieldset>
<fieldset><legend>Size</legend>
	
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Type');
        data.addColumn('number', 'Size');
        data.addRows([
<?php
echo $str_piesize;
?>
]);

        var options = {
          width: 600, height: 500,
          title: 'Size of file types'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div_a'));
        chart.draw(data, options);
      }
    </script>
<div id="chart_div_a" style="float:right"></div>
<?php
 echo $str_descsize;
?>
<p><strong>Total : </strong><?php echo octets($somme2); ?><p>
</fieldset>
<fieldset><legend>Count</legend>
	
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Type');
        data.addColumn('number', 'Count');
        data.addRows([
<?php
echo $str_piecount;
?>
]);

        var options = {
          width: 600, height: 500,
          title: 'Count of file types'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div_b'));
        chart.draw(data, options);
      }
    </script>
<div id="chart_div_b" style="float:right"></div>
<?php
 echo $str_desccount;
?>
<p><strong>Total : </strong><?php echo $compte2; ?> files.<p>
</fieldset>
<fieldset><legend>Month size</legend>
	
	<script type="text/javascript">
<?php
  echo MonthBarGraph($array_month_size,'Size','Upload size by month for '.$name,'bar_div_a');
?>
    </script>
<div id="bar_div_a" style="float:right"></div>
<p><strong>Total : </strong><?php echo octets($somme2); ?><p>
</fieldset>
<fieldset><legend>Month count</legend>
	
	<script type="text/javascript">
<?php
  echo MonthBarGraph($array_month_count,'Count','Upload count by month for '.$name,'bar_div_b');
?>
    </script>
<div id="bar_div_b" style="float:right"></div>
<p><strong>Total : </strong><?php echo $compte2; ?> files.<p>
</fieldset>

<?php
}
include('utils/footer.php');

?>

