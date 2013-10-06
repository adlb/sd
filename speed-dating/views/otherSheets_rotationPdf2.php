<?
    function get_shaked_list($min, $max) {
        $shaked_list = array($min);
        for($i=$min+1;$i<=$max;$i++) {
            $pos = rand(0, count($shaked_list));
            $shaked_list = array_merge(array_slice($shaked_list, 0, $pos), array($i), array_slice($shaked_list, $pos));
        }
        return $shaked_list;
    }
    
    $shaked_tables = get_shaked_list(1, $nbTables);
    $shaked_gentlemen = get_shaked_list($nbTables+1, $nbTables*2);
    $shaked_ladies = get_shaked_list(1, $nbTables);
    
    //$shaked_decalageLadies = get_shaked_list(0, $nbTables-1);
    //$shaked_decalageGentlemen = get_shaked_list(0, $nbTables-1);
    $shaked_decalageTables = get_shaked_list(0, $nbTables-1);
    /**echo '<pre>';
    echo 'shaked_tables';
    print_r($shaked_tables);
    echo 'shaked_gentlemen';
    print_r($shaked_gentlemen);
    echo 'shaked_ladies';
    print_r($shaked_ladies);
    echo 'shaked_decalageLadies';
    print_r($shaked_decalageLadies);
    echo 'shaked_decalageGentlemen';
    print_r($shaked_decalageGentlemen);
    */
    
    class interview {
        var $lady;
        var $gentleman;
        var $table;
        
        function interview($lady, $gentleman, $table) {
            $this->lady = $lady;
            $this->gentleman = $gentleman;
            $this->table = $table;
        }
    }
    
    class rotation {
        var $interviews;
        
        function rotation($idRotation) {
            Global $nbTables,$shaked_tables,$shaked_gentlemen,$shaked_ladies,
            $shaked_decalageTables; //,$shaked_decalageGentlemen;
            $this->interviews = array();
            for($i=0; $i<$nbTables; $i++) {
                $lady = $shaked_ladies[($i)%$nbTables];
                $gentleman = $shaked_gentlemen[($i+$idRotation)%$nbTables];
                $table = $shaked_tables[($i+$idRotation*2)%$nbTables];
                $this->interviews[] = new interview($lady, $gentleman, $table);
            }
            $this->orderByTables();
        }
        
        function orderByLadies() {
            $this->orderBy('lady');
        }
        function orderByGentlemen() {
            $this->orderBy('gentleman');
        }
        function orderByTables() {
            $this->orderBy('table');
        }
        
        function orderBy($var) {
            for($i=0;$i<count($interviews)-1;$i++) {
                for($j=$i+1;$j<count($interviews);$j++) {
                    if ($this->interviews[$i]->$var > $this->interviews[$j]->$var) {
                        $a = $this->interviews[$i];
                        $this->interviews[$i] = $this->interviews[$j];
                        $this->interviews[$j] = $a;
                    }
                }
            }
        }
        
        function gett($lady, $gentleman, $table) {
            $nbNull = (($lady === null) ? 1 : 0) + (($gentleman === null) ? 1 : 0) + (($table === null) ? 1 : 0);
            if ($nbNull != 2) {
                die("error ; lady=$lady - gentleman=$gentleman - $table=$table");
            }
            foreach($this->interviews as $interview) {
                if ($lady==$interview->lady) {
                    return $interview;
                }
                if ($gentleman==$interview->gentleman) {
                    return $interview;
                }
                if ($table==$interview->table) {
                    return $interview;
                }
            }
            echo '<pre>';
            print_r($this->interviews);
            die("ERROR ; lady=$lady - gentleman=$gentleman - $table=$table");
        }
    }
    
    class rotation_table {
        var $rotations;
        
        function rotation_table($nbRotations) {
            $this->rotations = array();
            for($i=0;$i<$nbRotations;$i++) {
                $this->rotations[] = new rotation($i);
            }
        }
        
        function get($rotation, $lady, $gentleman, $table) {
            return $this->rotations[$rotation]->gett($lady, $gentleman, $table);
        }
    }
    
    $my_rotation_table = new rotation_table($nbRotations);
    
    //echo '<pre>';
    //print_r($my_rotation_table);
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
    $titre = ''.$party->name." - ".date('d/m/y', $party->date)." - ".'Table de rotation - Sorted by Ladies';
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
    for($rotation=0;$rotation<$nbRotations;$rotation++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*3,6,"".$rotation+1,1,1,'C'); 
        $contenuTableau = array();
        for($lady=1;$lady<=$nbTables;$lady++){
            $contenuTableau[]=$lady;
            //echo 'xx'.$rotation.'xx';
            $contenuTableau[]=$my_rotation_table->get($rotation, $lady, null, null)->gentleman;
            //echo 'xx'.$rotation.'xx';
            $contenuTableau[]=$my_rotation_table->get($rotation, $lady, null, null)->table;
        }
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 3*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(3*$larg + 0.2)*($rotation+1));
    }
    
    //2eme page bis
	$pdf->AddPage();
    $proprietesTableau['L_MARGIN'] = 0;
    $titre = ''.$party->name." - ".date('d/m/y', $party->date)." - ".'Table de rotation - Sorted by Gentlemen';
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
    for($rotation=0;$rotation<$nbRotations;$rotation++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*3,6,"".$rotation+1,1,1,'C'); 
        $contenuTableau = array();
        for($gentleman=$nbTables+1;$gentleman<=$nbTables*2;$gentleman++){
            $contenuTableau[]=$my_rotation_table->get($rotation, null, $gentleman, null)->lady;
            //echo 'xx'.$rotation.'xx';
            $contenuTableau[]=$gentleman;
            //echo 'xx'.$rotation.'xx';
            $contenuTableau[]=$my_rotation_table->get($rotation, null, $gentleman, null)->table;
        }
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 3*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(3*$larg + 0.2)*($rotation+1));
    }
    
	/*
    //3eme page (ladies)
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
    for($rotation=0;$rotation<$nbRotations;$rotation++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*2,6,"".$rotation+1,1,1,'C'); 
        $contenuTableau = array();
        for($lady=1;$lady<=$nbTables;$lady++){
            $contenuTableau[]=$lady;
            $contenuTableau[]=$my_rotation_table->get($rotation, $lady, null, null)->table;
        }
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 2*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(2*$larg + 0.2)*($rotation+1));
    }
		
    //4eme page (Gentlemen)
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
    for($rotation=0;$rotation<$nbRotations;$rotation++) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($larg*2,6,"".$rotation+1,1,1,'C'); 
        $contenuTableau = array();
        for($gentleman=$nbTables+1;$gentleman<=$nbTables*2;$gentleman++){
            $contenuTableau[]=$gentleman;
            $contenuTableau[]=$my_rotation_table->get($rotation, null, $gentleman, null)->table;
        }
        $pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
        $proprietesTableau['L_MARGIN'] = $proprietesTableau['L_MARGIN'] + 2*$larg + 0.2;
        $pdf->setY($y);
        $pdf->setX($x+(2*$larg + 0.2)*($rotation+1));
    }
	*/    
        
    $pdf->Output();
    exit();
	
?>