<?php
namespace EWallet\Repository;

// Cherche un wallet par téléphone — retourne l'index ou -1
// Utilise array_filter + array_keys à la place d'une boucle manuelle
function trouverIndex($wallets, $telephone){
    $resultat = array_keys(
        array_filter($wallets, fn($w) => $w['telephone'] === $telephone)
    );
    return empty($resultat) ? -1 : $resultat[0];
}

// Vérifie si un numéro de téléphone est déjà utilisé
function telephoneExiste($wallets, $telephone){
    $trouve = array_filter($wallets, fn($w) => $w['telephone'] === $telephone);
    return !empty($trouve);
}

// Vérifie si un code secret est déjà utilisé
function codeExiste($wallets, $code){
    $trouve = array_filter($wallets, fn($w) => $w['code'] === $code);
    return !empty($trouve);
}

// Récupère les transactions d'un client précis via array_filter
function filtrerTransactions($transactions, $indexClient){
    return array_filter(
        $transactions,
        fn($t) => $t['indexClient'] === $indexClient
    );
}

// Ajoute un wallet (passage par référence)
function ajouterWallet(&$wallets, $newWallet){
    $wallets[] = $newWallet;
}

// Met à jour le solde d'un wallet (passage par référence)
function mettreAJourSolde(&$wallets, $index, $nouveauSolde){
    $wallets[$index]['solde'] = $nouveauSolde;
}

// Enregistre une transaction (passage par référence)
function ajouterTransaction(&$transactions, $transaction){
    $transactions[] = $transaction;
}