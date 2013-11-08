<?php


include('utils/functions.php');
include('utils/user.php');

include('utils/database.php');
$title = 'Find files in cat';
$h1='Commons files finder';
include('utils/header.php');

if (!empty($_GET)) {
$name=$_GET['user'];
$category=$_GET['category'];
$reverse=$_GET['reverse'];
}else{
$name='';
$category = '';
$reverse = '';
}


?>
<fieldset><legend>Find files in cat for user</legend>
<p>Get the files from a user for a category. If reverse is set, get the files from the user NOT in the category.
<form method="get" action="findfiles.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table"> 
<tr><td class='mw-label'><label for="username">Username :</label></td><td class='mw-input'><input id="user" name="user" type="text" value="<?php echo $name; ?>"/></td>
<tr><td class='mw-label'><label for="category">Category :</label></td><td class='mw-input'><input id="category" name="category" type="text" value="<?php echo $category; ?>"/></td>
<tr><td class='mw-label'><label for="reverse">Reverse :</label></td><td class='mw-input'><input id="reverse" name="reverse" type="checkbox" value="true" <?php if ($reverse == 'true') echo 'checked'; ?>/></td>
<tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php

if (($name != '') and ($category != '')) {
$db = new database;
if ($db->connect('commonswiki')) {
$user = new user($db,$name);
$user->printUser();

$category = str_replace ( ' ' , '_' , $category);
if ($reverse=='true')
{
  $result=$db->execute(QUERY_USER_NOT_IN_CAT,array($user->user_id,$category)); 
}
else
{
  $result=$db->execute(QUERY_USER_IN_CAT,array($user->user_id,$category)); 
}


if ($result != NULL){
foreach ($result as $row)
{
$list .= '<a href="//commons.wikimedia.org/wiki/File:'.$row['page_title'].'" >File:'.$row['page_title'].'</a><br/>';
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

