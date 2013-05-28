<?php
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
