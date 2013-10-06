<?
	require_once 'helpers/pdf/phpToPDF.php';
	
$pdf = new phpTopdf('L');
$pdf->AddPage();

//Sélection de la police
$pdf->SetFont('Arial','B',10);

//Texte centré dans une cellule 20*10 mm encadrée et retour à la ligne
$pdf->Cell(297-20,8,utf8_decode('Speed-Dating : '.$party->name." - ".date('d/m/y', $party->date)." - ".'Les présents'),1,1,'C'); 
	
$pdf->SetY($pdf->GetY()+2);

$x = $pdf->GetX(); $y = $pdf->GetY();

// Définition des propriétés du tableau.
$proprietesTableau = array(
	'TB_ALIGN' => 'L',
	'L_MARGIN' => 0,
	'BRD_COLOR' => array(0,92,177),
	'BRD_SIZE' => '0.3',
	);

// Définition des propriétés du header du tableau.	
$proprieteHeader = array(
	'T_COLOR' => array(150,10,10),
	'T_SIZE' => 8,
	'T_FONT' => 'Arial',
	'T_ALIGN' => 'C',
	'V_ALIGN' => 'T',
	'T_TYPE' => 'B',
	'LN_SIZE' => 7,
	'BG_COLOR_COL0' => array(170, 240, 230),
	'BG_COLOR' => array(170, 240, 230),
	'BRD_COLOR' => array(0,92,177),
	'BRD_SIZE' => 0.2,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
	);

// Contenu du header du tableau.	
$contenuHeader = array(
	6, 25, 28, 23, 56, //8.5, 35, 30, 30, 35,
	utf8_decode("N°"), utf8_decode("Prénom"), utf8_decode("Nom"), utf8_decode("Téléphone"), utf8_decode("e-Mail")//,
	//utf8_decode("N°"), utf8_decode("Prénom"), utf8_decode("Nom"), utf8_decode("Téléphone"), utf8_decode("e-Mail")
	);

// Définition des propriétés du reste du contenu du tableau.	
$proprieteContenu = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 8,
	'T_FONT' => 'Arial',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'R',
	'V_ALIGN' => 'M',
	'T_TYPE' => '',
	'LN_SIZE' => (5.6*28)/($party->maxPeople),
	'BG_COLOR_COL0' => array(245, 245, 150),
	'BG_COLOR' => array(255,255,255),
	'BRD_COLOR' => array(0,92,177),
	'BRD_SIZE' => 0.1,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
	);	


$contenuTableau = array();

for($i=0;$i<count($ladies_inscrit);$i++){
	$contenuTableau[] = ($ladies_inscrit[$i]->givenNumber!=0 ? utf8_decode($ladies_inscrit[$i]->givenNumber) : "");
	$contenuTableau[] = utf8_decode($ladies_inscrit[$i]->firstname);
	$contenuTableau[] = utf8_decode($ladies_inscrit[$i]->lastname);
	$contenuTableau[] = utf8_decode($ladies_inscrit[$i]->telephone);
	$contenuTableau[] = utf8_decode($ladies_inscrit[$i]->email);
}

for($i;$i<$party->maxPeople;$i++){
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
}
$proprieteContenu['BG_COLOR'] = array(247,193,200);

$pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
$pdf->setY($y);
$proprietesTableau['L_MARGIN'] = 139;

$contenuTableau = array();

for($i=0;$i<count($gentlemen_inscrit);$i++){
	$contenuTableau[] = ($gentlemen_inscrit[$i]->givenNumber!=0 ? utf8_decode($gentlemen_inscrit[$i]->givenNumber) : "");
	$contenuTableau[] = utf8_decode($gentlemen_inscrit[$i]->firstname);
	$contenuTableau[] = utf8_decode($gentlemen_inscrit[$i]->lastname);
	$contenuTableau[] = utf8_decode($gentlemen_inscrit[$i]->telephone);
	$contenuTableau[] = utf8_decode($gentlemen_inscrit[$i]->email);
}
for($i;$i<$party->maxPeople;$i++){
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
	$contenuTableau[] = ""; 
}

$proprieteContenu['BG_COLOR'] = array(137,213,211);
		
$pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);

$pdf->Output();




?>