<?
	$checked_tables = array(1);
    for($i=2;$i<=$nbTables;$i++) {
        $pos = rand(0, count($checked_tables));
        $checked_tables = array_merge(array_slice($checked_tables, 0, $pos), array($i), array_slice($checked_tables, $pos));
    }
    $checked_gentlemen = array($nbTables+1);
    for($i=$nbTables+2;$i<=$nbTables*2;$i++) {
        $pos = rand(0, count($checked_gentlemen));
        $checked_gentlemen = array_merge(array_slice($checked_gentlemen, 0, $pos), array($i), array_slice($checked_gentlemen, $pos));
    }
    //print_r($checked_gentlemen);
    //exit();
    
    
    function tete_de_page($pdf, $titre) {
        Global $party;
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(297-20,8,utf8_decode($titre),1,1,'C'); 
        $pdf->SetY($pdf->GetY()+2);
    }
    
    require_once 'helpers/pdf/phpToPDF.php';
	
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
		'LN_SIZE' => 6,
		'BG_COLOR_COL0' => array(170, 240, 230),
		'BG_COLOR' => array(170, 240, 230),
		'BRD_COLOR' => array(0,92,177),
		'BRD_SIZE' => 0.2,
		'BRD_TYPE' => '1',
		'BRD_TYPE_NEW_PAGE' => '',
		);
        
    // Définition des propriétés du reste du contenu du tableau.	
	$proprieteContenu = array(
		'T_COLOR' => array(0,0,0),
		'T_SIZE' => 8,
		'T_FONT' => 'Arial',
		'T_ALIGN_COL0' => 'C',
		'T_ALIGN' => 'C',
		'V_ALIGN' => 'M',
		'T_TYPE' => '',
		//'LN_SIZE' => $haut,
		'BG_COLOR_COL0' => array(245, 245, 150),
		'BG_COLOR' => array(255,255,255),
		'BRD_COLOR' => array(0,92,177),
		'BRD_SIZE' => 0.1,
		'BRD_TYPE' => '1',
		'BRD_TYPE_NEW_PAGE' => '',
		);
    //$proprieteContenuLorG = $proprieteContenu;
    //$proprieteContenuLorG['T_COLOR'] = array(0,0);
    
        
    $pdf = new phpTopdf('L');
    
    //1ere page
	$pdf->AddPage();
    $titre = 'Speed-Dating : '.$party->name." - ".date('d/m/y', $party->date)." - ".'Table de rotation';
    tete_de_page($pdf, $titre);
	
	$larg = (297-20-0.2*($nbRotations-1))/$nbRotations/3;
	$haut = (210-20-12-$pdf->getY()-1)/$nbTables;
    $haut = min($haut,6);
    $proprieteContenu['LN_SIZE'] = $haut;
	// Contenu du header du tableau.	
	$contenuHeader = array(
		$larg, $larg, $larg,
		utf8_decode("L"), utf8_decode("G"), utf8_decode("T")
		);

	// Contenu du tableau.	
    $x=$pdf->getX(); $y=$pdf->getY();
    for($j=0;$j<$nbRotations;$j++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*3,6,"".$j+1,1,1,'C'); 
        $contenuTableau = array();
        for($i=0;$i<$nbTables;$i++){
            $contenuTableau[]=$i+1;
            //$contenuTableau[]=((($startG+$jumpG*$j+$i)%$nbTables)+$nbTables+1);
            $contenuTableau[]=$checked_gentlemen[((($startG+$jumpG*$j+$i)%$nbTables))];
            //$contenuTableau[]=((($startT+$jumpT*$j+$i)%$nbTables)+1);
            $contenuTableau[]=$checked_tables[((($startT+$jumpT*$j+$i)%$nbTables))];
        
        }
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 3*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(3*$larg + 0.2)*($j+1));
    }
        
	//2eme page (ladies)
	$pdf->AddPage();
    $proprietesTableau['L_MARGIN'] = 0;
    $titre = 'Speed-Dating : '.$party->name." - ".date('d/m/y', $party->date)." - ".'Table de rotation - Ladies';
    tete_de_page($pdf, $titre);
	
	$larg = (297-20-0.2*($nbRotations-1))/$nbRotations/2;
	$haut = (210-20-12-$pdf->getY()-1)/$nbTables;
	$haut = min($haut,6);
	// Contenu du header du tableau.	
	$contenuHeader = array(
		$larg, $larg,
		utf8_decode("L"), utf8_decode("T")
		);

	// Contenu du tableau.	
    $x=$pdf->getX(); $y=$pdf->getY();
    for($j=0;$j<$nbRotations;$j++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*2,6,"".$j+1,1,1,'C'); 
        $contenuTableau = array();
        for($i=0;$i<$nbTables;$i++){
            $contenuTableau[]=$i+1;
            //$contenuTableau[]=((($startG+$jumpG*$j+$i)%$nbTables)+$nbTables+1);
            //$contenuTableau[]=$checked_gentlemen[((($startG+$jumpG*$j+$i)%$nbTables))];
            //$contenuTableau[]=((($startT+$jumpT*$j+$i)%$nbTables)+1);
            $contenuTableau[]=$checked_tables[((($startT+$jumpT*$j+$i)%$nbTables))];
        
        }
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 2*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(2*$larg + 0.2)*($j+1));
    }
		
    //3eme page (Gentlemen)
	$pdf->AddPage();
    $proprietesTableau['L_MARGIN'] = 0;
    $titre = 'Speed-Dating : '.$party->name." - ".date('d/m/y', $party->date)." - ".'Table de rotation - Gentlemen';
    tete_de_page($pdf, $titre);
	
	$larg = (297-20-0.2*($nbRotations-1))/$nbRotations/2;
	$haut = (210-20-12-$pdf->getY()-1)/$nbTables;
	$haut = min($haut,6);
	// Contenu du header du tableau.	
	$contenuHeader = array(
		$larg, $larg,
		utf8_decode("G"), utf8_decode("T")
		);

	// Contenu du tableau.	
    $x=$pdf->getX(); $y=$pdf->getY();
    for($j=0;$j<$nbRotations;$j++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*2,6,"".$j+1,1,1,'C'); 
        $contenuTableau = array();
        for($i=0;$i<$nbTables;$i++){
            //$contenuTableau[]=$i+1;
            //$contenuTableau[]=((($startG+$jumpG*$j+$i)%$nbTables)+$nbTables+1);
            $contenuTableau[]=$checked_gentlemen[((($startG+$jumpG*$j+$i)%$nbTables))];
            //$contenuTableau[]=((($startT+$jumpT*$j+$i)%$nbTables)+1);
            $contenuTableau[]=$checked_tables[((($startT+$jumpT*$j+$i)%$nbTables))];
        
        }
        for($i=0;$i<$nbTables-1;$i++) {
            for($k=$i+1;$k<$nbTables;$k++) {
                if ($contenuTableau[$i*2]>$contenuTableau[$k*2]) {
                    $a = $contenuTableau[$k*2];
                    $b = $contenuTableau[$k*2+1];
                    $contenuTableau[$k*2] = $contenuTableau[$i*2];
                    $contenuTableau[$k*2+1] = $contenuTableau[$i*2+1];
                    $contenuTableau[$i*2] = $a;
                    $contenuTableau[$i*2+1] = $b;
                }
            }
        }
        //for($i=0;$i<$nbTables;$i++) {
        //    $a = $contenuTableau[$i*2];
        //    $contenuTableau[$i*2]=$contenuTableau[$i*2+1];
        //    $contenuTableau[$i*2+1] = $a;
        //}
        
        
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 2*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(2*$larg + 0.2)*($j+1));
    }
	    
        
		$pdf->Output();
		exit();
	
?>