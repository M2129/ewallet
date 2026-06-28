<?php
// Affiche le menu principal
function afficherMenu(){
    echo "\n=============================\n";
    echo "     WALLET MOBILE MONEY     \n";
    echo "=============================\n";
    echo "1. Créer Wallet\n";
    echo "2. Faire Dépôt\n";
    echo "3. Faire Retrait\n";
    echo "4. Lister les Transactions\n";
    echo "0. Quitter\n";
    echo "=============================\n";
}

// Gère la saisie et appelle le bon service selon le choix
function traiterChoix($choix, &$wallets, &$transactions){
    if($choix == '1'){
        $wallet = [];
        $wallet['client']    = readline("Nom du client            : ");
        $wallet['telephone'] = readline("Numéro de téléphone      : ");
        $wallet['code']      = readline("Code secret (4 chiffres) : ");
        $wallet['solde']     = (float) readline("Solde initial            : ");
        echo "\n" . creerWallet($wallets, $wallet) . "\n";

    } elseif($choix == '2'){
        $telephone = readline("Numéro de téléphone : ");
        $montant   = (float) readline("Montant à déposer   : ");
        echo "\n" . effectuerDepot($wallets, $transactions, $telephone, $montant) . "\n";

    } elseif($choix == '3'){
        $telephone = readline("Numéro de téléphone : ");
        $montant   = (float) readline("Montant à retirer   : ");
        echo "\n" . effectuerRetrait($wallets, $transactions, $telephone, $montant) . "\n";

    } elseif($choix == '4'){
        afficherTransactions($transactions, $wallets);

    } elseif($choix == '0'){
        echo "\nAu revoir !\n";

    } else {
        echo "\nChoix invalide, veuillez réessayer.\n";
    }
}

// Affiche toutes les transactions enregistrées
function afficherTransactions($transactions, $wallets){
    echo "\n===== HISTORIQUE =====\n";
    if(empty($transactions)){
        echo "Aucune transaction pour le moment.\n";
        return;
    }
    foreach($transactions as $index => $t){
        $client = $wallets[$t['indexClient']]['client'];
        echo "\n[" . $index . "] " . $t['type'] . " - " . $client . "\n";
        echo "Montant : " . $t['montant'] . " CFA\n";
        if($t['frais'] > 0) echo "Frais   : " . $t['frais'] . " CFA\n";
        echo "Date    : " . $t['date'] . "\n";
        echo "----------------------\n";
    }
}
