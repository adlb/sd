<? include('models/z_tete_admin.v'); ?>

<?=displayLink('Retour', 'parties')?>

<br/>
<H1>Modifier la Soirée</H1>
<? 
	if (count($party->errors) > 0) { 
		foreach($party->errors as $st) {
			echo "$st<br/>";
		}
	}
?>

<?=displayForm($party, array('action'=>'update'))?>
<TABLE border=1>
	<?=$party->displayUpdateFormLines()?>
	<tr><td colspan=2><input TYPE="SUBMIT" VALUE="Mettre à jour" SIZE="15"></tr>
</table>
</form>
