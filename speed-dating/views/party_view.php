<? include('models/z_tete_admin.v'); ?>
    <script language="JavaScript">
	function submitForm(link, dest)
	{ 
		var req = null; 

		//document.getElementById(dest).innerHTML = "Started...";
 
		if (window.XMLHttpRequest)
		{
 			req = new XMLHttpRequest();

		} 
		else if (window.ActiveXObject) 
		{
			try {
				req = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e)
			{
				try {
					req = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
        	}


		req.onreadystatechange = function()
		{ 
			//document.getElementById(dest).innerHTML = "Wait server...";
			if(req.readyState == 4)
			{
				if(req.status == 200)
				{
					document.getElementById(dest).innerHTML = req.responseText;	
				}	
				else	
				{
					document.getElementById(dest).innerHTML = "Error: returned status code " + req.status + " " + req.statusText;
				}	
			} 
		}; 
		req.open("GET", link, true); 
		req.send(null); 
	} 
	</script>


<?=displayLink('Retour', 'parties')?> 
<?=displayLink('Assistant Email', 'mail')?> 
<?=displayLink('Excel', 'otherSheets', array('id'=>$id, 'view'=>'partyExcel'))?> 
<?=displayLink('Rotation', 'otherSheets', array('view' => 'rotation', 'tableRotation'=>$party->tableRotation))?> 
<?=displayLink('Feuilles d\'inscription', 'otherSheets', array('view'=>'inscriptionPdf', 'id'=>$party->id))?> 
<?=displayLink('Ajouter numéro', 'party', array('view'=>'addGivenNumbers', 'id'=>$party->id))?> 
<?=displayLink('Feuille avec les numéro de la soirée', 'otherSheets', array('view'=>'afterPartyPdf', 'id'=>$party->id))?> 

<br/>

<? if ($party->image!='') {?><div id=floatimage><img src=images/<?=$party->image?> height=50></div><?}?>

<H1><?=$party->name?>&nbsp;&nbsp;&nbsp;</H1><br/>
<P>Date de la soir&eacute; : <?=date('d/m/y', $party->date)?>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
Date ouverture réservation : <?=date('d/m/y', $party->dateOpen)?>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
Nombre max de filles et de garçons : <?=$party->maxPeople?>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
Active : <?=($party->active)?'Oui':'Non'?></P>

<P>
<?=displayLink('Actualiser', $party)?> 
<?=displayLink('Ajouter une personne', 'guest', array('view' => 'new', 'id_party' => $party->id))?> 
<?=displayLink('Modifier', $party, array('view' => 'edit'))?> 
</p>

<div style="margin:auto;width:800px;background-color:#DDFFDD;">
<? $gens = $party->get_ladies(); ?>
<div id="TitreF" style="background-color:pink;border-width:0px 0px 1px 0px; border-style:solid; border-color:black;"><? $sexe = 'F'; include('views/party_sexeBandeau.php'); ?></div>
<? for($i=0;$i<count($gens);$i++) { ?>
   <div id="gens_<?=$gens[$i]->id?>">
        <? $guest = $gens[$i]; include('views/guest_viewsmall.php'); ?>
    </div>
<? } ?>
</div> 
<br/>
<div style="margin:auto;width:800px;background-color:#DDFFDD;">
<div id="TitreH" style="background-color:#CCCCFF;border-width:0px 0px 1px 0px; border-style:solid; border-color:black;"><? $sexe = 'H'; include('views/party_sexeBandeau.php'); ?></div>
<? $gens = $party->get_gentlemen(); ?>
<? for($i=0;$i<count($gens);$i++) { ?>
    <div id="gens_<?=$gens[$i]->id?>">
        <? $guest = $gens[$i]; include('views/guest_viewsmall.php'); ?>
    </div>
<? } ?>
</div>

<!--
<table width=100%>
<tr><td width=50%>
<table class=tableau>
<tr><th colspan=5>Filles</th></tr>
<? $gens = $party->get_ladies(); ?>

<? for($i=0;$i<count($gens);$i++) { ?>
<? if (($gens[$i]->id) == $_GET['activeGuestId']) {$tt = ' style="background-color:yellow;"';} else {$tt='';} ?>
<tr<?=$tt?>>
        <td><?=t($gens[$i]->id)?></td>
		<td style="text-align:left;"><?=t($gens[$i]->firstname.' '.$gens[$i]->lastname)?></td>
		<td style="text-align:left;font-size:90%;"><?=t($gens[$i]->email, 40)?></td>
		<td><?=t($gens[$i]->statut)?></td>
        <td><a href="javascript:;" onClick="submitForm('?<?=get_url_link($gens[$i])?>');">action</a></td>
</tr>
<? } ?>
</table>
</td><td style="vertical-align:top;">
<div id="zone_dyn" style="border-width: 1px; border-style: solid; border-color: #000000; ">
<H1> ... </H1>
</div> 
</td></tr></table>
<? if (isset($_GET['activeGuestId'])) {
    echo '<script language="JavaScript">submitForm(\'?'.get_url_link('guest',array('id'=>$_GET['activeGuestId'])).'\');</script>';
} ?>

<table class=tableau>
<tr><th colspan=14>... Ceux qui viennent ...</th></tr>
<tr><th colspan=14>&nbsp;</th</tr>
<tr><th colspan=7>filles</th><th colspan=7>gars</th></tr>
<? for($i=0;$i<$party->maxPeople;$i++) {?>
<tr>
	<td><?=$i+1?></td>
	<? if (count($ladies_inscrit)>0) { 
		$lady=array_shift($ladies_inscrit);
		?>
		<td><?=t($lady->firstname)?></td>
		<td><?=t($lady->lastname)?></td>
		<td><?=t($lady->telephone)?></td>
		<td><?=t($lady->email, 30)?></td>
		<td><?=t($lady->statut)?></td>
		<td>							<?=displayLinkImage('delete.png', $lady, array('action' => 'delete'))?> 
										<?=displayLinkImage('edit.png', $lady, array('view' => 'edit'))?> 
										<?=displayLinkImage('mail.jpg', 'mail', array('view' => 'view', 'id_guest' => $lady->id))?> 
										<?=displayLinkImage('validate.gif', $lady, array('action' => 'validate'))?> 
										<br /><?=displayLinkImage('unvalidate.gif', $lady, array('action' => 'unvalidate'))?> 
										<?=displayLinkImage('relanceValidation.gif', $lady, array('action' => 'relancerValidation'))?> 
										<?=displayLinkImage('relanceDispo.gif', $lady, array('action' => 'relancerPlaceDispo'))?> 
										<?=displayLinkImage('relanceAnnulation.gif', $lady, array('action' => 'annulerRelance'))?> 
		</td>
	<? } else {?>
		<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>
	<? } ?>
	<td><?=$i+1+$party->maxPeople?></td>
	<? if (count($gentlemen_inscrit)>0) { 
		$lady=array_shift($gentlemen_inscrit);
		?>
		<td><?=t($lady->firstname)?></td>
		<td><?=t($lady->lastname)?></td>
		<td><?=t($lady->telephone)?></td>
		<td><?=t($lady->email,30)?></td>
		<td><?=t($lady->statut)?></td>
		<td>							<?=displayLinkImage('delete.png', $lady, array('action' => 'delete'))?> 
										<?=displayLinkImage('edit.png', $lady, array('view' => 'edit'))?> 
										<?=displayLinkImage('mail.jpg', 'mail', array('view' => 'view', 'id_guest' => $lady->id))?> 
										<?=displayLinkImage('validate.gif', $lady, array('action' => 'validate'))?> 
										<br /><?=displayLinkImage('unvalidate.gif', $lady, array('action' => 'unvalidate'))?> 
										<?=displayLinkImage('relanceValidation.gif', $lady, array('action' => 'relancerValidation'))?> 
										<?=displayLinkImage('relanceDispo.gif', $lady, array('action' => 'relancerPlaceDispo'))?> 
										<?=displayLinkImage('relanceAnnulation.gif', $lady, array('action' => 'annulerRelance'))?> 
		</td>
	<? } else {?>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<? } ?>
</tr>
<?}?>
<?
$ladies_inscrit = array_merge($ladies_inscrit, $ladies_supprime);
$gentlemen_inscrit = array_merge($gentlemen_inscrit, $gentlemen_supprime);
?>
<tr><td colspan=14>&nbsp;</td</tr>
<tr><th colspan=14>... Ceux qui ne viennent pas ...</th</tr>
<tr><th colspan=14>&nbsp;</th</tr>
<tr><th colspan=7>filles</th><th colspan=7>gars</th></tr>

<? 
$max = max(count($ladies_inscrit),count($gentlemen_inscrit));
for($i=0;$i<$max;$i++) {?>
<tr>
	<td><?=$i+1?></td>
	<? if (count($ladies_inscrit)>0) { 
		$lady=array_shift($ladies_inscrit);
		?>
		<td><?=t($lady->firstname)?></td>
		<td><?=t($lady->lastname)?></td>
		<td><?=t($lady->telephone)?></td>
		<td><?=t($lady->email, 30)?></td>
		<td><?=t($lady->statut)?></td>
		<td>							<?=displayLinkImage('delete.png', $lady, array('action' => 'delete'))?> 
										<?=displayLinkImage('edit.png', $lady, array('view' => 'edit'))?> 
										<?=displayLinkImage('mail.jpg', 'mail', array('view' => 'view', 'id_guest' => $lady->id))?> 
										<?=displayLinkImage('validate.gif', $lady, array('action' => 'validate'))?> 
										<br /><?=displayLinkImage('unvalidate.gif', $lady, array('action' => 'unvalidate'))?> 
										<?=displayLinkImage('relanceValidation.gif', $lady, array('action' => 'relancerValidation'))?> 
										<?=displayLinkImage('relanceDispo.gif', $lady, array('action' => 'relancerPlaceDispo'))?> 
										<?=displayLinkImage('relanceAnnulation.gif', $lady, array('action' => 'annulerRelance'))?> 
		</td>
	<? } else {?>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<? } ?>
	<td><?=$i+1+$party->maxPeople?></td>
	<? if (count($gentlemen_inscrit)>0) { 
		$lady=array_shift($gentlemen_inscrit);
		?>
		<td><?=t($lady->firstname)?></td>
		<td><?=t($lady->lastname)?></td>
		<td><?=t($lady->telephone)?></td>
		<td><?=t($lady->email, 30)?></td>
		<td><?=t($lady->statut)?></td>
		<td>							<?=displayLinkImage('delete.png', $lady, array('action' => 'delete'))?> 
										<?=displayLinkImage('edit.png', $lady, array('view' => 'edit'))?> 
										<?=displayLinkImage('mail.jpg', 'mail', array('view' => 'view', 'id_guest' => $lady->id))?> 
										<?=displayLinkImage('validate.gif', $lady, array('action' => 'validate'))?> 
										<br /><?=displayLinkImage('unvalidate.gif', $lady, array('action' => 'unvalidate'))?> 
										<?=displayLinkImage('relanceValidation.gif', $lady, array('action' => 'relancerValidation'))?> 
										<?=displayLinkImage('relanceDispo.gif', $lady, array('action' => 'relancerPlaceDispo'))?> 
										<?=displayLinkImage('relanceAnnulation.gif', $lady, array('action' => 'annulerRelance'))?> 
		</td>
	<? } else {?>
		<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>	<td>&nbsp;</td>
	<? } ?>
</tr>
<?}?>
</table>

<br>
<br>
<table class="tableau">
<tr><th>Icone</th><th>Explication</th></tr>
<tr><td><img src="ressources/delete.png"></td><td style="text-align:left;">Fait passer l'invité à l'état 'Supprimé' Il apparait donc ensuite dans le tableau du bas. Cependant, aucun mail ne lui est envoyé automatiquement... Il faut le prévenir qu'il n'est plus attendu...</td></tr> 
<tr><td><img src="ressources/edit.png"></td><td style="text-align:left;">Ouvre un formulaire qui permet de modifier les informations de la personne. ça permet aussi de voir l'adresse mail complète.</td></tr>
<tr><td><img src="ressources/mail.jpg"></td><td style="text-align:left;">Permet d'envoyer un mail personnalisé à l'invité.</td></tr>
<tr><td><img src="ressources/validate.gif"></td><td style="text-align:left;">Fait passer l'invité à l'état 'Validé' ou 'Attente' en fonction de la dispo. Cependant, aucun mail ne lui est envoyé automatiquement...</td>
<tr><td><img src="ressources/unvalidate.gif"></td><td style="text-align:left;">Fait passer l'invité à l'état 'Non Validé'. Cependant, aucun mail ne lui est envoyé automatiquement... Il faut le prévenir qu'il n'est plus attendu...</td>
<tr><td><img src="ressources/relanceValidation.gif"></td><td style="text-align:left;">Uniquement pour les invités à l'état 'Non Validé' : envoie un mail de relance pour qu'il valide son adresse mail.</td>
<tr><td><img src="ressources/relanceDispo.gif"></td><td style="text-align:left;">Uniquement pour les invités à l'état 'Attente' et s'il reste de la place : envoie un mail de relance disant qu'il y a des places disponibles s'il souhaite venir.</td>
<tr><td><img src="ressources/relanceAnnulation.gif"></td><td style="text-align:left;">Uniquement pour les invités à l'état 'Relancé' : envoie un mail pour dire que la place qui s'était libérée est finalement prise.</td>
</table>
-->
<br><br>