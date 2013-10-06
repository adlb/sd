<?
	require_once ('controller_login.php');
	require_once ('models/model_party.php');
	
	
	function parties_controller_before_action_or_view($action, $view) {
		if (!login_isAdmin()) {
			headerLocation('login');
		}
	}
	
	function parties_prepare_view(){
		Global $parties, $nextParty;
		$parties = recordset_get_all('party');
		$nextParty = party_getNextActive();
	} 
	
    function parties_prepare_everybody(){
		Global $ladies, $gentlemen;
        $ladies = array();
        $gentlemen = array();
		$guests = recordset_get_all('guest');
        foreach($guests as $guest) {
            if ($guest->sexe == 'F') {
                $ladies[] = $guest;
            } else {
                $gentlemen[] = $guest;
            }
        }
	} 
    
	function parties_action_eraseALL() {
		recordset_erase_all('party');
		recordset_erase_all('guest');
		recordset_erase_all('log');
		headerLocation('parties', array());
	}

	function parties_action_eraseALLandLOAD() {
		recordset_erase_all('party');
		recordset_erase_all('guest');
		recordset_erase_all('log');
		load_all();
		headerLocation('parties', array());
	}
	
	function load_all() {
		Global $db;
		$lines = file('models/baseInitiale.sql'); 

	    if(!$lines)  {
			echo "error;";
		    return false; 
	    } 

		$scriptfile = false; 

		/* Get rid of the comments and form one jumbo line */ 
		foreach($lines as $line)   {
		    $line = trim($line); 
			if(!(substr($line,0,3)=="---")) {
				$scriptfile.=" ".$line; 
			} 
		} 

		if(!$scriptfile) {
			return false; 
		} 

		/* Split the jumbo line into smaller lines */ 
		$queries = explode(';', $scriptfile); 
		
		foreach($queries as $query) {
			$query = trim($query); 
			if($query == "") { continue; } 
			if(!mysql_query($query.';')) {  
				return false; 
			} 
		} 
		return true;
	}
	
?>