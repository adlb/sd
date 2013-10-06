<? include('models/z_tete_admin.v'); ?>

<?=displayLink('Retour', 'party', array('id'=>$party->id))?> 
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

<?=displayForm($party, array('action'=>'updateGivenNumbers'))?>
<table class=tableau>
<tr><th colspan=7>filles</th><th colspan=7>gars</th></tr>
<? $k=1; $l=$party->maxPeople + 1; ?>
<? for($i=0;$i<$party->maxPeople;$i++) {?>
<tr>
	<td><?=$i+1?></td>
	<? if (count($ladies_inscrit)>0) { 
		$lady=array_shift($ladies_inscrit);
		?>
		<td><?=t($lady->firstname)?></td>
		<td><?=t($lady->lastname)?></td>
		<td><?=t($lady->telephone)?></td>
		<td><?=t($lady->email, 15)?></td>
		<td><?=t($lady->statut)?></td>
		<td><input type=text name=updatePeopleGivenNumber_<?=$lady->id?> value="<?=($lady->givenNumber!=0?$lady->givenNumber:"")?>" tabindex=<?=$k++?> size=4></td>
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
		<td><?=t($lady->email, 15)?></td>
		<td><?=t($lady->statut)?></td>
		<td><input type=text name=updatePeopleGivenNumber_<?=$lady->id?> value="<?=($lady->givenNumber!=0?$lady->givenNumber:"")?>" tabindex=<?=$l++?> size=4></td>
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
<tr><td colspan=14><input TYPE="SUBMIT" VALUE="Mettre à jour" SIZE="15" class="Bouton"></td></tr>
</table>
<br>

<br>
</form>