<?php


include('utils/functions.php');
include('utils/user.php');

include('utils/database.php');
$title = 'Uploaders in cat';
$h1='Commons Uploaders in cat';
include('utils/header.php');

if (!empty($_GET)) {
$category=$_GET['category'];
}else{
$category = '';
}


?>
<fieldset><legend>Find uploaders in cat</legend>
<form method="get" action="uploadersincat.php" id="mw-sulinfo-form1">
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
$result=$db->execute(QUERY_UPLOADERS_IN_CAT,array($category)); 

$list = '';

if ($result != NULL){
foreach ($result as $row)
{
$list .= '<a href="//commons.wikimedia.org/wiki/User:'.$row['img_user_text'].'" >User:'.$row['img_user_text'].'</a> : '.$row['compte'].' files.<br/>';
}

}

?>
<fieldset><legend>List</legend>
<?php
echo $list;
?>
</fieldset>
<?php
}
}
include('utils/footer.php');
 
?>

