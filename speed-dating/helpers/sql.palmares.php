<?
/**
##########################
# Calcul le nombre de participation des gens pr�sents � la derni�re soir�e
# Change 'F' en 'H' pour les hommes. (2 changes)
######################


SELECT t1.firstname, t1.lastname, t1.email, t2.nb as nbParticipations FROM
(SELECT *
FROM `sd_guests`
WHERE statut = 'Valid�'
AND sexe = 'F'
AND partyId =14
) as t1 INNER JOIN (
SELECT `sd_guests`.email, `sd_guests`.sexe, count( `sd_guests`.partyId ) as nb
FROM `sd_guests`
WHERE statut = 'Valid�'
AND sexe = 'F'
GROUP BY email
) as t2 ON
t1.email = t2.email
ORDER BY t2.nb DESC

*/
?>