<?

require_once('model.php');
require_once('model_guest.php');
require_once('model_log.php');			
						
class party extends recordset {
	var $tableName = 'sd_parties';
	var $fields	   = array(	'name'=>'TEXT', 'date'=>'DATE', 
									'dateOpen'=>'DATE', 'maxPeople'=>'INT', 
									'active'=>'BOOLEAN', 'inscriptionOpen'=>'BOOLEAN', 'image'=>'TEXT', 'magicWord'=>'TEXT', 'tableRotation'=>'TEXT',
                                    'typedesoiree'=>'TEXT', 
                                    'heure'=>'TEXT', 'adresse1'=>'TEXT', 'adresse2'=>'TEXT', 'contactInfo'=>'TEXT', 'descriptionFormule'=>'TEXT');
	var $id, $name, $date, $dateOpen, $maxPeople, $active, $magicWord, $image, $tableRotation;
	
	function get_nbladies(){
		return $this->get_nb_people('F', array('Validé', 'Relancé', 'Attente'));
	}
	
	function get_nbladies_strict(){
		return $this->get_nb_people('F', 'Validé');
	}
	
	function get_nbgentlemen(){
		return $this->get_nb_people('H', array('Validé', 'Relancé', 'Attente'));
	}
	
	function get_nbgentlemen_strict(){
		return $this->get_nb_people('H', 'Validé');
	}
	
	function get_ladies_en_attente(){
		return $this->get_people('F', 'Attente');
	}
	
	function get_gentlemen_en_attente(){
		return $this->get_people('H', 'Attente');
	}
	
    function get_ladies(){
        return $this->get_people('F', null, 'id');
    }
    function get_gentlemen(){
        return $this->get_people('H', null, 'id');
    }
    
	function get_people($sexe, $statut, $order=null) {
		Global $db, $prefixe_database;
		$guest = new guest();
		$sql = "SELECT id FROM ".$prefixe_database.$guest->tableName." WHERE partyId=".$this->id." ";
		$sql .= "AND ".(($sexe == 'F')?'':'NOT ').'sexe=\'F\' ';
		if ($statut !=null && gettype($statut)=='array') {
			$nstatut=array();
			foreach($statut as $state) {
				$nstatut[] = 'statut=\''.$state.'\'';
			}
			$sql .= 'AND ('.implode(' OR ',$nstatut).') ';
		} elseif ($statut !=null) {
			$sql .= "AND ".'statut=\''.$statut.'\'';
		}
		if ($order!=null) {
			$sql .= " ORDER BY ".$order;
		}
		$req = mysql_query($sql, $db);
		if (!$req || mysql_num_rows($req)==0) {return array();}
		$result = array();
		while ($res = mysql_fetch_assoc($req)) {
			$result[] = new guest($res['id']);
		}
		return $result;
	}
	
	function get_nb_people($sexe, $statut=null) {
		Global $db, $prefixe_database;
		$guest = new guest();
		$sql = "SELECT id FROM ".$prefixe_database.$guest->tableName." WHERE partyId=".$this->id." ";
		$sql .= "AND ".(($sexe == 'F')?'':'NOT ').'sexe=\'F\' ';
		if ($statut !=null && gettype($statut)=='array') {
			$nstatut=array();
			foreach($statut as $state) {
				$nstatut[] = 'statut=\''.$state.'\'';
			}
			$sql .= 'AND ('.implode(' OR ',$nstatut).') ';
		} elseif ($statut !=null) {
			$sql .= "AND ".'statut=\''.$statut.'\'';
		}
		$req = mysql_query($sql, $db);
		if (!$req) {return 0;}
		return  mysql_num_rows($req);
	}
	
	function get_guestByEmail($email) {
		Global $db,$prefixe_database;
		$guest = new guest;
		$tablename = $prefixe_database.$guest->tableName;
		$sql = "SELECT id FROM $tablename WHERE email = '".mysql_escape_string($email)."' AND partyId = ".$this->id." AND NOT statut = 'Supprimé' LIMIT 1";
		$req = mysql_query($sql, $db);
		if (!$req) {
			return null;
		}
		if (mysql_num_rows($req) == 0) {
			$sql = "SELECT id FROM $tablename WHERE email = '".mysql_escape_string($email)."' AND partyId = ".$this->id." LIMIT 1";
			$req = mysql_query($sql, $db);
			if (mysql_num_rows($req) == 0) {
				return null;
			}
		}
		$item = mysql_fetch_assoc($req);
		return new guest($item['id']);
	}
	
	function validateDatas(){
		$this->errors = array();
		if (strlen($this->name)<5) {
			$this->errors[] = "Le nom n'est pas correct...";
		}
		return (count($this->errors)==0);
	}
	
	function traiterLesPlacesDispo(){
		$places_for_ladies = $this->maxPeople - $this->get_nbladies;
		if ($places_for_ladies>0) {
			$ladies_en_attente = $this->get_ladies_en_attente();
			while(count($ladies_en_attente)>0 && $places_for_ladies>0) {
				$guest=array_shift($ladies_en_attente);
				$guest->relancerPlaceDispo();
				$places_for_ladies--;
			}
		}
		$places_for_gentlemen = $this->maxPeople - $this->get_nbgentlemen;
		if ($places_for_gentlemen>0) {
			$gentlemen_en_attente = $this->get_gentlemen_en_attente();
			while(count($gentlemen_en_attente)>0 && $places_for_gentlemen>0) {
				$guest=array_shift($gentlemen_en_attente);
				$guest->relancerPlaceDispo();
				$places_for_gentlemen--;
			}
		}
	}
}

function party_getNextActive(){
	Global $db, $prefixe_database;
	$party = new party;
	$sql = "SELECT * FROM ".$prefixe_database.$party->tableName." WHERE active=1 AND date>=TO_DAYS(NOW()) ORDER BY date LIMIT 1";
	$req = mysql_query($sql,$db);
	if (!$req) {
		return null;
	}
	if (mysql_num_rows($req) == 0) {
		return null;
	}
	$res = mysql_fetch_assoc($req);
	return new party($res['id']);
}
?>