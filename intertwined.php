<?php


include('utils/functions.php');
include('utils/contributions.php');

include('utils/database.php');
$title = 'Intertwined contributions';
$h1='Intertwined contributions';
include('utils/header.php');

if (!empty($_GET)) {
$project = $_GET['project'];
$user1=$_GET['user1'];
$user2=$_GET['user2'];
}else{
$project = '';
$user1='';
$user2='';
}

$metadb = new metadatabase;
?>
<fieldset><legend>Description</legend>
This tool diplays the last 1000 cumulated contributions of the two users. If you want to see all the pages edited by both users, see <a href='//tools.wmflabs.org/intersect-contribs/' title='Intersect contribs'>Intersect contribs</a> by Pietrodn.
</fieldset>
<fieldset><legend>Users</legend>
<form method="get" action="intertwined.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table">
<tr><td class='mw-label'><label for="wiki">Project </label></td><td class='mw-input'><select class="form-control" name="project" id="wikiDb" required>
					<?php
					       echo $metadb->listSelectWiki($project);
					?>
					</select></td></tr>
<tr><td class='mw-label'><label for="user1">User 1 :</label></td><td class='mw-input'><input id="user1" name="user1" type="text" value="<?php echo $user1; ?>"/></td>
<tr><td class='mw-label'><label for="user2">User 2 :</label></td><td class='mw-input'><input id="user2" name="user2" type="text" value="<?php echo $user2; ?>"/></td>
<tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php

if ($user1 != '') {
$db = new database;
if ($db->connect($project)) {
$contribs = new contributions($db,$project,$user1,$metadb);

$contribs->getIntertwinedContribs($user2);
$contribs->printIntertwinedContribs();
}
}
include('utils/footer.php');
 
?>
