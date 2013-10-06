<? include 'models/z_tete.v'; ?>
<div id=helpus>
		<H1>Feed-back</H1> 
		</br>
		Aidez-nous à améliorer les soirées.<br/>
		</br>
		<a href="?view=eqs" class=bouton>Je vous aide !</a>
</div> 
<?
$actualdate = getdate();
$actualdate = $actualdate[0];
if ($nextParty==null) { ?>
	<div id=presentationSoiree>
		<h1 style="margin-left:0;">Pas de nouvelle soir&eacute;e programm&eacute;e !!!</h1>
	</div>
	<?=displayMessage()?>
<? } elseif ($nextParty->dateOpen>$actualdate) { ?>
	<div id=presentationSoiree>
		<a href=index.php><img src=images/<?=$nextParty->image?>></a>
		<h1><?=$nextParty->name?></h1>
		<h2><?=$nextParty->typedesoiree?></h2>
		<h3><?=strftime("%A %d %B %Y",$nextParty->date)?> • <?=$nextParty->heure?></h2>
		<p><b>Adresse : <?=$nextParty->adresse1?></b><br /><?=$nextParty->adresse2?></p>
		<p><b>Info : </b><?=$nextParty->contactInfo?></p>
		<p><?=$nextParty->descriptionFormule?></p>
		<div style="clear:both;"></div>
	</div>
	<?=displayMessage()?>
	<div id=wrap_line>
	<div id=formulaireInscription>
		<H1>Inscription</H1>
		Les incriptions ouvriront le <?=date("d/m/y", $nextParty->dateOpen)?>.
	</div></div>
<? } elseif (!$nextParty->inscriptionOpen) { ?>
	<div id=presentationSoiree>
		<a href=index.php><img src=images/<?=$nextParty->image?>></a>
		<h1><?=$nextParty->name?></h1>
		<h2><?=$nextParty->typedesoiree?></h2>
		<h3><?=strftime("%A %d %B %Y",$nextParty->date)?> • <?=$nextParty->heure?></h2>
		<p><b>Adresse : <?=$nextParty->adresse1?></b><br /><?=$nextParty->adresse2?></p>
		<p><b>Info : </b><?=$nextParty->contactInfo?></p>
		<p><?=$nextParty->descriptionFormule?></p>
		<div style="clear:both;"></div>
	</div>
	<?=displayMessage()?>
	<div id=wrap_line>
	<div id=formulaireInscription>
		<H1>Inscription</H1>
		Les incriptions sont closes
	</div></div>
<? } else { ?>
	<div id=presentationSoiree>
		<a href=index.php><img src=images/<?=$nextParty->image?>></a>
		<h1><?=$nextParty->name?></h1>
		<h2><?=$nextParty->typedesoiree?></h2>
		<h3><?=strftime("%A %d %B %Y",$nextParty->date)?> • <?=$nextParty->heure?></h2>
		<p><b>Adresse : <?=$nextParty->adresse1?></b><br /><?=$nextParty->adresse2?></p>
		<p><b>Info : </b><?=$nextParty->contactInfo?></p>
		<p><?=$nextParty->descriptionFormule?></p>
		<div style="clear:both;"></div>
	</div>
	<?=displayMessage()?>
<div id=wrap_line>
	<div id=formulaireInscription>
		<? if (sd_ismagic($nextParty)) { ?>
			<H1>Inscription</H1>
			<Form ACTION=?obj=sd&id=<?=$nextParty->id?>&action=create METHOD="POST" ENCTYPE="application/x-www-form-urlencoded" name="adminlogin">
			<TABLE>
				<tr><td>Sexe</td><td>
							<? $sh = (($guest->sexe!='F')?' checked="checked"':'');$sf = (($guest->sexe=='F')?' checked="checked"':''); ?>
							<label><input id="id_civilite-1" type="radio" value="H" name="sexe" class="radio" tabindex=1 <?=$sh?>>H</label>
							<label><input id="id_civilite-1" type="radio" value="F" name="sexe" class="radio" tabindex=1 <?=$sf?>>F</label>
				</td><td>Année de naissance</td><td><input TYPE="TEXT" NAME="birthDate" SIZE="30" VALUE="<?=$guest->birthDate?>" tabindex=5></td></tr>	
				<tr><td>Pr&eacute;nom</td><td><input TYPE="TEXT" NAME="firstname" SIZE="30" VALUE="<?=$guest->firstname?>" tabindex=3></td><td>T&eacute;l&eacute;phone</td><td><input TYPE="TEXT" NAME="telephone" SIZE="30" VALUE="<?=$guest->telephone?>"  tabindex=6></td></tr>
				<tr><td>Nom</td><td><input TYPE="TEXT" NAME="lastname" SIZE="30" VALUE="<?=$guest->lastname?>" tabindex=4></td><td>e-Mail</td><td><input TYPE="TEXT" NAME="email" SIZE="30" VALUE="<?=$guest->email?>" tabindex=7></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan=2 align=right><input TYPE="SUBMIT" VALUE="Valider" SIZE="15" class='validez' tabindex=8></tr>
			</table>
			</form>
		<? } else { ?>
			<H1>Inscription</H1>
			<Form ACTION=?obj=sd&id=<?=$nextParty->id?>&action=loglog METHOD="POST" ENCTYPE="application/x-www-form-urlencoded" name="adminlogin">
			<TABLE>
				<tr><td>Sésame : </td><td><input TYPE="TEXT" NAME="magicWord" SIZE="30" VALUE="" tabindex=2></td></tr>
				<tr><td>&nbsp;</td><td align=right><input TYPE="SUBMIT" VALUE="Valider" SIZE="15" class='validez' tabindex=6></td></tr>
			</table>
			</form>
		<? } ?>
	</div>
	
	<div id=suisjeinscrit>
		<H1>Suis-je inscrit ?</H1> 
		</br>
		Un mail vous sera envoy&eacute; avec la r&eacute;ponse.<br/>
		Il permet aussi de confirmer/annuler votre venue.<br/>
		</br>
		<Form ACTION=?obj=sd&id=<?=$nextParty->id?>&action=resend METHOD="POST" ENCTYPE="application/x-www-form-urlencoded" name="suisjeinscrit">
		<table>
		<tr><td>e-Mail</td><td><input TYPE="TEXT" NAME="email" SIZE="30" VALUE=""></td></tr>
		<tr><td>&nbsp;</td><td align=right><input TYPE="SUBMIT" VALUE="Vérifier" SIZE="15" class='validez'></td></tr>
		</table>
		</form>
	</div>
</div>
<? } ?>
</div>
</div>

</body>
</html>