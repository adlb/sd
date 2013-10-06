<?=displayMessage()?> 
<? if ($party != null) { ?>
    <H1><?=($sexe=='F')?'Filles':'Garçons'?> (<?=$party->get_nb_people($sexe)?>)</H1>
    <p>
    Validé=<?=$party->get_nb_people($sexe, 'Validé')?>/<?=$party->maxPeople?>
     - 
    Non Validé=<?=$party->get_nb_people($sexe, 'Non validé')?>
     - 
    Attente=<?=$party->get_nb_people($sexe, 'Attente')?>
     - 
    Relancé=<?=$party->get_nb_people($sexe, 'Relancé')?>
     - 
    Supprimé=<?=$party->get_nb_people($sexe, 'Supprimé')?>


    <img src='ressources/refresh.png' width=13px style="cursor:pointer;" 
    onclick="submitForm('?<?=get_url_link($party, array('view'=>'sexeBandeau', 'sexe'=>$sexe))?>','Titre<?=$sexe?>');">
    </p>
<? } else { ?>
<H1><?=($sexe=='F')?'Filles':'Garçons'?></H1>
<? } ?>