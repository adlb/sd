<?
	require_once ('controller_login.php');
	
	function party_controller_before_action_or_view($action, $view) {
		if (!login_isAdmin()) {
			headerLocation('login');
		}
	}
	
	function party_prepare_view(){
		global $party, $ladies_inscrit, $gentlemen_inscrit, $ladies_supprime, $gentlemen_supprime;
		$ladies_inscrit = $party->get_people('F', 'Validé');
		$ladies_inscrit = array_merge($ladies_inscrit, $party->get_people('F', array('Relancé', 'Non validé', 'Attente'), 'id'));
		$gentlemen_inscrit = $party->get_people('H', 'Validé');
		$gentlemen_inscrit = array_merge($gentlemen_inscrit, $party->get_people('H', array('Relancé', 'Non validé', 'Attente'), 'id'));

		$ladies_supprime = $party->get_people('F', array('Supprimé',''));
		$gentlemen_supprime = $party->get_people('H', array('Supprimé',''));
	}
	
    function party_prepare_sexeBandeau(){
        Global $sexe;
        if (isset($_GET['sexe'])) {
            $sexe = $_GET['sexe'];
        }
    }
    
	function party_prepare_addGivenNumbers(){
		global $party, $ladies_inscrit, $gentlemen_inscrit, $ladies_supprime, $gentlemen_supprime;
		$ladies_inscrit = $party->get_people('F', 'Validé', 'firstname');
		$gentlemen_inscrit = $party->get_people('H', 'Validé', 'firstname');
	}
	
	function party_action_update(){
		Global $party;
		$party->updateFromForm($_POST);
		$party->validateDatas();
		if (count($party->errors)==0) {
			$party->save();
			headerLocation('parties', array());
		} else {
			include(headerView($party, array('view' => 'edit')));
			exit();
		}
	}
	
	function party_action_delete(){
		Global $party;
		$party->delete();
		headerLocation('parties', array());
	}
	
	function party_action_create(){
		Global $party;
		$party = new party($_POST);
		$party->validateDatas();
		if (count($party->errors)==0) {
			$party->save();
			headerLocation('parties', array());
		} else {
			include(headerView($party, array('view' => 'new')));
			exit();
		}
	}
	
	function party_action_updateGivenNumbers(){
		Global $party;
		$ladies_inscrit = $party->get_people('F', 'Validé', 'firstname');
		$inscrits = array_merge($ladies_inscrit, $party->get_people('H', 'Validé', 'firstname'));
		foreach($inscrits as $guest) {
			if (isset($_POST['updatePeopleGivenNumber_'.$guest->id])) {
				$guest->givenNumber = $_POST['updatePeopleGivenNumber_'.$guest->id];
			} else {
				$guest->givenNumber = null;
			}
			$guest->save();
		}
		headerLocation($party);
	}
	
	function party_action_upload() {
		Global $party;
		if (isset($_FILES['nom_du_fichier']['name'])&&($_FILES['nom_du_fichier']['error'] == UPLOAD_ERR_OK)&&
			($_FILES['nom_du_fichier']['type'] == 'image/jpg'||$_FILES['nom_du_fichier']['type'] == 'image/jpeg'||$_FILES['nom_du_fichier']['type'] == 'image/png')
			) {    
			$chemin_destination = 'images/';
			$str = $AsciiData = preg_replace( '/[\x7f-\xff]/', '', $_FILES['nom_du_fichier']['name']);
			$str = $AsciiData = preg_replace( '/[ ]/', '', $str);
			move_uploaded_file($_FILES['nom_du_fichier']['tmp_name'], $chemin_destination.$str);    
		}
		headerLocation($party, array('view' => 'new'));
	}
?>