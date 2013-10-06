<? include('models/z_tete_admin.v'); ?>

<?=displayLink('Logout', 'login', array('action'=>'logout'))?> 
<?=displayLink('Nouvelle Soirée', 'party', array('view'=>'new'))?> 
<?=displayLink('Assistant Email', 'mail')?> 
<?=displayLink('Générer Table de Rotation', 'otherSheets', array('view' => 'rotation', 'nbTables'=>($nextParty==null)?0:$nextParty->maxPeople, 'nbRotations'=>10))?> 
<a class=bouton href=eqs.csv>Sondage</a>
<br/><br/>

<H1>Prochaine Soirée : </H1>
	<p><?=($nextParty==null)?"Aucune":$nextParty->name." ".displayLink('Show', $nextParty)?> 
	</p>
	<br/><br/>
<H1>Toutes les soirées : </H1>
	<? if (count($parties)>0) { ?>
		<TABLE class=tableau>
			<TR><TH>Id</TH><TH>Nom</TH><TH>Date</TH><TH>Ouverture réservation</TH><TH>Nombre Max</TH><TH>Active ?</TH><TH>nb Ladies</TH><TH>nb Gentlemen</TH><TH>Actions</TH></tr>
			<? foreach($parties as $party) { ?>
				<tr>
					<td><?=$party->id?></td>
					<td><?=$party->name?></td>
					<td><?=date('d/m/y', $party->date)?></td>
					<td><?=date('d/m/y', $party->dateOpen)?></td>
					<td><?=$party->maxPeople?></td>
					<td><?=(($party->active)? 'Yes' : '-')?></td>
					<td><?=$party->get_nb_people('F', array('Validé', 'Non validé', 'Attente', 'Relancé'))?></td>
					<td><?=$party->get_nb_people('H', array('Validé', 'Non validé', 'Attente', 'Relancé'))?></td>
					<td>	<?=displayLinkImage('edit.png', $party, array('view'=>'edit'))?> 
							<?=displayLinkImage('show.png', $party)?> 
							<?=displayLinkImage('delete.png', $party, array('action'=>'delete'))?> 
				</tr>
			<?	} ?>
		</TABLE>
	<? } else { ?> 
		<p>Pas de parties !!!</p>
	<? } ?>
        <p><?=displayLink('Voir tout le monde', 'parties', array('view'=>'everybody'))?> </p>
<br/>
<br/>
<? //=displayLink('!!! EFFACER TOUTE LA BASE !!!', 'parties', array('action'=>'eraseALL'))?> 
<? //=displayLink('!!! EFFACER TOUTE LA BASE ET CHARGER BASE INITIALE !!!', 'parties', array('action'=>'eraseALLandLOAD'))?>