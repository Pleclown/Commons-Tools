<?php

include('utils/functions.php');
include('utils/category.php');

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
<fieldset><legend>Files in category</legend>
<p>Get information on files for a category, including count of members and time of uploads.
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

$cat = new category($db,$category);
$cat->printCat();
$cat->getFilesInCatByMonth();
$cat->printFilesInCatByMonth();
}
}
include('utils/footer.php');
 
?>
