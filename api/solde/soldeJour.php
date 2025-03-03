<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // $id_user = $_GET['id'];
    // $id_agence = $_GET['id2'];
    // Response de caisse
    $_SOLDE_DU_JOUR = 0;
    extract($_GET);
    try {

        if ($id_agence != 'null'):
            $affectationUser = ModeleClasse::getoneByname('id_utilisateur', 'affectations', $id_user);
            $agenceUser = ModeleClasse::getoneByname('id', 'agences', $affectationUser['id_agence']);
            $zoneUser = ModeleClasse::getoneByname('id', 'zones', $agenceUser['id_zone']);
            $deviseUser = ModeleClasse::getoneByname('id', 'devise', $zoneUser['id_devise']);
            $libelleDeviseUser = $deviseUser['libelle'];
        else:
            $libelleDeviseUser = 'GNF';
        endif;


        // Date du jour bien recuperer
        $today = _Aujourdhui();

        // Envoie
        if ($id_agence != 'null')
            $Envoie = ModeleClasse::getallbyName('transfert', 'created_by', $id_user);
        else
            $Envoie = ModeleClasse::getall('transfert');
        foreach ($Envoie as $data):
            $createdAt = dateConvert($data['created_at']);
            if ($createdAt == $today)
                $_SOLDE_DU_JOUR += $data['montant'] + $data['frais'];
        // ----------------------------------------------------------------            
        endforeach;

        // Retrait
        if ($id_agence != 'null')
            $Retrait = ModeleClasse::getallbyName('transfert', 'modify_by', $id_user);
        else
            $Retrait = ModeleClasse::getall('transfert');
        foreach ($Retrait as $data):
            $createdAt = dateConvert($data['created_at']);
            if ($createdAt == $today)
                $_SOLDE_DU_JOUR -= $data['montantRetrait'];
        // ----------------------------------------------------------------            
        endforeach;

        if ($id_agence != 'null'):
            // Transfert de fond Sortant
            $FondEntrant = ModeleClasse::getallbyName('transfert_fond', 'id_agenceSource', $id_agence);
            foreach ($FondEntrant as $data):
                $createdAt = dateConvert($data['created_at']);
                if ($createdAt == $today):
                    if ($data['statut'] == 'valider'):
                        $_SOLDE_DU_JOUR -= $data['montant'];
                    endif;
                endif;
            // ----------------------------------------------------------------            
            endforeach;

            // Transfert de fond Entrant
            $FondEntrant = ModeleClasse::getallbyName('transfert_fond', 'id_agenceDestination', $id_agence);
            foreach ($FondEntrant as $data):
                $createdAt = dateConvert($data['created_at']);
                if ($createdAt == $today):
                    if ($data['statut'] == 'valider'):
                        $_SOLDE_DU_JOUR += $data['montant'];
                    endif;
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
                $createdAt = dateConvert($data['created_at']);
                if ($createdAt == $today):
                    if ($data['typeTransaction'] == 1) // Encaissement
                        $_SOLDE_DU_JOUR += $data['montant'];
                    else // Retrait de Gains et Decaissement
                        $_SOLDE_DU_JOUR -= $data['montant'];
                endif;
            endif;
        // ----------------------------------------------------------------            
        endforeach;

        // Retourner les caisses sous forme de JSON
        echo json_encode(formatNumber2($_SOLDE_DU_JOUR) . ' ' . $libelleDeviseUser, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
