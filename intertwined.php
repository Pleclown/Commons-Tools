<?php


include('utils/functions.php');
include('utils/contributions.php');

include('utils/database.php');
$title = 'Intertwined contributions';
$h1='Intertwined contributions';
include('utils/header.php');

if (!empty($_GET)) {
$project = noHTML($_GET['project']);
$user1= noHTML($_GET['user1']);
$user2=noHTML($_GET['user2']);
$after=noHTML($_GET['after']);
$before=noHTML($_GET['before']);
	
}else{
$project = '';
$user1='';
$user2='';
$after='';
$before='';
}

$metadb = new metadatabase;
?>
<fieldset><legend>Intertwined contributions</legend>
This tool displays contributions of the two users.
The dates should be in the format YYYY-MM-DD. If no date is selected, the last 1000 cumulated contributions will be displayed.	
If you want to see all the pages edited by both users, see <a href='//tools.wmflabs.org/intersect-contribs/' title='Intersect contribs'>Intersect contribs</a> by Pietrodn.
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
<tr><td class='mw-label'><label for="after">After :</label></td><td class='mw-input'><input id="after" name="after" type="text" value="<?php echo $after; ?>"/></td>
<tr><td class='mw-label'><label for="before">Before :</label></td><td class='mw-input'><input id="before" name="before" type="text" value="<?php echo $before; ?>"/></td>
<tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php

if ($user1 != '') {
$db = new database;
if ($db->connect($project)) {
$contribs = new contributions($db,$project,$user1,$metadb);

$contribs->getIntertwinedContribs($user2,$after,$before);
$contribs->printIntertwinedContribs();
}
}
include('utils/footer.php');
 
?>
