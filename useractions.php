<?php
include('utils/functions.php');
include('utils/user.php');

include('utils/database.php');
$title = 'Useractions';
$h1='Useractions';
include('utils/header.php');


if (!empty($_GET)) {
$project = $_GET['project'];
        $name=$_GET['user'];
}else{
        $name='';
$project = '';
}
$metadb = new metadatabase;
?>
<fieldset><legend>User actions</legend>
<form method="get" action="useractions.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table"> 
<tr><td class='mw-label'><label for="wiki">Project </label></td><td class='mw-input'><select class="form-control" name="project" id="wikiDb" required>
					<?php
					       echo $metadb->listSelectWiki($project);
					?>
					</select></td></tr>
<tr><td class='mw-label'><label for="username">Username :</label></td><td class='mw-input'><input id="user" name="user" type="text" value="<?php echo $name; ?>"/></td><tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php
if ($name != '') {
$db = new database;
if ($db->connect($project)) {
$user = new user($db,$name);

$user->printUser();
$user->getUserActions();
$user->printGeneralInfos();

}
}
include('utils/footer.php');

?>
