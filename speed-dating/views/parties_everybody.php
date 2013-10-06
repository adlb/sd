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

<H1>Toutes les soirées&nbsp;&nbsp;&nbsp;</H1><br/>

<P>
<?=displayLink('Actualiser', 'parties', array('view' => 'everybody'))?> 
<?=displayLink('Ajouter une personne', 'guest', array('view' => 'new'))?> 
</p>

<div style="margin:auto;width:800px;background-color:#DDFFDD;">
<? $gens = $ladies; ?>
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
<? $gens = $gentlemen; ?>
<? for($i=0;$i<count($gens);$i++) { ?>
    <div id="gens_<?=$gens[$i]->id?>">
        <? $guest = $gens[$i]; include('views/guest_viewsmall.php'); ?>
    </div>
<? } ?>
</div>

<br><br>