<? include 'models/z_tete.v'; ?>
<?=displayMessage()?>

<div id=eqs>
	<H1>Questionnaire Rapide</H1> 
	<br/>
	<Form ACTION=?obj=sd&action=eqssend METHOD="POST" ENCTYPE="application/x-www-form-urlencoded" name="adminlogin">
			<TABLE cellpadding="4">
				<tr><th colspan=2>Avis Général</th></tr>
				<tr><td class="underlined">êtes-vous ?</td><td class="underlined">
							<label><input id="id_civilite-1" type="radio" value="H" name="sexe" class="radio">un homme</label>
							<label><input id="id_civilite-1" type="radio" value="F" name="sexe" class="radio">une femme</label>
				</td></tr>
				<tr><td class="underlined">Quelle est votre année de naissance ?</td><td class="underlined">
							<input  class="eqs" TYPE="TEXT" NAME="annee_de_naissance" SIZE="30">
				</td></tr>
				<tr><td class="underlined">combien de fois êtes-vous venu ?</td><td class="underlined">
							<SELECT class="eqs" name="nb_venu_au_speeddating" size="1" width=20>
								<OPTION value=0>Jamais
								<OPTION value=1>1 fois
								<OPTION value=2>2 fois
								<OPTION value=3>3 fois ou plus
							</SELECT>
				</td></tr>
				<tr><td class="underlined">Comment avez-vous été informé(e) de ces soirées ?</td><td class="underlined">
							<label><input type="radio" value="amis celib" name="informe_par" class="radio">Amis célibataires</label><br/>
							<label><input type="radio" value="amis coupl" name="informe_par" class="radio">Amis en couple</label><br/>
							<label><input type="radio" value="famille" name="informe_par" class="radio">Famille</label><br/>
							<label><input type="radio" value="autre" name="informe_par" class="radio">Autre : </label><input TYPE="TEXT" NAME="informe_par_autre" SIZE="30">
							
				</td></tr>
				<tr><td class="underlined">Passez-vous de bonnes soirées au speed-dating ?</td><td class="underlined">
							<label><input type="radio" value="pas du tout" name="bonne_soiree" class="radio">pas du tout</label><br/>
							<label><input type="radio" value="pas trop" name="bonne_soiree" class="radio">pas trop</label><br/>
							<label><input type="radio" value="oui" name="bonne_soiree" class="radio">oui</label><br/>
							<label><input type="radio" value="définitivement oui" name="bonne_soiree" class="radio">définitivement oui</label><br/>
				</td></tr>
				<tr><td class="underlined">Quels sont les points forts des soirées ?</td><td class="underlined">
							<TEXTAREA NAME="points_forts" ROWS="1" COLS="40"></textarea>
				</td></tr>
				<tr><td class="underlined">Quels sont les points faibles des soirées ?</td><td class="underlined">
							<TEXTAREA NAME="points_faibles" ROWS="1" COLS="40"></textarea>
				</td></tr>
				<tr><td class="underlined">Le nombre de rencontre vous semble-t-il ?</td><td class="underlined">
							<label><input type="radio" value="insuffisant" name="nb_rencontre" class="radio">insuffisant</label><br/>
							<label><input type="radio" value="suffisant" name="nb_rencontre" class="radio">suffisant</label><br/>
							<label><input type="radio" value="trop" name="nb_rencontre" class="radio">trop</label><br/>
				</td></tr>
				<tr><td>La durée de chaque rencontre vous semble-t-elle ?</td><td>
							<label><input type="radio" value="insuffisant" name="duree_rencontre" class="radio">insuffisante</label><br/>
							<label><input type="radio" value="suffisant" name="duree_rencontre" class="radio">suffisante</label><br/>
							<label><input type="radio" value="trop" name="duree_rencontre" class="radio">trop</label><br/>
				</td></tr>
				<tr><th colspan=2>Après le speed-dating</th></tr>
				<tr><td class="underlined">combien de personnes avez-vous contactées ?</td><td class="underlined">
							<SELECT class="eqs" name="nb_concatees" size="1" width=20>
								<OPTION value=0>zéro
								<OPTION value=1>1 personne
								<OPTION value=2>2 personnes
								<OPTION value=3>3 personnes ou plus
							</SELECT>
				</td></tr>
				<tr><td class="underlined">combien de personnes vous ont contacté ?</td><td class="underlined">
							<SELECT class="eqs" name="nb_contactant" size="1" width=20>
								<OPTION value=0>zéro
								<OPTION value=1>1 personne
								<OPTION value=2>2 personnes
								<OPTION value=3>3 personnes ou plus
							</SELECT>
				</td></tr>
				<tr><td class="underlined">cela a-t-il abouti sur une relation ?</td><td class="underlined">
							<SELECT class="eqs" name="type_relation" size="1" width=20>
								<OPTION value=0>non
								<OPTION value=1>Oui, une relation amicale
								<OPTION value=2>Oui, une relation affective
								<OPTION value=3>Les deux
							</SELECT>
				</td></tr>
				<tr><td class="underlined">un commentaire sur les relations créées à l'occasion des soirées ? (qualité, durée, ...)</td><td class="underlined">
							<TEXTAREA NAME="commentaire_relations" ROWS="1" COLS="40"></textarea>
				</td></tr>
				<tr><td>êtes-vous toujours en couple ?</td><td>
							<label><input id="id_civilite-1" type="radio" value="YES" name="encore_en_couple" class="radio">Oui</label>
							<label><input id="id_civilite-1" type="radio" value="NO" name="encore_en_couple" class="radio">Non</label>
				</td></tr>
				<tr><th colspan=2>Pour faire évoluer le speed-dating</th></tr>
				<tr><td class="underlined"colspan = 2>
					Toutes les idées sont les bienvenues...<br/>
					<TEXTAREA NAME="faire_evoluer_speeddating" ROWS="3" COLS="80"></textarea>
				</td></tr>
				
				<tr><td class="underlined">e-Mail (facultatif)</td><td class="underlined">
							<input  class="eqs" TYPE="TEXT" NAME="email" SIZE="30" VALUE="<?=$guest->email?>" tabindex=5>
				</td></tr>
				<tr><td colspan=2 align=right><input TYPE="SUBMIT" VALUE="Envoyer" SIZE="15" class='validez' tabindex=6>
				</tr>
			</table>
	</form>

</div>

</div>
</body>
</html>