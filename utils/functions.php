<?php

function noHTML($input, $encoding = 'UTF-8')
{
    return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
}

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
  $Result ='      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
	[\'Type\',\''.$column.'\']';
	foreach($array as $key => $value)
	{
		$Result.=',[\''.$key.'\','.$value.']';	
	}
	$Result.=']);

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
	$Result = 'google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
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

function formatMWTimestamp($timestamp)
{
	$date = DateTime::createFromFormat('YmdHis',$timestamp);
	return date('d F Y \a\t H:i:s',$date->getTimestamp());
}

/* 	Coprid from Pietrodn
	Gets the namespaces via MediaWiki API.
	$wikiHost: wiki domain (e.g. "en.wikipedia.org")
	Returns: associative array of namespaces (id => name).
*/
function getNamespacesAPI($wikiHost)
{
	$conn = curl_init('https://' . $wikiHost .
		'/w/api.php?action=query&meta=siteinfo&siprop=namespaces&format=php');
	curl_setopt ($conn, CURLOPT_USERAGENT, "BimBot/1.0");
	curl_setopt($conn, CURLOPT_RETURNTRANSFER, True);
	$ser = curl_exec($conn);
	curl_close($conn);
	
	$unser = unserialize($ser);
	$namespaces = $unser['query']['namespaces'];
	
	$ns = array();
	foreach($namespaces as $i => $val) {
		$ns[$i] = $val['*'];
	}
		
	return $ns;
}
?>
