<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Response de caisse
    $response = [];
    extract($_GET);

    try {

        $affectationUser = ModeleClasse::getoneByname('id_utilisateur', 'affectations', $id_user);
        $agenceUser = ModeleClasse::getoneByname('id', 'agences', $affectationUser['id_agence']);
        $zoneUser = ModeleClasse::getoneByname('id', 'zones', $agenceUser['id_zone']);
        $deviseUser = ModeleClasse::getoneByname('id', 'devise', $zoneUser['id_devise']);
        $idDeviseUser = $deviseUser['id'];
        $libelleDeviseUser = $deviseUser['libelle'];

        // Envoie
        $Envoie = ModeleClasse::getallbyName('transfert', 'created_by', $id_user);
        $montantEnvoie = 0;
        $gainsEnvoie = 0;
        foreach ($Envoie as $e):
            $createdAt = dateConvert($e['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == _Hier()) {
                // Calcul du gains
                $gainsEnvoie += ($e['frais'] * 35) / 100;
                // ----------------------------------------------------------------
                if ($e['deductFrais'] == 0) // Frais payer a part alors 
                    $montantEnvoie += $e['montant'] + $e['frais'];
                else
                    $montantEnvoie += $e['montant'];
            }
        // ----------------------------------------------------------------            
        endforeach;

        // Retrait
        $Retrait = ModeleClasse::getallbyName('transfert', 'modify_by', $id_user);
        $dataRetrait = [];
        $montantRetrait = 0;
        $gainsRetrait = 0;
        foreach ($Retrait as $r):
            $createdAt = dateConvert($r['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == _Hier()) {
                // Agence d'EXPEDITION
                $AE = ModeleClasse::getoneByname('id', 'agences', $r['id_agence']);
                $Zone_Exp = ModeleClasse::getoneByname('id', 'zones', $AE['id_zone']);
                if ($r['id_zone'] == $Zone_Exp['id'] || $Zone_Exp['id_devise'] == $idDeviseUser):
                    $calc = (($r['frais'] * 35) / 100);
                    $gainsRetrait += $calc;
                else:
                    // LA ZONE EST DIFFERENT, ET LA DEVISE DEIFFERENT 
                    if ($Zone_Exp['id_devise'] == 1) { // GNF - FCFA
                        $calc = (($r['frais'] * 35) / 100);
                        $gainsRetrait += $calc / $r['taux_du_jour'];
                    } elseif ($Zone_Exp['id_devise'] == 2) { // FCFA - GNF
                        $calc = (($r['frais'] * 35) / 100);
                        $gainsRetrait += $calc * $r['taux_du_jour'];
                    }
                endif;
                $montantRetrait += $r['montantRetrait'];
            }
        endforeach;


        // Transfert de fond | ENTRANT
        $F_ET = ModeleClasse::getallbyName('transfert_fond', 'modify_by', $id_user);
        $dataF_ET = [];
        $montantEntrant = 0;
        foreach ($F_ET as $fond1):
            $createdAt = dateConvert($fond1['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == _Hier()) {
                // ----------------------------------------------------------------
                if ($fond1['statut'] == 'valider')
                    $montantEntrant += $fond1['montant'];
            }
        endforeach;


        // Transfert de fond | SORTANT
        $F_ST = ModeleClasse::getallbyName('transfert_fond', 'id_agenceSource', $id_agence);
        $dataF_ST = [];
        $montantSortant = 0;
        foreach ($F_ST as $fond2):
            $createdAt = dateConvert($fond2['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == _Hier()) {
                // ----------------------------------------------------------------
                if ($fond2['statut'] == 'valider')
                    $montantSortant += $fond2['montant'];
            }
        endforeach;

        // Transaction | AUTRES-DEPENSES
        $Transaction = ModeleClasse::getallbyName('transactions', 'id_agence', $id_agence);
        $Encaissement = 0;
        $Decaissement = 0;
        foreach ($Transaction as $data):
            $createdAt = dateConvert($data['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == _Hier()) {
                $Type = ModeleClasse::getoneByname('id', 'type_depenses', $data['typeTransaction']);
                if ($data['typeTransaction'] == 1 && $data['statut_transaction'] == 'valider') //  Encaissement
                    $Encaissement += $data['montant'];
                elseif ($data['typeTransaction'] == 0 && $data['statut_transaction'] == 'valider') // Decaissement
                    $Decaissement += $data['montant'];
            }
        endforeach;

        $_SOLDE = 0;
        // CALCULE DU SOLDE
        $_SOLDE = (($montantEnvoie + $montantEntrant + $Encaissement) - ($montantRetrait + $montantSortant + $Decaissement));

        // Retourner les caisses sous forme de JSON
        echo json_encode(formatNumber2($_SOLDE) . ' ' . $libelleDeviseUser, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
