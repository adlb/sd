<?

require_once('model.php');
require_once('model_party.php');
require_once('model_log.php');
require_once('helpers/helper_mail.php');

//statut = {'Supprimé', 'Validé', 'Non validé', 'Attente', 'Relancé'}

class guest extends recordset {
	var $tableName = 'sd_guests';
	var $fields	   = array( 'firstname'=>'TEXT', 'lastname'=>'TEXT', 'telephone'=>'TEXT', 'email'=>'TEXT', 'sexe'=>'TEXT',
									'givenNumber'=>'INT', 'creationDate'=>'DATE', 'updateDate'=>'DATE', 
									'statut'=>'TEXT', 'partyId'=>'INT', 'birthDate'=>'INT', 'pass'=>'TEXT');
	
	var $fields_in_form = array( 'firstname'=>'TEXT', 'lastname'=>'TEXT', 'telephone'=>'TEXT', 'email'=>'TEXT', 'sexe'=>'TEXT', 'birthDate'=>'INT', 'givenNumber'=>'INT');

	var $id, $firstname, $lastname, $telephone, $email, $sexe, $birthDate, $creationDate, $updateDate, $statut, $pass, $partyId,  $givenNumber;
	
	function get_party(){
		return new party($this->partyId);
	}
	
	function afterUpdate() {
		if ($this->statut == '') {
			$this->statut = 'Non validé';
		}
		$this->creationDate = ($this->creationDate==null) ? time() : $this->creationDate;
		$this->updateDate = time();
	}
	
	function afterCreate() {
		$this->pass = substr(md5(microtime()),0,4);
		$this->creationDate = time();
		$this->updateDate = time();
		$this->statut = "Non validé";
	}
	
	function validateDatas(){
		Global $db, $prefixe_database;
		$this->errors = array();
		
		//mail
		$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
		if (!preg_match($Syntaxe,$this->email)) {
			$this->errors[] = "Le mail n'est pas valide...";
		}
        
        //BirthDate
		if (!(($this->birthDate>1940) &&  ($this->birthDate<2012)) && !($this->birthDate==-1)) {
			$this->errors[] = "L'année de naissance n'est pas valide... (ex: 1977)";
		}
		
		
		//Nom et Prénom
		$this->firstname=toLowerWithFirstUpper($this->firstname);
		$this->lastname=toLowerWithFirstUpper($this->lastname);
		$this->email=strtolower($this->email);
		if (strlen($this->firstname)<3) {
			$this->errors[] = "Le prénom n'est pas correct...";
		}
		if (strlen($this->lastname)<3) {
			$this->errors[] = "Le nom n'est pas correct...";
		}
		
		//party_id
		if ($this->partyId == null || $this->partyId == '') {
			$this->errors[] = "L'invité n'est pas rattaché à une soirée !...";
		}
		
		//Téléphone
		$tel = '';
		$autorized = array('+','0','1','2','3','4','5','6','7','8','9');
		for($i=0; $i < strlen($this->telephone); $i++) {
			if (in_array(substr($this->telephone,$i,1), $autorized)) {
				$tel .=substr($this->telephone,$i,1);
			}
		}
		if (substr($tel,0,3) == '+33'){
			$tel='0'.substr($tel,3);
		}
		$Syntaxe='#^\+[0-9]{8}[0-9]+$|^0[0-9]{9}$#'; 
		if (!preg_match($Syntaxe,$tel)) {
			$this->errors[] = "Le telephone n'est pas valide...";
		} else {
			if (substr($tel,0,1)!='+'){
				$tel=substr($tel,0,2).".".substr($tel,2,2).".".substr($tel,4,2).".".substr($tel,6,2).".".substr($tel,8,2);
				$this->telephone = $tel;
			}
		}
		
		//vérifie que le sexe est bien F ou H
		if ($this->sexe !='F' && $this->sexe !='H') {
			$this->errors[] = "Le sexe n'est pas correct... 'H' ou 'F' uniquement !";
		}
        if (!isset($_SESSION['prenom_sexe_error'])) {
            $_SESSION['prenom_sexe_error'] = array();
        }
        //print_r($_SESSION['prenom_sexe_error']);
        if (!in_array($this->firstname, $_SESSION['prenom_sexe_error'])) {
            $guest = new guest;
            if ($this->sexe == 'F') {
                $sexe_condition = ' AND NOT sexe = \'F\'';
                $sexe_mis = 'une fille';
            } else {
                $sexe_condition = ' AND sexe = \'F\'';
                $sexe_mis = 'un homme';
            }
            $sql = 'SELECT id FROM '.$prefixe_database.$guest->tableName.' WHERE firstname = \''.$this->firstname.'\''.$sexe_condition.' AND NOT id = \''.$this->id.'\' LIMIT 1';
            $req = mysql_query($sql, $db);
            if ($req && mysql_num_rows($req) > 0) {
                $_SESSION['prenom_sexe_error'][] = $this->firstname;
                $this->errors[] = "Es-tu sûr d'être ".$sexe_mis." ? si oui, valide à nouveau le formulaire.";
            }
        }
		
		
		
		//unicité de l'email
		//if ($this->id != null and $this->id!='') {
		//	$close = " AND NOT id = ".$this->id." ";
		//} else {
		//	$close = '';
		//}
		$guest = new guest;
		//$sql = 'DELETE FROM '.$prefixe_database.$guest->tableName.' WHERE partyId='.$this->partyId.' AND email=\''.$this->email.'\' AND statut=\'Supprimé\' LIMIT 1';
		//mysql_query($sql, $db);
		$sql = 'SELECT id FROM '.$prefixe_database.$guest->tableName.' WHERE partyId='.$this->partyId.' AND email=\''.$this->email.'\' AND NOT id = \''.$this->id.'\' AND NOT statut=\'Supprimé\' LIMIT 1';
		$req = mysql_query($sql, $db);
		if ($req && mysql_num_rows($req) > 0) {
			$rec = mysql_fetch_assoc($req);
			$gg = new guest($rec['id']);
			if ($gg->statut == 'Non validé') {
				$this->errors[] = "Vous êtes déjà inscrit, veuillez utiliser le lien dans le mail pour valider votre inscription...";
			} elseif ($gg->statut == 'Attente') {
				$this->errors[] = "Vous êtes déjà inscrit, vous êtes en liste d'attente...";
			} elseif ($gg->statut == 'Validé') {
				$this->errors[] = "Vous êtes déjà inscrit...";
			}
		}
		return (count($this->errors)==0);
	}
	
    function get_nb_participations() {
        Global $db, $prefixe_database;
        if ($this->email == 'aaa@bbb.com') return 0;
        $sql = 'SELECT count(partyId) as nb FROM '.$prefixe_database.$this->tableName.' WHERE statut=\'Validé\' AND email=\''.$this->email.'\' LIMIT 1';
        $req = mysql_query($sql, $db);
        $data = mysql_fetch_assoc($req);
		return $data['nb'];
    }
    
    function emailDejaValide(){
        Global $db, $prefixe_database;
        $sql = 'SELECT id FROM '.$prefixe_database.$this->tableName.' WHERE partyId<>'.$this->partyId.' AND partyId>10 AND email=\''.$this->email.'\' AND statut<>\'Non validé\' LIMIT 1';
		//echo $sql;
        //exit();
        $req = mysql_query($sql, $db);
		if ($req && mysql_num_rows($req) > 0) {
			return true;
		}
        return false;
    }
    
	function changeStatutToValideOrAttente(){
		$party = new party($this->partyId);
		$i=0;
		if ($this->statut=='Validé' or $this->statut=='Relancé') {
			$i=1;
		}
		if (($this->sexe=='F' && $party->get_nbladies()-$i>=$party->maxPeople) or 
			($this->sexe!='F' && $party->get_nbgentlemen()-$i>=$party->maxPeople)) {
			$this->statut='Attente';
		} else {
			$this->statut='Validé';
		}
		$this->save();
	}
	
	function sendMailEnregistrementAValider(){
			return send_email($this, $this, $this->get_party(), 'email_suite_enregistrement_guestNonValide');
	}
	
	function sendMailEnregistrementAValiderRelance(){
			return send_email($this, $this, $this->get_party(), 'email_suite_relance_admin_pour_valider');
	}
	
	function sendMailFinalementTuViens(){
			return send_email($this, $this, $this->get_party(), 'email_suite_liberation_de_place');
	}

	function sendMailAnnuleRelance(){
			return send_email($this, $this, $this->get_party(), 'email_suite_annule_relance_by_admin');
	}
	
	function sendMailValidation(){
			if ($this->statut=='Validé') {
				return send_email($this, $this, $this->get_party(), 'email_suite_validation_par_utilisateur');
			} elseif ($this->statut=='Attente'){
            	return send_email($this, $this, $this->get_party(), 'email_suite_validation_par_utilisateur_Attente');
			} else {
				return send_email($this, $this, $this->get_party(), 'email_suite_enregistrement_guestNonValide');
			}
	}
	
	function sendMailAnnulation(){
			return send_email($this, $this, $this->get_party(), 'email_suite_annulation_par_utilisateur');
	}
    
    function removemyemailfromdatabase(){
        Global $db, $prefixe_database;
        $sql = "UPDATE ".$prefixe_database.$this->tableName." SET email='".'aaa@bbb.com'."' WHERE email='".$this->email."' ";
        $req = mysql_query($sql, $db);
        return;
    }
	
}

function guest_sendInformationEmail($email, $party){
	global $uri;
	$guest=$party->get_guestByEmail($email);
	
	if ($guest==null) {
		return send_email($email, null, $party, 'email_suite_demande_renvoi_pastrouve');
	}
	if ($guest->statut=='Supprimé') {
		return send_email($guest, $guest, $party, 'email_suite_demande_renvoi_guestSupprime');
	} elseif ($guest->statut=='Validé') {
		return send_email($guest, $guest, $party, 'email_suite_demande_renvoi_guestValide');
	} elseif ($guest->statut=='Attente') {
		return send_email($guest, $guest, $party, 'email_suite_demande_renvoi_guestAttente');
	} elseif ($guest->statut=='Non validé') {
		return send_email($guest, $guest, $party, 'email_suite_demande_renvoi_guestNonValide');
	} elseif ($guest->statut=='Relancé') {
		return send_email($guest, $guest, $party, 'email_suite_demande_renvoi_guestRelance');
	}
	return false;
}


	
?>