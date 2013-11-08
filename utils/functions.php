<?php
const QUERY_USER_IN_CAT ='select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = ? and page_id in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_USER_NOT_IN_CAT ='select distinct page_title from page, image where page_namespace = 6 and img_name = page_title and img_user = ? and page_id not in (SELECT distinct cl_from from categorylinks where cl_to IN (?))';

const QUERY_UPLOADERS_IN_CAT = 'select distinct img_user_text from image, page, categorylinks where page_namespace = 6 and img_name = page_title and page_id = cl_from and cl_to in (?);'

function octets($n){
	$i=0;
	while ($n > 1024){
		$i++;
		$n = round($n / 1024);
	}
	switch ($i){
		case 0: $n.=' B';
          		break;
		case 1: $n.=' KiB';
			break;
		case 2: $n.=' MiB';
			break;
		case 3: $n.=' GiB';
			break;
		default: $n.=' toomuch';
	}
	return $n;
}


function PieChart($array, $column,$title,$div)
{
  $Result ='      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn(\'string\', \'Type\');
        data.addColumn(\'number\', \''.$column.'\');
        data.addRows([';
	foreach($array as $key => $value)
	{
		$Result.='[\''.$key.'\','.$value.'],';	
	}
	$Result.='        ]);

        var options = {
          width: 600, height: 500,
          title: \''.$title.'\'
        };

        var chart = new google.visualization.PieChart(document.getElementById(\''.$div.'\'));
        chart.draw(data, options);
      }';
      return $Result;
}


function MonthBarGraph($array, $column,$title,$div)
{
	$Result = 'google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn(\'string\', \'Month\');
        data.addColumn(\'number\', \''.$column.'\');
	data.addRows([';
	foreach($array as $key => $value)
	{
		$Result.='[\''.$key.'\','.$value.'],';	
	}
	$Result.='        ]);

        var options = {
          width: 1300, height: 350,
          title: \''.$title.'\',
          isStacked: true,
		  reverseCategories: false
        };

        var chart = new google.visualization.ColumnChart(document.getElementById(\''.$div.'\'));
        chart.draw(data, options);
      }';
	return $Result;
}



//phpinfo();




?>
