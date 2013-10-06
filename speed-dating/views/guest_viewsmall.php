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
<table style="width:800px; margin:0;table-layout: fixed;border-width:0px 0px 1px 0px; border-style:solid; border-color:black;border-collapse:collapse;padding:0px;background-color:<?=$bgcolor?>;">
<? // y'a un padding de 2... donc 24 px ?>
<td width=38px><?=t($guest->id)?></td>
<td style="width:200px;overflow: hidden;white-space: nowrap; "><?=t($guest->firstname.' '.$guest->lastname)?></td>
<td style="width:21px;overflow: hidden;white-space: nowrap; "><?=t($guest->get_nb_participations())?></td>
<td style="width:46px;overflow: hidden;white-space: nowrap; "><?=t($guest->birthDate)?></td>
<td style="width:175px;overflow: hidden;white-space: nowrap; "><?=t($guest->email, 40)?></td>
<td style="width:150px;overflow: hidden;white-space: nowrap; "><?=t($guest->telephone)?></td>
<td width=100px><?=t($guest->statut)?></td>
<td width=38px><img src='ressources/plus.png' width=13px style="cursor:pointer;" onclick="submitForm('?<?=get_url_link($guest)?>','gens_<?=$guest->id?>');""></td>
</table>