<?

	require_once ('controller_login.php');
	require_once ('models/model_party.php');
	
	function otherSheets_controller_before_action_or_view($action, $view) {
		if (!login_isAdmin()) {
			headerLocation('login');
		}
	}

	function otherSheets_prepare_rotation() {
		Global $rotationOk, $nbTables, $nbRotations, $jumpG, $jumpT, $startG, $startT;
		if (!isset($_GET['nbTables'])) {$nbTables='';} else {$nbTables=$_GET['nbTables'];}
		if (!isset($_GET['nbRotations'])) {$nbRotations='';} else {$nbRotations=$_GET['nbRotations'];}
		if (!isset($_GET['format'])) {$format='';} else {$format=$_GET['format'];}
		if (!isset($_GET['jumpG'])) {$jumpG='';} else {$jumpG=$_GET['jumpG'];}
		if (!isset($_GET['jumpT'])) {$jumpT='';} else {$jumpT=$_GET['jumpT'];}
		if (!isset($_GET['startG'])) {$startG='';} else {$startG=$_GET['startG'];}
		if (!isset($_GET['startT'])) {$startT='';} else {$startT=$_GET['startT'];}
		if (!isset($_GET['tableRotation'])) {$tableRotation='';} else {$tableRotation=$_GET['tableRotation'];}
		
		$tableRotation_items = explode('-', $tableRotation);
		if (count($tableRotation_items)==6) {
			$nbTables = $tableRotation_items[0];
			$nbRotations = $tableRotation_items[1];
			$jumpG = $tableRotation_items[2];
			$jumpT = $tableRotation_items[3];
			$startG = $tableRotation_items[4];
			$startT = $tableRotation_items[5];
		} else {
			if ($jumpG=='') {$jumpG==0;}
			if ($jumpT=='') {$jumpT==0;}
			$essai=0;
			while (!($jumpG>1 && $jumpT>1 && pgcd($nbTables, $jumpG) == 1 && pgcd($nbTables, $jumpT)== 1) && $essai<1000){
				$mid = (int)$nbTables/2;
				$jumpG = rand(7, $nbTables-7);
				$jumpT = rand(7, $nbTables-7);
				$startG = rand(7, $nbTables-7);
				$startT = rand(7, $nbTables-7);
				$essai++;
			}
		}
		
		if ($jumpG != '' && $jumpT != '' && $nbTables != '' && $nbRotations != '' && 
			($jumpG>1 && $jumpT>1 && pgcd($nbTables, $jumpG) == 1 && pgcd($nbTables, $jumpT)== 1)) {
			$rotationOk = true;
		} else {
			$rotationOk = false;
		}
	}
	
	function otherSheets_prepare_rotationPdf() {
		Global $rotationOk, $party;
		otherSheets_prepare_rotation();
		$party = party_getNextActive();
		if ($party == null) {
			$party = new party(array('name'=>'TABLE DE ROTATION', 'date'=>date("d/m/Y", time())));
		}
		if (!$rotationOk) {
			addMessage('Pas de génération de PDF car las paramètre de la table ne sont pas bons...');
			headerLocation('parties');
		}
	}
    function otherSheets_prepare_rotationPdf2() {
        return otherSheets_prepare_rotationPdf();
    }
	
    function otherSheets_prepare_inscriptionPdf() {
		Global $party, $id;
		$party = new party($id);
		if ($party==null) {
			headerLocation('parties');
		}
	}
	
	function otherSheets_prepare_afterPartyPdf() {
		Global $party, $id, $ladies_inscrit, $gentlemen_inscrit;
		$party = new party($id);
		if ($party==null) {
			headerLocation('parties');
		}
		// Contenu du tableau.	
		$ladies_inscrit = $party->get_people('F', 'Validé', 'givenNumber');
		$gentlemen_inscrit = $party->get_people('H', 'Validé', 'givenNumber');
		if (!isset($_GET['forcer']) || $_GET['forcer']!='yes') {
			foreach(array_merge($ladies_inscrit, $gentlemen_inscrit) as $guest) {
				if ($guest->givenNumber==0 || $guest->givenNumber==null) {
					addMessage('Les numéros ne sont pas tous renseignés... '.
					'<a href=?&obj=otherSheets&view=afterPartyPdf&id=1&forcer=yes>g&eacute;n&eacute;rer quand m&ecirc;me.</a>');
					headerLocation($party);
					exit();
				}
			}
		}
	}
	
	function otherSheets_prepare_rotationExcel() {
		Global $rotationOk, $party;
		otherSheets_prepare_rotation();
		$party = party_getNextActive();
		if ($party == null) {
			$party = new party(array('name'=>'TABLE DE ROTATION', 'date'=>date("d/m/Y", time())));
		}
		if (!$rotationOk) {
			addMessage('Pas de génération de Excel car las paramètre de la table ne sont pas bons...');
			headerLocation('parties');
		}
	}
	
	function otherSheets_prepare_partyExcel() {
		Global $id, $party, $ladies_inscrit, $gentlemen_inscrit, $ladies_supprime, $gentlemen_supprime;
		$party = new party($id);
		$ladies_inscrit = $party->get_people('F', 'Validé');
		$ladies_inscrit = array_merge($ladies_inscrit, $party->get_people('F', array('Relancé', 'Non validé', 'Attente')));
		$gentlemen_inscrit = $party->get_people('H', 'Validé');
		$gentlemen_inscrit = array_merge($gentlemen_inscrit, $party->get_people('H', array('Relancé', 'Non validé', 'Attente')));

		$ladies_supprime = $party->get_people('F', array('Supprimé',''));
		$gentlemen_supprime = $party->get_people('H', array('Supprimé',''));
	}
	
	function otherSheets_action_sauveRotation(){
		if (!isset($_POST['idParty'])) {$idParty='rien';} else {$idParty=$_POST['idParty'];}
		$party = new party($idParty);
		if ($party==null || $party->id != $idParty) {
			addMessage('Partie introuvable : pas de sauvegarde !!! ');
			headerLocation('otherSheets', array('view'=>'rotation'));
		} else {
			$party->tableRotation = isset($_POST['tableRotation']) ? $_POST['tableRotation'] : null;
			$party->save();
			addMessage('Rotation sauvegardée !!! ');
			headerLocation('otherSheets', array('view'=>'rotation', 'tableRotation'=>$party->tableRotation));
		}
	}

?>