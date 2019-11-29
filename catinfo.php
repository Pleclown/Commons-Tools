<?php

include('utils/functions.php');
include('utils/category.php');

include('utils/database.php');
$title = 'Cat info';
$h1='Information on cat';
include('utils/header.php');

if (!empty($_GET)) {
$name=noHTML($_GET['user']);
$category=noHTML($_GET['category']);
}else{
$name='';
$category = '';
}


?>
<fieldset><legend>Cat info</legend>
<p>Get informations on a category.
<form method="get" action="catinfo.php" id="mw-sulinfo-form1">
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
$cat->getUploadersInCat();
$cat->printUploadersInCatPieChart();
$cat->printUploadersInCatList();
}
}
include('utils/footer.php');
 
?>
