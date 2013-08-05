<?php

include('utils/functions.php');
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

$name = htmlspecialchars($name);
$category = htmlspecialchars($category);

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
$category = str_replace ( ' ' , '_' , $category);
$query = 'select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = '.$user_id.' and page_id ';
if ($reverse=='true') $query .= 'not';
$query .=' in (SELECT distinct cl_from from categorylinks where cl_to IN ("'.$category.'"))';

$result = mysql_query($query);

//echo $query;

if (!$result){
	die('Invalid query: ' .mysql_error());
}
while ($row = mysql_fetch_assoc($result)) {
$list .= '<a href="//commons.wikimedia.org/wiki/File:'.$row['page_title'].'" >File:'.$row['page_title'].'</a><br/>';
}

mysql_free_result($result); 
?>
<fieldset><legend>User</legend>
<p><strong>Name : </strong> <?php echo $name; ?></p>
<p><strong>User ID : </strong> <?php echo $user_id; ?></p>
<p><strong>Registered : </strong> <?php echo date('d/m/Y',strtotime($user_registration)); ?></p>
</fieldset>
<fieldset><legend>List</legend>
<?php
echo $list;
?>
</fieldset>
<?php
}
/*
 */
include('utils/footer.php');
 
?>

