<? include('models/z_tete_admin.v'); ?>

<?=displayLink('Retour', $guest->get_party())?>

<? 
	if (count($guest->errors) > 0) { 
		foreach($guest->errors as $st) {
			echo "$st<br/>";
		}
	}
?>

<?=displayForm($guest, array('action'=>'create', 'id_party' => $_GET['id_party']))?>
<TABLE border=1>
	<?=$guest->displayNewFormLines($guest->fields_in_form)?>
	<tr><td colspan=2><input TYPE="SUBMIT" VALUE="Créer" SIZE="15"></tr>
</table>
</form>
