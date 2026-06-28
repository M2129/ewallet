<?php
namespace EWallet\Controller;

use function EWallet\Service\{creerWallet, effectuerDepot, effectuerRetrait};
use function EWallet\Repository\filtrerTransactions;

// Seul fichier autorisé à utiliser echo et readline

function afficherMenu(){
    echo "\n" . str_repeat("=", 40) . "\n";
    echo "        WALLET MOBILE MONEY\n";
    echo str_repeat("=", 40) . "\n";
    echo "  1. Créer Wallet\n";
    echo "  2. Faire Dépôt\n";
    echo "  3. Faire Retrait\n";
    echo "  4. Lister les Transactions\n";
    echo "  0. Quitter\n";
    echo str_repeat("=", 40) . "\n";
}

// Reçoit le choix de l'utilisateur, fait la saisie et appelle le bon service
function traiterChoix($choix, &$wallets, &$transactions){
    if($choix == '1'){
        echo "\n-- CRÉER UN WALLET --\n";
        $wallet = [];
        $wallet['client']    = readline("Nom du client            : ");
        $wallet['telephone'] = readline("Numéro de téléphone      : ");
        $wallet['code']      = readline("Code secret (4 chiffres) : ");
        $wallet['solde']     = (float) readline("Solde initial (CFA)      : ");
        echo "\n" . creerWallet($wallets, $wallet) . "\n";

    } elseif($choix == '2'){
        echo "\n-- FAIRE UN DÉPÔT --\n";
        $telephone = readline("Numéro de téléphone : ");
        $montant   = (float) readline("Montant à déposer   : ");
        echo "\n" . effectuerDepot($wallets, $transactions, $telephone, $montant) . "\n";

    } elseif($choix == '3'){
        echo "\n-- FAIRE UN RETRAIT --\n";
        $telephone = readline("Numéro de téléphone : ");
        $montant   = (float) readline("Montant à retirer   : ");
        echo "\n" . effectuerRetrait($wallets, $transactions, $telephone, $montant) . "\n";

    } elseif($choix == '4'){
        afficherTransactions($transactions, $wallets);

    } elseif($choix == '0'){
        echo "\nAu revoir !\n";

    } else {
        echo "\nChoix invalide. Veuillez saisir 0, 1, 2, 3 ou 4.\n";
    }
}

// Affiche l'historique de toutes les transactions
// Utilise array_map + array_keys pour parcourir sans boucle manuelle
function afficherTransactions($transactions, $wallets){
    echo "\n" . str_repeat("=", 40) . "\n";
    echo "      HISTORIQUE DES TRANSACTIONS\n";
    echo str_repeat("=", 40) . "\n";

    if(empty($transactions)){
        echo "Aucune transaction enregistrée.\n";
        return;
    }

    array_map(
        function($index) use ($transactions, $wallets){
            $t      = $transactions[$index];
            $client = $wallets[$t['indexClient']]['client'];
            $frais  = $t['frais'] > 0
                    ? " | Frais : " . number_format($t['frais'], 0, ',', ' ') . " CFA"
                    : "";

            echo "\n[" . $index . "] " . $t['type'] . " — " . $client . "\n";
            echo "    Montant : " . number_format($t['montant'], 0, ',', ' ') . " CFA" . $frais . "\n";
            echo "    Date    : " . $t['date'] . "\n";
            echo "    " . str_repeat("-", 35) . "\n";
        },
        array_keys($transactions)
    );
}