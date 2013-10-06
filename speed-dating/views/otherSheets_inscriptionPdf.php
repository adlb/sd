<?
	require_once 'helpers/pdf/phpToPDF.php';
	
$pdf = new phpTopdf();
$pdf->AddPage();

//Sélection de la police
$pdf->SetFont('Arial','B',8);

//Texte centré dans une cellule 20*10 mm encadrée et retour à la ligne
$pdf->MultiCell(190,8,utf8_decode('Speed-Dating : '.$party->name." - ".date('d/m/y', $party->date)."\n".'Les Femmes'),1,1,'C'); 

$pdf->SetY($pdf->GetY()+10);

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
	10, 40, 40, 40, 60,
	utf8_decode("N°"), utf8_decode("Prénom"), utf8_decode("Nom"), utf8_decode("Téléphone"), utf8_decode("e-Mail")
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
	'LN_SIZE' => 7,
	'BG_COLOR_COL0' => array(245, 245, 150),
	'BG_COLOR' => array(255,255,255),
	'BRD_COLOR' => array(0,92,177),
	'BRD_SIZE' => 0.1,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
	);	

// Contenu du tableau.	
$ladies_inscrit = $party->get_people('F', 'Validé', 'firstname');
$gentlemen_inscrit = $party->get_people('H', 'Validé', 'firstname');

$contenuTableau = array();

for($i=0;$i<count($ladies_inscrit);$i++){
	$contenuTableau[] = ""; 
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

$pdf->AddPage();
$pdf->SetFont('Arial','B',8);

$pdf->MultiCell(190,8,utf8_decode('Speed-Dating : '.$party->name." - ".date('d/m/y', $party->date)."\n".'Les Hommes'),1,1,'C'); 
$pdf->SetY($pdf->GetY()+10);
$contenuTableau = array();

for($i=0;$i<count($gentlemen_inscrit);$i++){
	$contenuTableau[] = ""; 
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