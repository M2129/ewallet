<?php
// Validation du numéro de téléphone sénégalais
function validerTelephone($telephone){
    $prefixesValides = ['77','78','76','70','75'];
    if(strlen($telephone) != 9) return 'invalide';
    if(!ctype_digit($telephone)) return 'invalide';
    $debut = substr($telephone, 0, 2);
    if(!in_array($debut, $prefixesValides)) return 'invalide';
    return 'valide';
}

// Le code secret doit avoir exactement 4 chiffres
function validerCode($code){
    if(strlen($code) != 4) return 'invalide';
    return 'valide';
}

// Le montant doit être strictement positif
function validerMontant($montant){
    if($montant <= 0) return 'invalide';
    return 'valide';
}

// Le solde initial ne peut pas être négatif
function validerSolde($solde){
    if($solde < 0) return 'invalide';
    return 'valide';
}
