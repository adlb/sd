<? include('models/z_tete_admin.v'); ?>
<?=displayLink('Retour', $guest->get_party())?>

<H1>Modifier l'invité</H1>
<? 
	if (count($guest->errors) > 0) { 
		foreach($guest->errors as $st) {
			echo "$st<br/>";
		}
	}
?>

<?=displayForm($guest, array('action'=>'update'))?>
<TABLE border=1>
	<?=$guest->displayUpdateFormLines($guest->fields_in_form)?>
	<tr><td colspan=2><input TYPE="SUBMIT" VALUE="Mettre à jour" SIZE="15"></tr>
</table>
</form>
