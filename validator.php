<?php
namespace EWallet\Validator;

// Vérifie le format du numéro sénégalais (9 chiffres, préfixe valide)
function validerTelephone($telephone){
    $prefixesValides = ['77','78','76','70','75'];
    if(strlen($telephone) != 9)              return 'invalide';
    if(!ctype_digit($telephone))             return 'invalide';
    if(!in_array(substr($telephone,0,2), $prefixesValides)) return 'invalide';
    return 'valide';
}

// Vérifie que le code secret fait exactement 4 chiffres
function validerCode($code){
    if(strlen($code) != 4)   return 'invalide';
    if(!ctype_digit($code))  return 'invalide';
    return 'valide';
}

// Vérifie que le montant est strictement positif
function validerMontant($montant){
    if($montant <= 0) return 'invalide';
    return 'valide';
}

// Vérifie que le solde initial est positif ou nul
function validerSolde($solde){
    if($solde < 0) return 'invalide';
    return 'valide';
}