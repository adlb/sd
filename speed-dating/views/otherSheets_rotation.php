<? include('models/z_tete_admin.v'); ?>
<?=displayLink('Retour', 'parties')?>

<table class='tableau'>
<Form ACTION="?obj=otherSheets&view=rotation" METHOD="GET" ENCTYPE="application/x-www-form-urlencoded" name="aaa">
	<input type=hidden name=obj value=otherSheets>
	<input type=hidden name=view value=rotation>
	<tr><td>Nb Tables=</td><td><input type=text name=nbTables value="<?=$nbTables?>"/></td><td rowspan=2><input TYPE="SUBMIT" VALUE="(re)Générer Table" SIZE="15"></td></tr>
	<tr><td>NbRotation=</td><td><input type=text name=nbRotations value="<?=$nbRotations?>"/></td></tr>

</form>
<Form ACTION="?obj=otherSheets&view=rotation" METHOD="GET" ENCTYPE="application/x-www-form-urlencoded" name="sss">
	<input type=hidden name=obj value=otherSheets>
	<input type=hidden name=view value=rotation>
	<tr><td>Récupére table rotation de : </td><td>
							<SELECT NAME="tableRotation">
								<OPTION VALUE="rien" > [pas de mise sélection]
								<? $parties = recordset_get_all('party'); 
									foreach($parties as $party) {
										echo "<OPTION VALUE=\"".$party->tableRotation."\" >".$party->name.date(" (d/m/y)",$party->date)."</OPTION>";
									}
								?>
							</SELECT></td>
	<td><input TYPE="SUBMIT" VALUE="Récupère" SIZE="15"></td></tr>
</form>
<Form ACTION="?obj=otherSheets&action=sauveRotation" METHOD="POST" ENCTYPE="application/x-www-form-urlencoded" name="ddd">
	<tr><td>Enregistre dans la soirée :</td><td>
							<SELECT NAME="idParty">
								<OPTION VALUE="rien" > [pas de mise sélection]
								<? $parties = recordset_get_all('party'); 
									foreach($parties as $party) {
										echo "<OPTION VALUE=\"".$party->id."\" >".$party->name.date(" (d/m/y)",$party->date)."</OPTION>";
									}
								?>
							</SELECT></td>
							<input type=hidden name=tableRotation value="<?=$nbTables."-".$nbRotations."-".$jumpG."-".$jumpT."-".$startG."-".$startT?>">
	<td><input TYPE="SUBMIT" VALUE="Sauve" SIZE="15"></td></tr>					
</form>
</table>
<br/>
<br/>

<?	if ($rotationOk) {
		echo '<p>';
		echo displayLink('Version PDF', 'otherSheets', array(
			'view' 			=> 'rotationPdf',
			'nbTables'		=> $nbTables,
			'nbRotations'	=> $nbRotations,
			'jumpG'			=> $jumpG,
			'jumpT'			=> $jumpT,
			'startG'		=> $startG,
			'startT'		=> $startT)).' ';
		
		echo displayLink('Version Excel', 'otherSheets', array(
			'view' 			=> 'rotationExcel',
			'nbTables'		=> $nbTables,
			'nbRotations'	=> $nbRotations,
			'jumpG'			=> $jumpG,
			'jumpT'			=> $jumpT,
			'startG'		=> $startG,
			'startT'		=> $startT));
			
		echo '</p>';
		echo "<table class=tableau><tr>";
			for($i=0;$i<$nbRotations;$i++) {echo "<th colspan=3>".($i+1)."</th>";}
		echo "</tr><tr>";	
			for($i=0;$i<$nbRotations;$i++) {echo "<th>L</th><th>G</th><th>T</th>";}
		echo "</tr>";
		
		for($i=0;$i<$nbTables;$i++){
			echo '<tr>';
			for($j=0;$j<$nbRotations;$j++) {
				echo '<td>'.($i+1).'</td>';
				echo '<td>'."".((($startG+$jumpG*$j+$i)%$nbTables)+$nbTables+1).'</td>';
				echo '<td>'."".((($startT+$jumpT*$j+$i)%$nbTables)+1).'</td>';
			}
			echo '</tr>';
		}
		echo "</table>";
		
	} else {
		echo "Je ne trouve pas de solution convenable...";
	}
?>