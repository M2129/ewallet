<?php
namespace EWallet\Service;

use function EWallet\Validator\{validerTelephone, validerCode, validerSolde, validerMontant};
use function EWallet\Repository\{telephoneExiste, codeExiste, trouverIndex,
                                   ajouterWallet, mettreAJourSolde, ajouterTransaction};

// Calcul des frais de retrait par palier
// 0 - 10 000 CFA      → 200 CFA fixe
// 10 001 - 100 000    → 500 CFA fixe
// > 100 000           → 1% du montant, plafonné à 5 000 CFA
function calculerFrais($montant){
    if($montant <= 10000)  return 200;
    if($montant <= 100000) return 500;
    $frais = $montant * 0.01;
    return $frais > 5000 ? 5000 : $frais;
}

// Crée un wallet après avoir vérifié toutes les règles métier
function creerWallet(&$wallets, $newWallet){
    if(empty($newWallet['client']))                         return "Erreur : le nom du client est obligatoire.";
    if(validerTelephone($newWallet['telephone']) == 'invalide') return "Erreur : numéro invalide (9 chiffres, préfixe 77/78/76/70/75).";
    if(telephoneExiste($wallets, $newWallet['telephone']))  return "Erreur : ce numéro est déjà utilisé.";
    if(validerCode($newWallet['code']) == 'invalide')       return "Erreur : le code doit avoir exactement 4 chiffres.";
    if(codeExiste($wallets, $newWallet['code']))            return "Erreur : ce code est déjà utilisé.";
    if(validerSolde($newWallet['solde']) == 'invalide')     return "Erreur : le solde initial ne peut pas être négatif.";

    ajouterWallet($wallets, $newWallet);
    return "✔ Wallet créé avec succès pour " . $newWallet['client'] . " !";
}

// Effectue un dépôt sur un wallet existant
function effectuerDepot(&$wallets, &$transactions, $telephone, $montant){
    if(trouverIndex($wallets, $telephone) == -1)  return "Erreur : aucun wallet trouvé pour ce numéro.";
    if(validerMontant($montant) == 'invalide')     return "Erreur : le montant doit être strictement positif.";

    $index        = trouverIndex($wallets, $telephone);
    $nouveauSolde = $wallets[$index]['solde'] + $montant;

    mettreAJourSolde($wallets, $index, $nouveauSolde);
    ajouterTransaction($transactions, [
        'type'        => 'DEPOT',
        'montant'     => $montant,
        'frais'       => 0,
        'indexClient' => $index,
        'date'        => date('d/m/Y H:i:s')
    ]);
    return "✔ Dépôt de " . number_format($montant, 0, ',', ' ') . " CFA effectué."
         . " Nouveau solde : " . number_format($nouveauSolde, 0, ',', ' ') . " CFA";
}

// Effectue un retrait avec calcul des frais par palier
function effectuerRetrait(&$wallets, &$transactions, $telephone, $montant){
    if(trouverIndex($wallets, $telephone) == -1)  return "Erreur : aucun wallet trouvé pour ce numéro.";
    if(validerMontant($montant) == 'invalide')     return "Erreur : le montant doit être strictement positif.";

    $index         = trouverIndex($wallets, $telephone);
    $frais         = calculerFrais($montant);
    $totalADebiter = $montant + $frais;

    if($wallets[$index]['solde'] < $totalADebiter)
        return "Erreur : solde insuffisant. Besoin de "
             . number_format($totalADebiter, 0, ',', ' ') . " CFA (montant + frais de "
             . number_format($frais, 0, ',', ' ') . " CFA).";

    $nouveauSolde = $wallets[$index]['solde'] - $totalADebiter;

    mettreAJourSolde($wallets, $index, $nouveauSolde);
    ajouterTransaction($transactions, [
        'type'        => 'RETRAIT',
        'montant'     => $montant,
        'frais'       => $frais,
        'indexClient' => $index,
        'date'        => date('d/m/Y H:i:s')
    ]);
    return "✔ Retrait de " . number_format($montant, 0, ',', ' ') . " CFA effectué."
         . " Frais : " . number_format($frais, 0, ',', ' ') . " CFA."
         . " Nouveau solde : " . number_format($nouveauSolde, 0, ',', ' ') . " CFA";
}