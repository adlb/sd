<? include('models/z_tete_admin.v'); ?>

<?=displayLink('Retour', 'parties')?>

<H1>Créer une nouvelle soirée</H1>

<? 
	if (count($party->errors) > 0) { 
		foreach($party->errors as $st) {
			echo "$st<br/>";
		}
	}
?>

<?=displayForm($party, array('action'=>'create'))?>
	<TABLE class=tableau>
		<?=$party->displayNewFormLines()?>
		<tr><td colspan=2><input TYPE="SUBMIT" VALUE="Créer" SIZE="15"></tr>
	</table>
</form>
<br>
<br>
<H1>Tableau des images et formulaire de chargement</H1>
<?
$dirname = './images/';
$dir = opendir($dirname); 
?>
<table class=tableau><tr><th colspan=2>Fichiers images</th></tr>
	<?
	while($file = readdir($dir)) {
		if($file != '.' && $file != '..' && !is_dir($dirname.$file) && (strcasecmp(substr($file,-5,5), ".jpeg")==0 || strcasecmp(substr($file,-4,4), ".png")==0 || strcasecmp(substr($file,-4,4), ".jpg")==0)) {
			echo '<tr><td>'.$file.'</td><td><img src=images/'.$file.' height=40/></td></tr>';
		}
	}
	?>
	<tr><td colspan=2>
		<form method="post" action="?obj=party&action=upload" enctype="multipart/form-data">    
				  <input type="hidden" name="MAX_FILE_SIZE" value="2097152">    
				  <input type="file" name="nom_du_fichier">   
				  <input type="submit" value="Envoyer">   
		</form>
	</td></tr>
</table>
<?
closedir($dir);
?>
