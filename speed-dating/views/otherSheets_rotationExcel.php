<?
require_once ('helpers/helper_excel.php');

	$datas = array();
	$line = array();
	for($i=0;$i<$nbRotations;$i++) {
		$line[] = $i+1;$line[] = '';$line[] = '';
	}
	$datas[] = $line;
	$line = array();
	for($i=0;$i<$nbRotations;$i++) {
		$line[] = 'L';$line[] = 'G';$line[] = 'T';
	}
	$datas[] = $line;
	for($i=0;$i<$nbTables;$i++){
		$line = array();
		for($j=0;$j<$nbRotations;$j++) {
			$line[]=$i+1;
			$line[]=((($startG+$jumpG*$j+$i)%$nbTables)+$nbTables+1);
			$line[]=((($startT+$jumpT*$j+$i)%$nbTables)+1);
		}
		$datas[] = $line;
	}
	$xls = new Excel_XML('UTF-8', false, 'rotation-'.$nbTables);
	$xls->addArray($datas);
	$xls->generateXML('rotation-'.$nbTables.'.xls');
	exit();



?>