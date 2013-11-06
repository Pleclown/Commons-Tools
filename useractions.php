<?php
include('utils/functions.php');
include('utils/user.php');

include('utils/database.php');
$title = 'Useractions';
$h1='Commons Useractions';
include('utils/header.php');


if (!empty($_GET)) {
        $name=$_GET['user'];
}else{
        $name='';
}
?>
<fieldset><legend>Uploadcounter</legend>
<form method="get" action="useractions.php" id="mw-sulinfo-form1">
<table border="0" id="mw-movepage-table"> 
<tr><td class='mw-label'><label for="username">Username :</label></td><td class='mw-input'><input id="user" name="user" type="text" value="<?php echo $name; ?>"/></td><tr><td>&#160;</td><td class='mw-submit'><input type="submit" value="Go !" /></td></tr>
</table>
</form></fieldset>
<?php
if ($name != '') {
$db = new database;
if ($db->connect('commonswiki')) {
echo 'toto';
$user = new user($db,$name);

$user->printUser();
$user->getUserActions();
$user->printGeneralInfos();

<?php
}
include('utils/footer.php');

?>
