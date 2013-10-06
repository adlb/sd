<?
require_once ('helpers/helper_excel.php');

// create a simple 2-dimensional array
$datas = array();
$maxRow = 1;
$maxCol = 1;

function setData($col, $row, $d){
	Global $datas, $maxRow, $maxCol;
	$datas[$row][$col] = $d;
	$maxRow = max($maxRow, $row);
	$maxCol = max($maxCol, $col);
}

function getDatas(){
	Global $datas, $maxRow, $maxCol;
	$table = array();
	for($col=0;$col<=$maxCol;$col++) {
		$cold = array();
		for($row=0;$row<=$maxRow;$row++) {
			if (isset($datas[$row]) && (isset($datas[$row][$col]))) {
				$cold[] = $datas[$row][$col];
			} else {
				$cold[] = null;
			}
		}
		$table[] = $cold;
	}
	return $table;
}

	setData(0,0,"Speed-Dating : ");
	setData(0,1,$party->name);
	setData(1,0,"Date : ");
	setData(1,1,date('d/m/y', $party->date));
	setData(3,0,"LADIES");
	setData(3,7,"GENTLEMAN");
    
	$xlsRow=4;
	for($i=0;$i<$party->maxPeople;$i++) {	
		setData($xlsRow,0,$i+1);
		if (count($ladies_inscrit)>0) { 
			$lady=array_shift($ladies_inscrit);
			setData($xlsRow,1,$lady->firstname);
			setData($xlsRow,2,$lady->lastname);
			setData($xlsRow,3,$lady->telephone);
			setData($xlsRow,4,$lady->email);
			setData($xlsRow,5,$lady->statut);
		}
		setData($xlsRow,7,$i+1+$party->maxPeople);
		if (count($gentlemen_inscrit)>0) { 
			$lady=array_shift($gentlemen_inscrit);
			setData($xlsRow,8,$lady->firstname);
			setData($xlsRow,9,$lady->lastname);
			setData($xlsRow,10,$lady->telephone);
			setData($xlsRow,11,$lady->email);
			setData($xlsRow,12,$lady->statut);
		}
		$xlsRow++;
	}
	$ladies_inscrit = array_merge($ladies_inscrit, $ladies_supprime);
	$gentlemen_inscrit = array_merge($gentlemen_inscrit, $gentlemen_supprime);		
	$xlsRow++;$xlsRow++;
	
	$max = max(count($ladies_inscrit),count($gentlemen_inscrit));
	for($i=0;$i<$max;$i++) {
		if (count($ladies_inscrit)>0) { 
			$lady=array_shift($ladies_inscrit);
			setData($xlsRow,1,$lady->firstname);
			setData($xlsRow,2,$lady->lastname);
			setData($xlsRow,3,$lady->telephone);
			setData($xlsRow,4,$lady->email);
			setData($xlsRow,5,$lady->statut);
		}
		
		if (count($gentlemen_inscrit)>0) { 
			$lady=array_shift($gentlemen_inscrit);
			setData($xlsRow,8,$lady->firstname);
			setData($xlsRow,9,$lady->lastname);
			setData($xlsRow,10,$lady->telephone);
			setData($xlsRow,11,$lady->email);
			setData($xlsRow,12,$lady->statut);
		}
		$xlsRow++;
	}

	
	// generate file (constructor parameters are optional)
	$xls = new Excel_XML('UTF-8', false, 'Liste'.date('-d-m-y', $party->date));
	$xls->addArray(getDatas());
	$xls->generateXML('SpeedDating'.date('-d-m-y', $party->date));
	exit();
?>