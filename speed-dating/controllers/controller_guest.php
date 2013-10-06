<?
	require_once ('controller_login.php');
	
	function guest_controller_before_action_or_view($action, $view) {
		if (!login_isAdmin()) {
			headerLocation('login');
		}
	}

	function guest_action_create() {
		Global $guest;
		$guest = new guest($_POST, $guest->fields_in_form);
		$guest->partyId = $_GET['id_party'];
		if ($guest->validateDatas()) {
			$guest->save();
			headerLocation($guest->get_party());
		} else {
			include(headerView($guest, array('view' => 'new', 'id_party' => $_GET['id_party'])));
			exit();
		}
	}

	function guest_action_update() {
		Global $guest;
		$guest->updateFromForm($_POST, $guest->fields_in_form);
        if ($guest->validateDatas()) {
		    $guest->save();
			headerLocation($guest->get_party());
		} else {
			include(headerView($guest, array('view' => 'edit')));
			exit();
		}
	}

	function guest_action_delete() {
		Global $guest;
		if ($guest==null) {
			addMessage('Erreur...');	  
			headerLocation('parties', array());
		}
		if ($guest->statut=='Supprimé') {
			addMessage(''.$guest->firstname.' '.$guest->lastname.' est déjà au statut \'Supprimé\'... Pour le supprimer définitivement c\'est <a href=?obj=guest&action=totalDelete&id='.$guest->id.'>ici</a> !');	  
			headerLocation($guest);
		}
		if ($guest->statut!='Validé' && $guest->statut!='Non validé' && $guest->statut!='Attente') {
			addMessage('Cette action est réservée aux invités aux statuts \'Attente\', \'Validé\' ou \'Non validé\'...');	  
			headerLocation($guest);
		}	
		$guest->statut='Supprimé';
		$guest->save();
		addMessage(''.$guest->firstname.' '.$guest->lastname.' a été remis au statut \'Supprimé\'... Cette action n\'a généré aucun email à l\'invité. Pour le supprimer définitivement c\'est <a href=?obj=guest&action=totalDelete&id='.$guest->id.'>ici</a> !');	  
		headerLocation($guest);
	}

	function guest_action_totalDelete() {
		Global $guest;
		if ($guest->statut!='Supprimé') {
			addMessage(''.$guest->firstname.' '.$guest->lastname.' n\'est pas au statut \'Supprimé\'... ');	  
			headerLocation($guest->get_party());
		}
		$party = $guest->get_party();
		$mess = ''.$guest->firstname.' '.$guest->lastname.' a été supprimé des bases.';	  
		$guest->delete();
		addMessage($mess);	  
		headerLocation($party);
	}

	function guest_action_validate() {
		Global $guest;
		$guest->changeStatutToValideOrAttente();
		addMessage(''.$guest->firstname.' '.$guest->lastname.' a été remis au statut \''.$guest->statut.'\'... Cette action n\'a généré aucun email à l\'invité !');	  
		headerLocation($guest);
	}

    
    function guest_action_changestatut() {
		Global $guest;
		if (isset($_GET['newstatut']) && in_array($_GET['newstatut'], array('Supprimé', 'Validé', 'Non validé', 'Attente', 'Relancé'))) {
            $guest->statut = $_GET['newstatut'];
            $guest->save();
            addMessage(''.$guest->firstname.' '.$guest->lastname.' a été remis au statut \''.$_GET['newstatut'].'\'... Cette action n\'a généré aucun email à l\'invité. <BR>S\'il ne vient finalement pas, il vaut mieux le mettre au statut \'Supprimé\'!');	  
            //headerLocation($guest);
            include(headerView($guest));
        } else {
            addMessage('Erreur ! pas de changement de statut...');	  
            include(headerView($guest));
        }
	}
    
	function guest_action_unvalidate() {
		Global $guest;
		if ($guest->statut=='Non validé') {
			addMessage(''.$guest->firstname.' '.$guest->lastname.' était déjà au statut \'Non Validé\'... ');	  
			headerLocation($guest);
		}
		if ($guest->statut!='Validé' && $guest->statut!='Supprimé') {
			addMessage('Cette action est réservée aux invités au statut \'Validé\' ou \'Supprimé\'...');	  
			headerLocation($guest);
		}	
		$guest->statut='Non validé';
		$guest->save();
		addMessage(''.$guest->firstname.' '.$guest->lastname.' a été remis au statut \'Non Validé\'... Cette action n\'a généré aucun email à l\'invité. <BR>S\'il ne vient finalement pas, il vaut mieux le mettre au statut \'Supprimé\'!');	  
		headerLocation($guest);
	}
    
    function guest_action_envoyerConfirmation() {
		Global $guest;
		$party = $guest->get_party();
		if ($guest->statut!='Validé') {
			addMessage('Le statut de '.$guest->firstname.' '.$guest->lastname.' est \''.$guest->statut.'\'. Il n\'est pas possible de confirmer quelqu\'un qui n\'est pas au statut \'Validé\'.');
		} else {
			if (!$guest->sendMailValidation()) {
				addMessage('Erreur pendant l\'envoi du mail à '.$guest->firstname.' '.$guest->lastname.'!');	
			}
			addMessage(''.$guest->firstname.' '.$guest->lastname.' a été confirmé... sur son email ('.$guest->email.')!');	  
		}
		include(headerView($guest));
	}
	
    function guest_action_envoyerMessageDattente() {
		Global $guest;
		$party = $guest->get_party();
		if ($guest->statut!='Attente') {
			addMessage('Le statut de '.$guest->firstname.' '.$guest->lastname.' est \''.$guest->statut.'\'. Il n\'est pas possible de confirmer quelqu\'un qui n\'est pas au statut \'Attente\'.');
		} else {
			if (!$guest->sendMailValidation()) {
				addMessage('Erreur pendant l\'envoi du mail à '.$guest->firstname.' '.$guest->lastname.'!');	
			}
			addMessage('Un message a été envoyé à '.$guest->firstname.' '.$guest->lastname.' pour lui indiquer qu\'il était sur liste d\'attente... sur son email ('.$guest->email.')!');	  
		}
		include(headerView($guest));
	}
	
    function guest_action_relancerPlaceDispo() {
		Global $guest;
		$party = $guest->get_party();
		if ($guest->statut!='Attente') {
			addMessage('Le statut de '.$guest->firstname.' '.$guest->lastname.' est \''.$guest->statut.'\'. Il n\'est pas possible de Relancer pour Disponibilité (RD) quelqu\'un qui n\'est pas au statut \'Attente\'.');
		} elseif (($guest->sexe=='F' && $party->get_nb_people('F', array('Validé'))>=$party->maxPeople) or 
				  ($guest->sexe!='F' && $party->get_nb_people('H', array('Validé'))>=$party->maxPeople)) {
			addMessage('Pour pouvoir relancer quelqu\'un, il doit rester des places disponibles. Le nombre de places occupées est la somme des invités au statut \'Validé\'. Si vous souhaitez relancer plus de personne que de places disponibles, par exemple pour relancer plusieurs mecs, il est possible de le faire en augmentant le nombre max de personne à la soirée.');
		} else {
			if (($guest->sexe=='F' && $party->get_nb_people('F', array('Validé', 'Relancé'))>=$party->maxPeople) or 
				($guest->sexe!='F' && $party->get_nb_people('H', array('Validé', 'Relancé'))>=$party->maxPeople)) {
				addMessage('Le total de places occupées par les invités au statut \'Validé\' et au statut \'Relancé\' est suppérieur au nombre de places disponiblesCe n\'est pas génant mais seul le ou les premiers à répondre auront une place.');
			}
			if (!$guest->sendMailFinalementTuViens()) {
				addMessage('Erreur pendant l\'envoi du mail à '.$guest->firstname.' '.$guest->lastname.'!');	
			}
			$guest->statut = 'Relancé';
			$guest->save();
			addMessage(''.$guest->firstname.' '.$guest->lastname.' a été relancé pour disponibilité... sur son email ('.$guest->email.')!');	  
		}
		include(headerView($guest));
	}

	function guest_action_relancerValidation() {
		Global $guest;
		if ($guest->statut!='Non validé') {
			addMessage('Il n\'est pas possible de relancé une personne qui n\'est pas au statut \'Non validé\'');	  
		} elseif (!$guest->sendMailEnregistrementAValiderRelance()) {
			addMessage('Erreur pendant l\'envoi du mail à '.$guest->firstname.' '.$guest->lastname.'!');	  
		} else {
			addMessage(''.$guest->firstname.' '.$guest->lastname.' a été relancé pour validation... sur son email ('.$guest->email.')!');	  
		}
		include(headerView($guest));
	}

	function guest_action_annulerRelance() {
		Global $guest;
		if ($guest->statut!='Relancé') {
			addMessage('Le statut de '.$guest->firstname.' '.$guest->lastname.' est \''.$guest->statut.'\'. Il n\'est pas possible d\'annuler la relance pour quelqu\'un qui n\'est pas au statut \'Relancé\'.');	  
		} else {
			if (!$guest->sendMailAnnuleRelance()) {
				addMessage('Problème pendant l\'annulation de la relance de '.$guest->firstname.' '.$guest->lastname.'.');	  
			}
			$guest->statut = 'Attente';
			$guest->save();
			addMessage('La relance de '.$guest->firstname.' '.$guest->lastname.' est bien annulée.');	  
		}
		include(headerView($guest));
	}
    
    function guest_action_supprimeEmail(){
        Global $guest;
        addMessage('Cette action va supprimer deffinitivement l\'adresse '.$guest->email.
        ' de la base et sera remplacée par aaa@bbb.com)! Si vous êtes sûr c\'est '.
        displayLinkAjax('ICI', $guest, array('action' => 'supprimeEmailTotal'), 'gens_'.$guest->id).'.');	  
        include(headerView($guest));
    }
    
    function guest_action_supprimeEmailTotal(){
        Global $guest;
        $guest->removemyemailfromdatabase();
        $guest = new Guest($guest->id);
        addMessage('C\'est fait !');	  
        include(headerView($guest));
    }
    

?>