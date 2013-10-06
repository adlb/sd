<?
	require_once ('controller_login.php');
	require_once ('models/model_party.php');
	
	function guest_controller_before_action_or_view($action, $view) {
		if (!login_isAdmin()) {
			headerLocation('login');
		}
	}
	
	function mail_prepare_view() {
		global $parties, $to, $subject, $mail;
		if (!isset($_GET['id_guest'])) {
			$id='';
			$to='';
		} else {
			$id=$_GET['id_guest'];
			$guest = new guest($id);
			$to=$guest->firstname.' <'.$guest->email.'>';
		}

		$party = party_getNextActive();
		$subject = ($party!=null) ? "Soirée speed-dating '".$party->name."' du ".date('d/m/y', $party->date)."" : "Soirée speed-dating";
		if (!isset($_POST['mail'])) {$mail='';} else {$mail=stripcslashes($_POST['mail']);}
		if (isset($_POST['subject']) && $_POST['subject']!='') {$subject=stripcslashes($_POST['subject']);}
		if (isset($_POST['to']) && $_POST['to']!='') {$to=$_POST['to'];}
		$parties = recordset_get_all('party');
	}


	function mail_action_update() {	
		global $db, $prefixe_database;
		global $parties, $to, $subject, $mail;
		
		mail_prepare_view();
		
		if (!isset($_POST['send_value'])) {$send_value='';} else {$send_value=$_POST['send_value'];}
		
        $nbAdd=0;
		if ($send_value == "Update les destinataires") {
            $sql_exclude = array();
            $exclude_dest = array();
            for($i=0;$i<$_POST['exclude_dest_size'];$i++){
                if (isset($_POST['exclude_dest_'.$i])) {
                    $exclude_dest[] = $_POST['exclude_dest_'.$i];
                }
            }
            $sql_dest_exclude = array('FALSE');
            foreach($exclude_dest as $st) {
                if ($st=='All') {
                    $sql_dest_exclude[] = "TRUE";
                } else {
                    $sql_dest_exclude[] = "partyId='".mysql_escape_string($st)."' ";
                }
            }
            $sql_exclude[] = "(".implode(" OR ", $sql_dest_exclude).")";
            
            $exclude_sexe=array();
            for($i=0;$i<$_POST['exclude_sexe_size'];$i++){
                if (isset($_POST['exclude_sexe_'.$i])) {
                    $exclude_sexe[] = $_POST['exclude_sexe_'.$i];
                }
            }
            $sql_sexe_exclude = array('FALSE');
            foreach($exclude_sexe as $st) {
                if ($st=='All') {
                    $sql_sexe_exclude[] = "TRUE";
                } elseif ($st=='H') {
                    $sql_sexe_exclude[] = "NOT sexe='F' ";
                } else {
                    $sql_sexe_exclude[] = "sexe='F' ";
                }
            }
            $sql_exclude[] = "(".implode(" OR ", $sql_sexe_exclude).")";
            
            $exclude_statut=array();
            for($i=0;$i<$_POST['exclude_statut_size'];$i++){
                if (isset($_POST['exclude_statut_'.$i])) {
                    $exclude_statut[] = $_POST['exclude_statut_'.$i];
                }
            }
            $sql_statut_exclude = array('FALSE');
            foreach($exclude_statut as $st) {
                if ($st=='All') {
                    $sql_statut_exclude[] = "TRUE";
                } else {
                    $sql_statut_exclude[] = "statut='".mysql_escape_string($st)."' ";
                }
            }
            $sql_exclude[] = "(".implode(" OR ", $sql_statut_exclude).")";
            
            $sql_exclude[] = "(NOT email='aaa@bbb.com')";
            
            $sql = implode(" AND ", $sql_exclude);
            $guest = new guest;
            $sql = "SELECT DISTINCT email FROM ".$prefixe_database.$guest->tableName." WHERE ".$sql;
            $req = mysql_query($sql, $db);
            $result_exclude = array();
            if ($req && mysql_num_rows($req) > 0) {
                while($item = mysql_fetch_assoc($req)){
                    $result_exclude[] = $item['email']; //$item['firstname'].' <'.$item['email'].'>';
                }
			}
			//echo "<pre>";
            //print_r($result_exclude);
            
            $sql_include = array();
            $select_dest=array();
            for($i=0;$i<$_POST['select_dest_size'];$i++){
                if (isset($_POST['select_dest_'.$i])) {
                    $select_dest[] = $_POST['select_dest_'.$i];
                }
            }
            $sql_dest_include = array('FALSE');
            foreach($select_dest as $st) {
                if ($st=='All') {
                    $sql_dest_include[] = "TRUE";
                } else {
                    $sql_dest_include[] = "partyId='".mysql_escape_string($st)."' ";
                }
            }
            $sql_include[] = "(".implode(" OR ", $sql_dest_include).")";
            
            $select_sexe=array();
            for($i=0;$i<$_POST['select_sexe_size'];$i++){
                if (isset($_POST['select_sexe_'.$i])) {
                    $select_sexe[] = $_POST['select_sexe_'.$i];
                }
            }
            $sql_sexe_include = array('FALSE');
            foreach($select_sexe as $st) {
                if ($st=='All') {
                    $sql_sexe_include[] = "TRUE";
                } elseif ($st=='H') {
                    $sql_sexe_include[] = "NOT sexe='F' ";
                } else {
                    $sql_sexe_include[] = "sexe='F' ";
                }
            }
            $sql_include[] = "(".implode(" OR ", $sql_sexe_include).")";
            
            $select_statut=array();
            for($i=0;$i<$_POST['select_statut_size'];$i++){
                if (isset($_POST['select_statut_'.$i])) {
                    $select_statut[] = $_POST['select_statut_'.$i];
                }
            }
            $sql_statut_include = array('FALSE');
            foreach($select_statut as $st) {
                if ($st=='All') {
                    $sql_statut_include[] = "TRUE";
                } else {
                    $sql_statut_include[] = "statut='".mysql_escape_string($st)."' ";
                }
            }
            $sql_include[] = "(".implode(" OR ", $sql_statut_include).")";
            
            
            $sql_include[] = "(NOT email='aaa@bbb.com')";
            $sql = implode(" AND ", $sql_include);
            $guest = new guest;
            $sql = "SELECT DISTINCT email FROM ".$prefixe_database.$guest->tableName." WHERE ".$sql;
            $req = mysql_query($sql, $db);
            $nbAdd = 0;
            if ($req && mysql_num_rows($req) > 0) {
                $result = array();
                while($item = mysql_fetch_assoc($req)){
                    if (!(in_array($item['email'], $result_exclude))) {
                        $result[] = $item['email']; //$item['firstname'].' <'.$item['email'].'>';
                        $nbAdd++;
                    }
                }
                $to = implode(', ', $result);
			}
		}
		
		if ($send_value == "Update texte") {
			if (!isset($_POST['textSelect'])) {$textSelect='';} else {$textSelect=$_POST['textSelect'];}
			Global $$textSelect;
			if ($textSelect!='rien' && isset($$textSelect)) {
				$mail=$$textSelect;
			}
		}
		
		if ($send_value == "Envoyer le(s) mail(s)") {
			if (send_email_mutiple($to, $subject, $mail)) {
				addMessage("Le mail est envoyé...");
				headerLocation('parties');
			}
			addMessage("Erreur pendant l'envoi du mail...");
		}
		include(headerView('mail'));
		exit();
	}

?>