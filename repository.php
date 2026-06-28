<?php
// Cherche un wallet par téléphone, retourne l'index ou -1 si pas trouvé
function trouverWallet($wallets, $telephone){
    foreach($wallets as $index => $wallet){
        if($wallet['telephone'] == $telephone) return $index;
    }
    return -1;
}

// Vérifie si un numéro de téléphone est déjà utilisé
function telephoneExiste($wallets, $telephone){
    return trouverWallet($wallets, $telephone) !== -1;
}

// Vérifie si un code secret est déjà utilisé
function codeExiste($wallets, $code){
    foreach($wallets as $wallet){
        if($wallet['code'] == $code) return true;
    }
    return false;
}

// Ajoute un nouveau wallet (passage par référence pour modifier le tableau)
function ajouterWallet(&$wallets, $newWallet){
    $wallets[] = $newWallet;
}

// Met à jour le solde d'un wallet
function mettreAJourSolde(&$wallets, $index, $nouveauSolde){
    $wallets[$index]['solde'] = $nouveauSolde;
}

// Enregistre une transaction
function ajouterTransaction(&$transactions, $transaction){
    $transactions[] = $transaction;
}
