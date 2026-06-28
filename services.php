<?php
// Calcul des frais : 1% du montant, plafonné à 5000 CFA
function calculerFrais($montant){
    $frais = $montant * 0.01;
    if($frais > 5000) $frais = 5000;
    return $frais;
}

// Crée un wallet après validation des règles métier
function creerWallet(&$wallets, $newWallet){
    if(empty($newWallet['client']))                          return "Erreur : le nom du client est obligatoire.";
    if(validerTelephone($newWallet['telephone']) == 'invalide') return "Erreur : numéro de téléphone invalide.";
    if(telephoneExiste($wallets, $newWallet['telephone']))   return "Erreur : ce numéro est déjà utilisé.";
    if(validerCode($newWallet['code']) == 'invalide')        return "Erreur : le code doit avoir 4 chiffres.";
    if(codeExiste($wallets, $newWallet['code']))             return "Erreur : ce code est déjà utilisé.";
    if(validerSolde($newWallet['solde']) == 'invalide')      return "Erreur : le solde ne peut pas être négatif.";

    ajouterWallet($wallets, $newWallet);
    return "Wallet créé avec succès pour " . $newWallet['client'] . " !";
}

// Effectue un dépôt sur un wallet existant
function effectuerDepot(&$wallets, &$transactions, $telephone, $montant){
    if(trouverWallet($wallets, $telephone) == -1)  return "Erreur : aucun wallet trouvé pour ce numéro.";
    if(validerMontant($montant) == 'invalide')      return "Erreur : le montant doit être positif.";

    $index        = trouverWallet($wallets, $telephone);
    $nouveauSolde = $wallets[$index]['solde'] + $montant;
    mettreAJourSolde($wallets, $index, $nouveauSolde);
    ajouterTransaction($transactions, [
        'type'        => 'DEPOT',
        'montant'     => $montant,
        'frais'       => 0,
        'indexClient' => $index,
        'date'        => date('d/m/Y H:i:s')
    ]);
    return "Dépôt de " . $montant . " CFA effectué. Nouveau solde : " . $nouveauSolde . " CFA";
}

// Effectue un retrait avec calcul des frais
function effectuerRetrait(&$wallets, &$transactions, $telephone, $montant){
    if(trouverWallet($wallets, $telephone) == -1)  return "Erreur : aucun wallet trouvé pour ce numéro.";
    if(validerMontant($montant) == 'invalide')      return "Erreur : le montant doit être positif.";

    $index         = trouverWallet($wallets, $telephone);
    $frais         = calculerFrais($montant);
    $totalADebiter = $montant + $frais;

    if($wallets[$index]['solde'] < $totalADebiter)
        return "Erreur : solde insuffisant. Il vous faut " . $totalADebiter . " CFA (montant + frais).";

    $nouveauSolde = $wallets[$index]['solde'] - $totalADebiter;
    mettreAJourSolde($wallets, $index, $nouveauSolde);
    ajouterTransaction($transactions, [
        'type'        => 'RETRAIT',
        'montant'     => $montant,
        'frais'       => $frais,
        'indexClient' => $index,
        'date'        => date('d/m/Y H:i:s')
    ]);
    return "Retrait de " . $montant . " CFA. Frais : " . $frais . " CFA. Nouveau solde : " . $nouveauSolde . " CFA";
}
