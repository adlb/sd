<?

require "PHPMailer_v2.0.4/class.phpmailer.php";

//simplified structure to hande mail may-be useless.
class monMail {
	var $id = '';
	var $from = '';
	var $to = '';
	var $copy = '';
	var $bcc = '';
	var $subject = '';
	var $text = '';
	
	function getInfos(){
		return date('d/m/y-H:i:s');
	}
}

//function to send simplified structure defined above.
function send_email_via_gmail($mm) {
	Global $mailHost, $mailUserName, $mailPassword;
	$mail = new PHPmailer();
	$mail->IsSMTP();
	$mail->IsHTML(true);
	$mail->Host = $mailHost; 
	$mail->SMTPAuth = TRUE;
	$mail->Username = $mailUsername;  
	$mail->Password = $mailPassword;  
	if ($mm->from != '') {
		$mail->From = $mm->from; 
		$mail->FromName = $mm->from;
		$mail->AddReplyTo($mm->from);    
	}
	if ($mm->to != '') {
		$list_to = explode(",", $mm->to);
		foreach($list_to as $to) {
			$mail->AddAddress($to);
		}
	}
	if ($mm->bcc != '') {
		$list_bcc = explode(",", $mm->bcc);
		foreach($list_bcc as $bcc) {
			$mail->AddBCC($bcc);
		}
	}
	if ($mm->copy != '') {
		$list_copy = explode(",", $mm->copy);
		foreach($list_copy as $copy) {
			$mail->AddCC($copy);
		}
	}
	$mail->Subject=$mm->subject;
	$mail->Body=$mm->text; 

	if(!$mail->Send()){ 
		$mail->SmtpClose();
		$error = $mail->ErrorInfo;
		unset($mail);
		return $error;
    } else {    
		$mail->SmtpClose();
		unset($mail);
		return 'Mail OK';
    }
}


function replaceInformation($string, $guest, $party) {
	global $uri;
	if ($party != null) {
		$string = str_replace("%party_name%", $party->name, $string);
		$string = str_replace("%party_date%", date('d/m/y', $party->date), $string);
        $string = str_replace("%party_heure%", $party->heure, $string);
        $string = str_replace("%party_adresse1%", $party->adresse1, $string);
        $string = str_replace("%party_adresse2%", $party->adresse2, $string);
        $string = str_replace("%party_contactInfo%", $party->contactInfo, $string);
    }
	if ($guest != null) {
		$string = str_replace("%guest_firstname%", $guest->firstname, $string);
		$string = str_replace("%guest_email%", $guest->email, $string);
		$string = str_replace("%(e)%", ($guest->sexe=='F')?'e':'', $string);
		$string = str_replace("%validationlink%", $uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&action=validate&pass='.$guest->pass, $string);
		$string = str_replace("%noactionlink%", $uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&pass='.$guest->pass, $string);
		$string = str_replace("%illbetherelink%", $uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&pass='.$guest->pass.'&action=y', $string);
		$string = str_replace("%iwontbetherelink%", $uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&pass='.$guest->pass.'&action=n', $string);
	}
	$string = str_replace("%uri%", $uri, $string);
	$string = str_replace('\n', "\r\n", $string);
	$string = utf8_decode($string);
	return $string;
}
function replaceInformationHtml($string, $guest, $party) {
	global $uri;
	$string = htmlentities($string,ENT_COMPAT,'UTF-8');
	if ($party != null) {
		$string = str_replace("%party_name%", htmlentities($party->name,ENT_COMPAT,'UTF-8'), $string);
		$string = str_replace("%party_date%", date('d/m/y', $party->date), $string);
        $string = str_replace("%party_heure%", htmlentities($party->heure,ENT_COMPAT,'UTF-8'), $string);
        $string = str_replace("%party_adresse1%", htmlentities($party->adresse1,ENT_COMPAT,'UTF-8'), $string);
        $string = str_replace("%party_adresse2%", htmlentities($party->adresse2,ENT_COMPAT,'UTF-8'), $string);
        $string = str_replace("%party_contactInfo%", htmlentities($party->contactInfo,ENT_COMPAT,'UTF-8'), $string);
	}
	if ($guest != null) {
		$string = str_replace("%guest_firstname%", htmlentities($guest->firstname,ENT_COMPAT,'UTF-8'), $string);
		$string = str_replace("%guest_email%", htmlentities($guest->email,ENT_COMPAT,'UTF-8'), $string);
		$string = str_replace("%(e)%", ($guest->sexe=='F')?'e':'', $string);
		$string = str_replace("%validationlink%", '<b><a href='.$uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&action=validate&pass='.$guest->pass.'>ici</a></b>', $string);
		$string = str_replace("%validationadress%", '<pre>'."\n".$uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&action=validate&pass='.$guest->pass."\n".'</pre>', $string);
		$string = str_replace("%noactionlink%", '<a href='.$uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&pass='.$guest->pass.'>ici</a>', $string);
		$string = str_replace("%illbetherelink%", '<a href='.$uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&pass='.$guest->pass.'&action=y'.'>ici</a>', $string);
		$string = str_replace("%iwontbetherelink%", '<a href='.$uri.'/index.php?&obj=sd&view=guest&id='.$guest->id.'&pass='.$guest->pass.'&action=n'.'>ici</a>', $string);
	}
	$string = str_replace("%uri%", htmlentities($uri,ENT_COMPAT,'UTF-8'), $string);
	$string = str_replace('\n', "\r\n", $string);
	$string = nl2br($string);
	$string = utf8_decode($string);
	return $string;
}

$copie = $mailFromSoiree;

function send_email($to, $guest, $party, $type) {
	global $$type, $headers, $copie, $boundary, $mailFromSoiree;
	
	$too      = (gettype($to)=='string') ? mb_encode_mimeheader($to, "UTF-8", "Q") : mb_encode_mimeheader($to->firstname, "UTF-8", "Q").' <'.$to->email.'>';

	//$too      = (gettype($to)=='string') ? $to : utf8_decode(htmlentities($to->firstname,ENT_COMPAT,'UTF-8')).' <'.$to->email.'>';

	$subject = utf8_decode($party->name." du ".date('d/m/y', $party->date)."");
	$message = "--".$boundary."\r\n".'Content-Type: text/plain; charset=ISO-8859-1'."\r\n\r\n".
				replaceInformation($$type, $guest, $party);
	$message .= "\r\n--".$boundary."\r\n".'Content-Type: text/html; charset=ISO-8859-1'."\r\n\r\n".
				replaceInformationHtml($$type, $guest, $party);
	$message .= "\r\n--".$boundary."--\r\n";
	log_logInBase('MAIL AUTO :'.$too.' - '.$type);
	if (false and ((strpos($too, "hotmail")!==false) or (strpos($too, "msn")!==false) or (strpos($too, "live")!==false))) {
		$boundary2 = md5(uniqid(rand()));
		$h=fopen('mailtosend.txt', 'a');
		fwrite($h,'MAIL:--'.$boundary2."\r\n");
		fwrite($h,'TO:'.((gettype($to)=='string') ? $to : $to->email)."\r\n");
		fwrite($h,'COPY:'.$copie."\r\n");
		//fwrite($h,'BCC:'.''."\r\n");
		fwrite($h,'SUBJECT:'.$subject."\r\n");
		fwrite($h,"\r\n"); //ligne blanche pour annoncer le mail
		fwrite($h,replaceInformationHtml($$type, $guest, $party)."\r\n");
		fwrite($h,'--'.$boundary2."\r\n");
		fclose($h);
		return true;
	} else {
		/*$mm = new monMail;
		$mm->to = ((gettype($to)=='string') ? $to : $to->email);
		$mm->copy = $copie;
		$mm->subject = $subject;
		$mm->from = $mailFromSoiree;
		$mm->txt = replaceInformationHtml($$type, $guest, $party);
	
		if (($a = send_email_via_gmail($mm)) == 'Mail OK')
			return true;
		return false;
		*/
		return mail($too, mb_encode_mimeheader($subject, "ISO-8859-1", "Q"), $message, "Cc: ".$copie."\r\n".$headers);
	}
}

function send_eqsMail($st) {
	global $$type, $headers, $copie;
	$to      = $copie;
	$subject = utf8_decode("Speed-dating : Réponse au questionnaire");
	$message = $st;
	$headers2 = 'Content-Type: text/html; charset=ISO-8859-1'."\r\n".
		   'X-Mailer: PHP/'.phpversion();
	log_logInBase('MAIL EQS :'.$to.' - '.$type);
	return mail($to, $subject, utf8_decode($message), $headers2);
}

function send_email_mutiple($bcc, $subject, $mail) {
	global $headers, $copie;
	$to      = $copie;
	log_logInBase('MAIL PERSO:'.$to.' - '.$subject.' - '.$mail);
	$subject = utf8_decode($subject);
	$message = "--".$boundary."\r\n".'Content-Type: text/plain; charset=ISO-8859-1'."\r\n\r\n".
				utf8_decode($mail);
	$message .= "\r\n--".$boundary."\r\n".'Content-Type: text/html; charset=ISO-8859-1'."\r\n\r\n".
				nl2br(htmlentities(utf8_decode($mail)));
	$message .= "\r\n--".$boundary;
	
	return mail($to, $subject, $message, "Bcc: ".$bcc."\r\n".$headers);
}

$endref='';
$list = array(',','.','_');
for($i=0;$i<20;$i++) {
	$a=rand(0,2);
	$endref.=$list[$a];
}

$coda ='
Bonne journée...

PS : 
Pour s\'inscrire ou se désinscrire : %noactionlink%
Pour toute information complémentaire : %party_contactInfo%

'.$endref;


$email_suite_demande_renvoi_pastrouve =	
'Bonjour,

Tu as fait une demande sur le site %uri%.
Ton adresse mail n\'est pas enregistrée pour la \'%party_name%\' du %party_date%.
N\'hésite pas à t\'inscrire.

Bonne journée...

PS : 
Pour toute information complémentaire : %party_contactInfo%
';

$email_suite_demande_renvoi_guestNonValide =
'Bonjour %guest_firstname%,

Tu as fait une demande sur le site %uri%.
Ton adresse mail n\'est pas encore validée pour la \'%party_name%\' du %party_date%.
Pour valider ton inscription, rends-toi sur %validationlink%. 
Ou bien copie-colle le lien suivant dans ton navigateur :
%validationadress%

Nous pourrons ainsi valider ton inscription.
'.$coda;

$email_suite_demande_renvoi_guestValide =
'Bonjour %guest_firstname%,

Tu as fait une demande sur le site %uri%.
Ton adresse mail est bien enregistrée pour la \'%party_name%\' du %party_date%.

Nous t\'attendons donc le %party_date% à %party_heure% au %party_adresse1% 
(%party_adresse2%).

Nous comptons sur toi !

Si par hasard tu devais te désister, merci de prévenir le plus tôt 
possible sur '.$mailFromSoiree.' afin qu\'on puisse te trouver un%(e)% remplaçant%(e)%.

'.$coda;

$email_suite_demande_renvoi_guestAttente =
'Bonjour %guest_firstname%,

Tu as fait une demande sur le site %uri%.
Ton adresse mail est bien enregistrée pour la \'%party_name%\' du %party_date%.

Cependant, tu es sur liste d\'attente. En cas de désistement, nous te ferons signe.
'.$coda;

$email_suite_demande_renvoi_guestSupprime =
'Bonjour %guest_firstname%,

Tu as fait une demande sur le site %uri%.
Ton adresse mail est enregistrée pour la \'%party_name%\' du %party_date%.
Cependant, tu as été marqué comme ne venant finalement pas.
'.$coda;

$email_suite_demande_renvoi_guestRelance =
'Bonjour %guest_firstname%,

Tu as fait une demande sur le site %uri%.
Ton adresse mail est enregistrée pour la \'%party_name%\' du %party_date%.
Nous t\'avons relancé car une place s\'est libérée.
Pour rappel, le rendez-vous est à %party_heure% au %party_adresse1% (%party_adresse2%).
Veux-tu venir ? (merci de répondre rapidement)

Oh! Yes! C\'est top : %illbetherelink%
Trop dommage, j\'ai piscine : %iwontbetherelink%
'.$coda;

$email_suite_enregistrement_guestNonValide =
'Bonjour %guest_firstname%,

Tu t\'es incrit%(e)% au speed-dating du %party_date%.
Pour valider ton inscription, rends-toi sur %validationlink%. 
Ou bien copie-colle le lien suivant dans ton navigateur :
%validationadress%
'.$coda;

$email_suite_relance_admin_pour_valider =
'Bonjour %guest_firstname%,

Tu t\'es incrit%(e)% au speed-dating du %party_date%.
Tu n\'as pas encore valider ton inscription, rends-toi sur %validationlink%.
'.$coda;

$email_suite_liberation_de_place =
'Bonjour %guest_firstname%,

Tu t\'es incrit%(e)% au speed-dating du %party_date%.

Tu étais en liste d\'attente et une place s\'est libérée. 
Pour rappel, le rendez-vous est à %party_heure% au %party_adresse1% (%party_adresse2%).

Veux-tu venir ? (merci de répondre rapidement)

Oh! Yes! C\'est top : %illbetherelink%
Trop dommage, j\'ai piscine : %iwontbetherelink%
'.$coda;

$email_suite_annule_relance_by_admin =
'Bonjour %guest_firstname%,

Finalement la place libérée à été prise par quelqu\'un d\'autre.
'.$coda;

$email_suite_validation_par_utilisateur =
'Bonjour %guest_firstname%,

Ton adresse mail est maintenant bien enregistrée pour la \'%party_name%\' du %party_date%.
Nous t\'attendons donc le %party_date% à %party_heure% au %party_adresse1% (%party_adresse2%).
Nous comptons sur toi !

Si par hasard tu devais te désister, merci de prévenir le plus tôt 
possible sur '.$mailFromSoiree.' afin qu\'on puisse te trouver un%(e)% remplaçant%(e)%.


'.$coda;

$email_suite_validation_par_utilisateur_Attente =
'Bonjour %guest_firstname%,

Ton adresse mail est maintenant bien enregistrée pour la \'%party_name%\' du %party_date%.
Cependant, tu es sur liste d\'attente. En cas de désistement, nous te ferons signe pour te proposer la place.

'.$coda;

$email_suite_annulation_par_utilisateur =
'Bonjour %guest_firstname%,

Nous avons bien enregistré que finalement tu ne viendras pas à la \'%party_name%\' du %party_date%.
'.$coda;

$email_suite_et_commencement =
'Bonjour à toi,

Merci pour ta participation active au Speed Dating du 1er Juillet...

Pour suivre les traces de Charlène et Albert, voici en pièce jointe, la 
liste des invités ainsi que les tables de rotations... Tu peux ainsi retrouver les 
coordonnées des personnes que tu as rencontrées vendredi.

Ton avenir est entre tes mains alors fait de cet événement un commencement.

Bon été !
'.$coda;

$email_relance_mecs =
'Bonjour,

Tu es inscrit à la soirée Speed-Dating du 1er Juillet...

Il reste quelques places disponibles pour la gente masculine.
C\'est le moment de partager ce très bon plan avec tes amis célibataires...

Pour s\'inscrire : xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
'.$coda;

$email_relance_last_call =
'Bonjour,

Tu t\'es inscrit à l\'une des dernières éditions des soirée speed-dating la prochaine a lieu le 1er Juillet ...
Sache qu\'il reste quelques places pour la gente masculine vendredi prochain pour la dernière édition avant les vacances...

N\'hésite pas à t\'inscrire ou à proposer à tes amis célib\'...

Comme d\'habitude: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
'.$coda;

$email_invitation_avant_premiere =
'Bonjour,

Nouvelle édition du speed-dating...
Vous étiez en liste d\'attente lors de la dernière édition, voici l\'invitation pour la prochaine soirée speed-dating en avant première...
'.$coda;

$email_dernier_mail_avant_soiree = '
Bonjour à tous,

Vous êtes inscrits au speed dating de vendredi prochain,

Si un impératif majeur ne vous permet pas de venir, par avance merci de nous avertir dès maintenant et non à la dernière minute.

Il reste encore 3 places pour la gente masculine !
n\'hésitez pas  à proposer ce "plan de dernière minute" à vos amis célibataires..

Pour vendredi, soyez à l\'heure (%party_heure%).

A vendredi !
'.$coda;

$boundary = md5(uniqid(rand()));
$headers = 'From: '.$mailFromSoiree."\r\n" .
		   'Reply-To: '.$mailFromSoiree."\r\n" .
		   'Content-Type: multipart/alternative; boundary='.$boundary."\r\n".
		   'Date: '.date('r')."\r\n".
		   //'Content-type: text/plain; charset=ISO-8859-1'."\r\n" .
		   'X-Mailer: PHP/'.phpversion();
?>