<?php 
	$q = $_GET["q"];
	$query = explode(",", $q);
	$output = array();
	foreach ($query as $phrase){
		
		$command = escapeshellcmd("/var/www/html/scholar.py -c 5 -t --phrase '$phrase'");
		$out = shell_exec($command);
		$output[] = $out;		
	}

	$result = shell_exec("python /var/www/html/parsedata.py ". escapeshellarg(json_encode($output)));
	echo json_encode($result);
?>
