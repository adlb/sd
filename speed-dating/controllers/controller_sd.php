<?
	require_once('models/model_party.php');
	
	
	function sd_controller_before_action_or_view($action, $view) {
	
	}

	function sd_prepare_view() {
		global $nextParty, $guest;
		$nextParty = party_getNextActive();
		$guest = new guest;
	}
	
	function sd_prepare_guest() {
		global $party, $guest, $id, $shouldbevalidated;
		
		$guest = new guest($id);
		$pass_is_given = 		(isset($_GET['pass']) 		&& $_GET['pass']       ==$guest->pass);
		$guestid_in_session = 	(isset($_SESSION['idGuest'])&& $_SESSION['idGuest']==$guest->id);
		
		if (!$pass_is_given && !$guestid_in_session) {
			addMessage('Veuillez utiliser le lien dans le mail reçu pour valider ou modifier votre inscription !');
			headerLocation('sd');
		}
		
		$party = $guest->get_party();

		if ($guest==null || $party == null ) {
			headerLocation('sd');
		}
	}
	
	function sd_action_eqssend(){
		$champs_eqs = array('sexe','annee_de_naissance','nb_venu_au_speeddating','informe_par','bonne_soiree','points_forts','points_faibles','nb_rencontre','duree_rencontre','nb_concatees','nb_contactant','type_relation','commentaire_relations','encore_en_couple','faire_evoluer_speeddating','email');
		$st = "REPONSES au questionnaire Speed dating le ".date("d-m-Y H:i")."<br/><br/><table border=1>";
		foreach($_POST as $key=>$value) {
			$st .= '<tr><td>'.htmlentities($key).'</td>'."\r\n".'<td>'.nl2br(htmlentities(stripslashes($value),ENT_COMPAT,'UTF-8')).'&nbsp;</td>'."\r\n".'</tr>';
		}
		$st .= "</TABLE>";
		send_eqsMail($st);
		
		if (!file_exists('eqs.csv')) {
			if ($f = fopen('eqs.csv', 'a')) {
				$titles = '';
				foreach($champs_eqs as $key) {
					$titles .= '"'.$key.'"'.';';
				}
				fwrite($f, $titles."\r\n");
				fclose($f);
			}
		}  
		$st=''; 
		if ($f = fopen('eqs.csv', 'a')) {
			foreach($champs_eqs as $key) {
				if (isset($_POST[$key])) { 
					$ist = stripcslashes($_POST[$key]);
					$ist = str_replace('"', '""', $ist);
					$ist = str_replace("\r\n", "\n", $ist);
					$st .= '"'.$ist.'"'.';';
				} else {
					$st .= '""'.';';
				}
			}
			fwrite($f, $st."\r\n");
			fclose($f);
		}
		addMessage("Merci d'avoir répondu à ce questionnaire...");
		headerLocation('sd');
	}
	
	function sd_action_loglog() {
		$magicWord = (isset($_POST['magicWord'])?$_POST['magicWord']:'');
		$_SESSION['magicWord'] = $magicWord;
		headerLocation('sd');
	}

	function sd_isMagic($party){
		return $party->magicWord=='' || (isset($_SESSION['magicWord']) && strcasecmp($_SESSION['magicWord'],$party->magicWord)==0);
	}
	
	function sd_action_create() {
		global $id, $nextParty, $guest, $modegeneral, $mailFromSoiree;
		$nextParty = party_getNextActive();
		if ($nextParty == null || $nextParty->id != $id || !sd_isMagic($nextParty)) {
			headerLocation('sd');
		}
		
		if ($nextParty->dateOpen>getdate()) {
			addMessage("Les inscriptions ne sont pas ouvertes...");
			headerLocation('sd');
		}
		$guest = new guest($_POST, $guest->fields_in_form);
		$guest->partyId = $nextParty->id;
		if ($guest->validateDatas()) {
			$guest->save();
			$_SESSION['idGuest']=$guest->id;
			if ((strpos($guest->email, "@hotmail")!==false) or (strpos($guest->email, "@msn")!==false) or (strpos($guest->email, "@live")!==false)) {
				addMessage("Les adresses Hotmail et MSN mettent un peu de temps à arriver. En cas de problème, envoyez un mail à ".$mailFromSoiree.".");
			}
            
            if ($guest->emailDejaValide()){
                $guest->changeStatutToValideOrAttente();
            }
            
            if ($guest->sendMailValidation()) {
                if ($guest->statut == 'Non validé') {
                    addMessage("Vous êtes enregistré mais pas encore validé.");
                } else {
                    addMessage("Vous êtes enregistré.");
                }
                headerLocation('sd', array('view'=>'guest', 'id'=>$guest->id));
            } else {
                $guest->statut = 'Supprimé';
                $guest->save();
                addMessage("Votre enregistrement n'a pas été validé ! Merci de réessayer ou d'envoyer un mail à ".$mailFromSoiree.".");
                headerLocation('sd');
            }
        }
		//addMessage("Le formulaire d'inscription n'est pas rempli correctement...");
		foreach($guest->errors as $st) {
			addMessage($st);
		}
		include(headerView('sd'));
	}

	function sd_action_resend() {
		Global $mailFromSoiree;
		if (!isset($_GET['email'])) {$email='';} else {$email=$_GET['email'];}
		if (isset($_POST['email'])) {$email=$_POST['email'];}
		$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
		
		if (!preg_match($Syntaxe,$email)) {
			addMessage('Le mail n\'est pas valide...');
			headerLocation('sd');
		}
		if ((strpos($email, "@hotmail")!==false) or (strpos($email, "@msn")!==false) or (strpos($email, "@live")!==false)) {
			addMessage("Les adresses Hotmail et MSN mettent un peu de temps à arriver. En cas de problème, envoyez un mail à ".$mailFromSoiree.".");
		}

		$nextParty = party_getNextActive();
		if (guest_sendInformationEmail($email, $nextParty)){
			addMessage('Un mail a été envoyé à l\'adresse indiquée.');
			headerLocation('sd');
		} else {
			addMessage('Impossible d\'envoyer le mail ! Vous pouvez ecrire à '.$mailFromSoiree.'.');
			headerLocation('sd');
		}
		exit();
	}

	function sd_action_validate() {
		global $id, $guest, $mailFromSoiree;
		if (!isset($_GET['pass'])) {$pass='';} else {$pass=$_GET['pass'];}
		$guest = new guest($id);
		if ($guest != null && $guest->pass == $pass) {
			$guest->changeStatutToValideOrAttente();
			$guest->sendMailValidation();
			$_SESSION['idGuest'] = $guest->id;
			headerLocation('sd', array('view'=>'guest', 'id'=>$id, 'pass'=>$guest->pass));
		} else {
			addMessage('Impossible de vous identifier pour valider votre inscription... merci d\'écrire à '.$mailFromSoiree.'');
			headerLocation('sd');
		}
	}

	//traitement Illbethere et Iwontbethere
	function sd_action_y() {
		sd_action_y_or_n('YES');
	}
	
	function sd_action_n() {
		sd_action_y_or_n('NO');
	}
	
	function sd_action_y_or_n($action) {
		global $id;
		if (!isset($_GET['pass'])) {$pass='';} else {$pass=$_GET['pass'];}
		$guest = new guest($id);
		$party = $guest->get_party();
		if ($guest != null && $guest->pass == $pass) {
			if ($action=='YES') {
				if ($guest->statut == 'Validé') {
					$_SESSION['idGuest'] = $guest->id;
					addMessage("Tu es déjà enregistré...");
					headerLocation('sd', array('view'=>'guest', 'id'=>$guest->id, 'pass'=>$guest->pass));
				}
				if (($guest->sexe=='F' && $party->get_nbladies_strict()>=$party->maxPeople) ||
								($guest->sexe!='F' && $party->get_nbgentlemen_strict()>=$party->maxPeople)) {
					$guest->statut = 'Attente';
					$guest->save();
					$_SESSION['idGuest'] = $guest->id;
					addMessage("Désolé, c'est trop tard, la place disponible a été prise... Tu es à nouveau sur liste d'attente.");
					headerLocation('sd', array('view'=>'guest', 'id'=>$guest->id, 'pass'=>$guest->pass));
				}
				$guest->statut = 'Validé';
				$guest->save();
				$_SESSION['idGuest'] = $guest->id;
				addMessage("Merci pour ta réponse, on compte sur toi !");
				$guest->sendMailValidation();
				headerLocation('sd', array('view'=>'guest', 'id'=>$guest->id, 'pass'=>$guest->pass));
			} else {
				$guest->statut = 'Supprimé';
				$guest->save();
				$_SESSION['idGuest'] = $guest->id;
				addMessage("Nous avons bien noté que tu ne viendrais pas. Merci pour ta réponse...");
				headerLocation('sd', array('view'=>'guest', 'id'=>$guest->id, 'pass'=>$guest->pass));
			}
		} else {
			headerLocation('sd');
		}
	}


	function sd_action_annuler() {
		global $id;
		$guest = new guest($id);
		
		$guest->statut = 'Supprimé';
		$guest->save();
		$guest->sendMailAnnulation();
		addMessage('Ta participation a été supprimée...');
		headerLocation('sd');
	}
?>