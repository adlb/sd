<? include 'models/z_tete_admin.v'; 
global $parties, $to, $mail, $subject;?>

<?=displayLink('Retour', 'parties')?> 

<?=displayForm('mail', array('action'=>'update'))?>
    <style type="text/css">
		/* Undo some styles from the master stylesheet */
		.checklist li { background: none; padding-left: 0; text-align: left;}
		
		/* CSS for checklists */
		.checklist {
			border: 1px solid #ccc;
			list-style: none;
			height: 10em;
			overflow: auto;
			width: 16em;
		}
		.checklist, .checklist li { margin-left: 0; padding: 0; }
		.checklist label { display: block; padding-left: 25px; text-indent: -25px; }
		.checklist label:hover, .checklist label.hover { background: #777; color: #fff; }
		* html .checklist label { height: 1%; }
		
		/* Checklist 1 */
		.cl1 { font-size: 0.9em; width: 100%; height: 7em; }
		.cl1 .alt { background: #f5f5f5; }
		.cl1 input { vertical-align: middle; }
		.cl1 label:hover, .cl1 label.hover { background: #ddd; color: #000; }
		
		/* Checklist 2 */
		.cl2 {
			background: #67893d;
			color: #D1DCC5;
			font-family: Tahoma, Geneva, Arial, sans-serif;
			width: 50%;
		}
		.cl2 input { vertical-align: middle; }
		.cl2 label { border-bottom: 1px solid #769550; padding: 0.2em 0.2em 0.2em 25px; }
		.cl2 label:hover, .cl2 label.hover { background: #306B34; color: #fff; }
		
		/* Checklist 3 */
		.cl3 {
			border: 1px dotted #a17c04;
			color: #a05a04;
			font-family: "Trebuchet MS", Tahoma, Geneva, Arial, sans-serif;
			font-size: 0.9em;
			height: 19em;
		}
		.cl3 .alt { background: #f8f6ed; }
		.cl3 label { padding: 0.2em 0.2em 0.2em 25px; }
		.cl3 label:hover, .cl3 label.hover { background: #EFE9D4; color: #a05a04; }
	</style>
    <!-- JavaScript -->
	<script type="text/javascript">
		/*-----------------------------------------------------------+
		 | addLoadEvent: Add event handler to body when window loads |
		 +-----------------------------------------------------------*/
		function addLoadEvent(func) {
			var oldonload = window.onload;
			
			if (typeof window.onload != "function") {
				window.onload = func;
			} else {
				window.onload = function () {
					oldonload();
					func();
				}
			}
		}
		
		/*------------------------------------+
		 | Functions to run when window loads |
		 +------------------------------------*/
		addLoadEvent(function () {
			initChecklist();
		});
		
		/*----------------------------------------------------------+
		 | initChecklist: Add :hover functionality on labels for IE |
		 +----------------------------------------------------------*/
		function initChecklist() {
			if (document.all && document.getElementById) {
				// Get all unordered lists
				var lists = document.getElementsByTagName("ul");
				
				for (i = 0; i < lists.length; i++) {
					var theList = lists[i];
					
					// Only work with those having the class "checklist"
					if (theList.className.indexOf("checklist") > -1) {
						var labels = theList.getElementsByTagName("label");
						
						// Assign event handlers to labels within
						for (var j = 0; j < labels.length; j++) {
							var theLabel = labels[j];
							theLabel.onmouseover = function() { this.className += " hover"; };
							theLabel.onmouseout = function() { this.className = this.className.replace(" hover", ""); };
						}
					}
				}
			}
		}
	</script>
<table class=tableau style="width:800px; margin:auto;table-layout: fixed;">
    <tr><th colspan=4>Selection des destinataires</th></tr>
</table>
<table class=tableau style="width:800px; margin:auto;table-layout: fixed;">
    <tr><th width=100px>&nbsp;</th><th>Soirées</th><th width=200px>Sexe</th><th width=200px>Statut</th></tr>
    <tr><td>Include</td>
    <td>
        <ul class="checklist cl1">
        <?
            $i=0;
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "select_dest_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"All\"/>Toutes</label></li>";
            foreach($parties as $party) {
                echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
                $n = "select_dest_".($i++);
                echo "<label for=\"$n\">";
                echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"".$party->id."\"/>".t($party->name).' ('.date('d/m/y', $party->date).')'."</label></li>";
            }
        ?>
        </ul>
        <input type="hidden" name="select_dest_size" value=<?=$i?>>
    </td>
    <td>
        <ul class="checklist cl1">
        <?
            $i=0;
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "select_sexe_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"All\"/>H et F</label></li>";
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "select_sexe_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"H\"/>Hommes</label></li>";
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "select_sexe_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"F\"/>Femmes</label></li>";
        ?>
        </ul>
        <input type="hidden" name="select_sexe_size" value=<?=$i?>>        
    </td>
    <td>
        <ul class="checklist cl1">
        <?
            $i=0;
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "select_statut_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"All\"/>Tous</label></li>";
            $statuts = array('Supprimé', 'Validé', 'Non validé', 'Attente', 'Relancé');
            foreach($statuts as $statut) {
                echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
                $n = "select_statut_".($i++);
                echo "<label for=\"$n\">";
                echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"".$statut."\"/>".t($statut)."</label></li>";
            }
        ?>
        </ul>
        <input type="hidden" name="select_statut_size" value=<?=$i?>>     
    </td></tr>
    <tr><td>Exclude</td>
    <td>
        <ul class="checklist cl1">
        <?
            $i=0;
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "exclude_dest_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"All\"/>Toutes</label></li>";
            foreach($parties as $party) {
                echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
                $n = "exclude_dest_".($i++);
                echo "<label for=\"$n\">";
                echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"".$party->id."\"/>".t($party->name).' ('.date('d/m/y', $party->date).')'."</label></li>";
            }
        ?>
        </ul>
        <input type="hidden" name="exclude_dest_size" value=<?=$i?>>     
    </td>
    <td>
        <ul class="checklist cl1">
        <?
            $i=0;
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "exclude_sexe_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"All\"/>H et F</label></li>";
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "exclude_sexe_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"H\"/>Hommes</label></li>";
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "exclude_sexe_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"F\"/>Femmes</label></li>";
        ?>
        </ul> 
        <input type="hidden" name="exclude_sexe_size" value=<?=$i?>>
    </td>
    <td>
        <ul class="checklist cl1">
        <?
            $i=0;
            echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
            $n = "exclude_statut_".($i++);
            echo "<label for=\"$n\">";
            echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"All\"/>Tous</label></li>";
            $statuts = array('Supprimé', 'Validé', 'Non validé', 'Attente', 'Relancé');
            foreach($statuts as $statut) {
                echo "<li".(($i%2 == 0)?" class=\"alt\"":"").">";
                $n = "exclude_statut_".($i++);
                echo "<label for=\"$n\">";
                echo "<input id=\"$n\" name=\"$n\" type=\"checkbox\" value=\"".$statut."\"/>".t($statut)."</label></li>";
            }
        ?>
        </ul> 
        <input type="hidden" name="exclude_statut_size" value=<?=$i?>>
    </td></tr>
    <tr><td colspan=4><INPUT TYPE=SUBMIT NAME="send_value" VALUE="Update les destinataires"> (<?=$nbAdd?> Adresses)<br/><TEXTAREA style="width:750px;height:100px;margin:auto;"><?=t($to)?></textarea></td></tr>
</table>


<br/>
<br/>
<table class=tableau style="width:800px; margin:auto;table-layout: fixed;">
    <tr><th>Textes</th></tr>
    <tr><td>Choisir texte :
							<SELECT NAME="textSelect">
								<OPTION VALUE="rien" > [pas de mise &agrave; jour]
								<OPTION VALUE="email_suite_et_commencement" >email_suite_et_commencement
								<OPTION VALUE="email_relance_mecs" >email_relance_mecs
								<OPTION VALUE="email_relance_last_call" >email_relance_last_call
								<OPTION VALUE="email_invitation_avant_premiere" >email_invitation_avant_premiere
								<OPTION VALUE="email_dernier_mail_avant_soiree" >email_dernier_mail_avant_soiree
								<OPTION VALUE="email_suite_demande_renvoi_pastrouve" >email_suite_demande_renvoi_pastrouve
								<OPTION VALUE="email_suite_demande_renvoi_guestNonValide">email_suite_demande_renvoi_guestNonValide
								<OPTION VALUE="email_suite_demande_renvoi_guestValide" >email_suite_demande_renvoi_guestValide
								<OPTION VALUE="email_suite_demande_renvoi_guestAttente" >email_suite_demande_renvoi_guestAttente
								<OPTION VALUE="email_suite_demande_renvoi_guestSupprime" >email_suite_demande_renvoi_guestSupprime
								<OPTION VALUE="email_suite_demande_renvoi_guestRelance" >email_suite_demande_renvoi_guestRelance
								<OPTION VALUE="email_suite_enregistrement_guestNonValide" >email_suite_enregistrement_guestNonValide
								<OPTION VALUE="email_suite_relance_admin_pour_valider" >email_suite_relance_admin_pour_valider
								<OPTION VALUE="email_suite_liberation_de_place" >email_suite_liberation_de_place
								<OPTION VALUE="email_suite_annule_relance_by_admin" >email_suite_annule_relance_by_admin
								<OPTION VALUE="email_suite_validation_par_utilisateur" >email_suite_validation_par_utilisateur
								<OPTION VALUE="email_suite_validation_par_utilisateur_Attente" >email_suite_validation_par_utilisateur_Attente
							</SELECT>
		</td></tr>
    <tr><td><INPUT TYPE=SUBMIT NAME="send_value" VALUE="Update texte"><br/>
    <TEXTAREA style="width:750px;height:100px;margin:auto;"><?=t($mail)?></textarea></td></tr>
</table>
</form>