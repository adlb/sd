<?=displayMessage()?> 
<?
switch ($guest->statut) {
    case 'Attente':
        $bgcolor = '#F0E68C';
        break;
    case 'Relancé':
        $bgcolor = '#FF8C00';
        break;
    case 'Validé':
        $bgcolor = '#7CFC00';
        break;
    case 'Non validé':
        $bgcolor = '#9ACD32';
        break;
    case 'Supprimé':
        $bgcolor = '#F08080';
        break;
    default:
        $bgcolor = '#101010';
        break;
}
?>

<table style="width:800px; margin:0;table-layout: fixed;border-width:0px 0px 0px 0px; border-style:solid; border-color:black;border-collapse:collapse;padding:0px;background-color:<?=$bgcolor?>;">
<? // y'a un padding de 2... donc 24 px ?>
<td width=38px><?=t($guest->id)?></td>
<td style="width:200px;overflow: hidden;white-space: nowrap; "><?=t($guest->firstname.' '.$guest->lastname)?></td>
<td style="width:21px;overflow: hidden;white-space: nowrap; "><?=t($guest->get_nb_participations())?></td>
<td style="width:46px;overflow: hidden;white-space: nowrap; "><?=t($guest->birthDate)?></td>
<td style="width:175px;overflow: hidden;white-space: nowrap; "><?=t($guest->email, 40)?></td>
<td style="width:150px;overflow: hidden;white-space: nowrap; "><?=t($guest->telephone)?></td>
<td width=100px><?=t($guest->statut)?></td>
<td width=38px><img src='ressources/moins.png' width=13px style="cursor:pointer;" onclick="submitForm('?<?=get_url_link($guest, array('view'=>'viewsmall'))?>','gens_<?=$guest->id?>');"></td>
</table>

<table style="width:800px; margin:0;table-layout:fixed;background-color:AAFFFF;border-width:0px 0px 1px 0px; border-style:solid; border-color:black;">
<tr><td width=100px>Prénom :</td><td><?=t($guest->firstname)?></td><td rowspan=5 width=400px>
    <h2 style="border-bottom:1px solid black;">Actions</h2>
    <? function linkstate($state) {
        Global $guest;
        return displayLinkAjax($state, $guest, array('action' => 'changestatut', 'newstatut' => $state), 'gens_'.$guest->id);
    }
    ?>
    Modifier le statut à : <?=linkstate('Non validé')?>, <?=linkstate('Validé')?>,
    <?=linkstate('Supprimé')?>, <?=linkstate('Attente')?>, <?=linkstate('Relancé')?>.
    Disponibilité : <?=displayLinkAjax('Relancer', $guest, array('action' => 'relancerPlaceDispo'), 'gens_'.$guest->id)?>, 
					<?=displayLinkAjax('Annuler Relance', $guest, array('action' => 'annulerRelance'), 'gens_'.$guest->id)?>.<br/>
    Envoyer :       <?=displayLinkAjax('Confirmation', $guest, array('action' => 'envoyerConfirmation'), 'gens_'.$guest->id)?>, 
                    <?=displayLinkAjax('Mise en attente', $guest, array('action' => 'envoyerMessageDattente'), 'gens_'.$guest->id)?>.<br/>
    <?=displayLinkImage('edit.png', $guest, array('view' => 'edit'))?> 
    <?=displayLinkImageAjax('delete.png', $guest, array('action' => 'delete'), 'gens_'.$guest->id)?> 
    <?=displayLinkImageAjax('unmail.png', $guest, array('action' => 'supprimeEmail'), 'gens_'.$guest->id)?> 
    <br/>
</td></tr>
<tr><td width=100px>Nom :</td><td><?=t($guest->lastname)?></td></tr>
<tr><td width=100px>Téléphone :</td><td><?=t($guest->telephone)?></td></tr>
<tr><td width=100px>E-Mail :</td><td><?=t($guest->email)?></td></tr>
<tr><td width=100px>Sexe :</td><td><?=t($guest->sexe)?></td></tr>
<tr><td width=100px>Année :</td><td><?=t($guest->birthDate)?></td></tr>
</table>