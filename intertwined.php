<?php


include('utils/functions.php');
include('utils/contributions.php');

include('utils/database.php');
$title = 'Intertwined contributions';
$h1='Intertwined contributions';
include('utils/header.php');

if (!empty($_GET)) {
$user1=$_GET['user1'];
$user2=$_GET['user2'];
}else{
$user1='';
$user2='';
}


?>
<fieldset><legend>Intertwined contributions</legend>
<form method="get" action="uploadersincat.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table"> 
<tr><td class='mw-label'><label for="user1">User 1 :</label></td><td class='mw-input'><input id="user1" name="user1" type="text" value="<?php echo $user1; ?>"/></td>
<tr><td class='mw-label'><label for="user2">User 2 :</label></td><td class='mw-input'><input id="user2" name="user2" type="text" value="<?php echo $user2; ?>"/></td>
<tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php

if (($user1 != '') {
$db = new database;
if ($db->connect('frwiki')) {
$contribs = new contributions($db,$user1);

$contribs->getIntertwinedContribs($user2);
$contribs->printIntertwinedContribs();
}
}
include('utils/footer.php');
 
?>
