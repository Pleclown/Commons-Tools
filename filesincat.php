<?php


include('utils/functions.php');

include('utils/database.php');
$title = 'Files in cat';
$h1='Commons files in cat';
include('utils/header.php');

if (!empty($_GET)) {
$name=$_GET['user'];
$category=$_GET['category'];
}else{
$name='';
$category = '';
}


?>
<fieldset><legend>Files in cat</legend>
<p>Get information on files for a category.
<form method="get" action="filesincat.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table"> 
<tr><td class='mw-label'><label for="category">Category :</label></td><td class='mw-input'><input id="category" name="category" type="text" value="<?php echo $category; ?>"/></td>
<tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php

if ($category != '') {
$db = new database;
if ($db->connect('commonswiki')) {

$category = str_replace ( ' ' , '_' , $category);
$result=$db->execute(QUERY_FILES_IN_CAT_BY_MONTH,array($category)); 


if ($result != NULL){
foreach ($result as $row)
{
$list .= 'Mois '.$row['created_month'].' :'.$row['compte'].' images<br/>';
}

}

?>
<fieldset><legend>Result</legend>
<?php
echo $list;
?>
</fieldset>
<?php

}
}
include('utils/footer.php');
 
?>
