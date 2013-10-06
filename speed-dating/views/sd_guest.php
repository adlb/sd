<? include 'models/z_tete.v'; ?>
<div id=content>
	<div id=presentationSoiree>
		<a href=index.php><img src=images/<?=$party->image?>></a>
		<h1><?=$party->name?></h1>
		<h2><?=$party->typedesoiree?></h2>
		<h3><?=strftime("%A %d %B %Y",$party->date)?> • <?=$party->heure?></h2>
		<p><b>Adresse : <?=$party->adresse1?></b><br /><?=$party->adresse2?></p>
		<p><b>Info : </b><?=$party->contactInfo?></p>
		<p><?=$party->descriptionFormule?></p>
		<div style="clear:both;"></div>
	</div>
	<?=displayMessage()?>
<div id=wrap_line>
	<div id=helpus>
		<H1>Feed-back</H1> 
		</br>
		Aidez-nous à améliorer les soirées.<br/>
		</br>
		<a href="?view=eqs" class=bouton>Je vous aide !</a>
	</div> 
	<div id=showGuest>
		<H1>Bonjour <?=$guest->firstname?>,</H1>
		
		<H2>Tu t'es inscrit<?=(($guest->sexe=='F')?'e':'')?> &aacute; la soir&eacute;e Speed-dating du <?=date('d/m/y',$party->date)?>.</H2>
		<br/>
		<? if ($guest->statut=='Validé') { ?>
			Ton inscription est bien valid&eacute;e et nous t'attendons donc &agrave; 20h30 pr&eacute;cise le <?=date('d/m/y',$party->date)?> au Loomi's (106, rue Montmartre - 75002 PARIS - M&deg; Bourse).<br/>
			Si tu as des questions ou des informations &agrave; nous donner : <?=$party->contactInfo?><br/>
			<br/>
			<a href="?id=<?=$guest->id?>&action=annuler" class=bouton>Me d&eacute;sinscrire</a>
			<a href="?action=retour" class=bouton>Retour</a>
		<? } elseif ($guest->statut=='Non validé' && !$shouldbevalidated) { ?>
			Ton inscription N'EST PAS ENCORE VALIDEE.<br/>Tu as d&ucirc; recevoir un mail sur <?=$guest->email?> avec un lien permettant de la valider.<br/><br/><br/>
			Valide rapidement l'inscription car les places sont limit&eacute;es.<br/>
			Si tu as des questions ou des informations à nous donner : <?=$party->contactInfo?><br/>
			<br/>
			<a href="?id=<?=$guest->id?>&action=annuler" class=bouton>Me d&eacute;sinscrire</a>
			<a href="?id=<?=$guest->id?>&action=resend&email=<?=$guest->email?>" class=bouton>Me renvoyer le mail</a>
			<a href="?action=retour" class=bouton>Retour</a>
		<? } elseif ($guest->statut=='Non validé' && $shouldbevalidated) { ?>
			Ton inscription N'EST PAS ENCORE VALIDEE.<br/>Tu as d&ucirc; recevoir un mail sur <?=$guest->email?> avec un lien permettant de la valider.<br/>
			Tu peux aussi utiliser le bouton ci-dessous pour valider ton inscription.<br/><br/><br/>
			Valide rapidement l'inscription car les places sont limit&eacute;es.<br/>
			Si tu as des questions ou des informations à nous donner : <?=$party->contactInfo?><br/>
			<br/>
			<a href="?id=<?=$guest->id?>&action=annuler" class=bouton>Me d&eacute;sinscrire</a>
			<a href="?id=<?=$guest->id?>&action=validate&pass=<?=$guest->pass?>" class=bouton>Validate</a>
			<a href="?action=retour" class=bouton>Retour</a>
		<? } elseif ($guest->statut=='Attente') { ?>
			Ton inscription est bien valid&eacute;e mais tu es sur liste d'attente car c'est complet. <br/>
			Nous te ferons signe en cas de d&eacute;sistement et sinon nous t'enverrons une invitation pour la prochaine session.<br/>
			Si tu as des questions ou des informations &agrave; nous donner : <?=$party->contactInfo?><br/>
			<br/>
			<a href="?id=<?=$guest->id?>&action=annuler" class=bouton>Me d&eacute;sinscrire</a>
			<a href="?action=retour" class=bouton>Retour</a>
		<? } elseif ($guest->statut=='Relancé') { ?>
			Ton inscription est bien valid&eacute;e mais tu étais sur liste d'attente car la soirée était complète. <br/>
			MAIS UNE PLACE S'EST LIBEREE !!! <br/>
			Souhaites-tu venir ? <br/>
			<br/>
			<a class=bouton href="?view=guest&id=<?=$guest->id?>&pass=<?=$guest->pass?>&action=y">Oui, c'est top !</a>&nbsp;
			<a class=bouton href="?view=guest&id=<?=$guest->id?>&pass=<?=$guest->pass?>&action=n">Non, j'ai piscine !</a><br/>
			Si tu as des questions ou des informations &agrave; nous donner : <?=$party->contactInfo?><br/>
			<br/>
			<a href="?id=<?=$guest->id?>&action=annuler" class=bouton>Me d&eacute;sinscrire</a>
			<a href="?action=retour" class=bouton>Retour</a>
		<? } else { //if ($guest->statut=='Supprimé')?>
			Ton inscription a &eacute;t&eacute; supprim&eacute;e. Si c'est une erreure, envoie un mail &agrave; <?=$party->contactInfo?><br/>
			<br/>
			<a href="?action=retour" class=bouton>Me réinscrire</a>
		<? } ?>
		<br/>
	</div>
</div>
</div>

</div>
</body>
</html>