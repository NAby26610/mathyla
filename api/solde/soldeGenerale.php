<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = [];
    // Response de caisse
    $_SOLDE_ = 0;
    extract($_GET);
    try {

        if ($id_agence != 'null'):
            $affectationUser = ModeleClasse::getoneByname('id_utilisateur', 'affectations', $id_user);
            $agenceUser = ModeleClasse::getoneByname('id', 'agences', $affectationUser['id_agence']);
            $zoneUser = ModeleClasse::getoneByname('id', 'zones', $agenceUser['id_zone']);
            $deviseUser = ModeleClasse::getoneByname('id', 'devise', $zoneUser['id_devise']);
            $libelleDeviseUser = $deviseUser['libelle'] ?? '?';
        else:
            $libelleDeviseUser = 'GNF';
        endif;

        // Envoie
        if ($id_agence != 'null')
            $Envoie = ModeleClasse::getallbyName('transfert', 'created_by', $id_user);
        else
            $Envoie = ModeleClasse::getall('transfert');
        foreach ($Envoie as $data):
            $_SOLDE_ += $data['montant'] + $data['frais'];
        // ----------------------------------------------------------------            
        endforeach;

        // Retrait
        if ($id_agence != 'null')
            $Retrait = ModeleClasse::getallbyName('transfert', 'modify_by', $id_user);
        else
            $Retrait = ModeleClasse::getall('transfert');
        foreach ($Retrait as $data):
            if ($data['modify_by'] != NULL)
                $_SOLDE_ -= $data['montantRetrait'];
        // ----------------------------------------------------------------            
        endforeach;

        // Transfert de fond Sortant
        if ($id_agence != 'null'):
            $FondEntrant = ModeleClasse::getallbyName('transfert_fond', 'id_agenceSource', $id_agence);
            foreach ($FondEntrant as $data):
                if ($data['statut'] == 'valider'):
                    $_SOLDE_ -= $data['montant'];
                endif;
            // ----------------------------------------------------------------            
            endforeach;

            // Transfert de fond Entrant
            $FondEntrant = ModeleClasse::getallbyName('transfert_fond', 'id_agenceDestination', $id_agence);
            foreach ($FondEntrant as $data):
                if ($data['statut'] == 'valider'):
                    $_SOLDE_ += $data['montant'];
                endif;
            // ----------------------------------------------------------------            
            endforeach;
        endif;


        // Transaction
        if ($id_agence != 'null')
            $Transaction = ModeleClasse::getallbyName('transactions', 'id_agence', $id_agence);
        else
            $Transaction = ModeleClasse::getall('transactions');
        foreach ($Transaction as $data):
            if ($data['statut_transaction'] == 'valider') :
                if ($data['typeTransaction'] == 1) // Encaissement
                    $_SOLDE_ += $data['montant'];
                else // Retrait de Gains et Decaissement
                    $_SOLDE_ -= $data['montant'];
            endif;
        // ----------------------------------------------------------------            
        endforeach;

        // Retourner sous forme de JSON
        echo json_encode(formatNumber2($_SOLDE_) . ' ' . $libelleDeviseUser, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
