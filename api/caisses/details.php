<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Response de caisse
    $response = [];
    $tableData = [];
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
            $createdAt = dateConvertMois($e['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == CE_MOIS()) {
                // Calcul du gains
                $gainsEnvoie += ($e['frais'] * 35) / 100;
                // ----------------------------------------------------------------
                $montantEnvoie += $e['montant'] + $e['frais'];

                $Objet = [
                    'libelle' => "Envoie d'argent",
                    'montant' => formatNumber2($e['montant'] + $e['frais']),
                    'statut' => $e['statut']
                ];
                array_push($tableData, $Objet);
            }
        // ----------------------------------------------------------------            
        endforeach;

        // Retrait
        $Retrait = ModeleClasse::getallbyName('transfert', 'modify_by', $id_user);
        $dataRetrait = [];
        $montantRetrait = 0;
        $gainsRetrait = 0;
        foreach ($Retrait as $r):
            $createdAt = dateConvertMois($e['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == CE_MOIS()) {
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

                $Objet = [
                    'libelle' => "Retrait d'argent",
                    'montant' => formatNumber2($r['montantRetrait']) ?? 0,
                    'statut' => $r['statut']
                ];
                array_push($tableData, $Objet);
            }
        endforeach;


        // Transfert de fond | ENTRANT
        $F_ET = ModeleClasse::getallbyName('transfert_fond', 'modify_by', $id_user);
        $dataF_ET = [];
        $montantEntrant = 0;
        foreach ($F_ET as $fond1):
            $createdAt = dateConvertMois($e['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == CE_MOIS()) {
                // ----------------------------------------------------------------
                if ($fond1['statut'] == 'valider')
                    $montantEntrant += $fond1['montant'];

                $Objet = [
                    'libelle' => "Fond Entrant",
                    'montant' => formatNumber2($fond1['montant']) ?? 0,
                    'statut' => $fond1['statut']
                ];
                array_push($tableData, $Objet);
            }
        endforeach;


        // Transfert de fond | SORTANT
        $F_ST = ModeleClasse::getallbyName('transfert_fond', 'id_agenceSource', $id_agence);
        $dataF_ST = [];
        $montantSortant = 0;
        foreach ($F_ST as $fond2):
            $createdAt = dateConvertMois($e['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == CE_MOIS()) {
                // ----------------------------------------------------------------
                if ($fond2['statut'] == 'valider')
                    $montantSortant += $fond2['montant'];

                $Objet = [
                    'libelle' => "Fond Sortant",
                    'montant' => formatNumber2($fond2['montant']) ?? 0,
                    'statut' => $fond2['statut']
                ];
                array_push($tableData, $Objet);
            }
        endforeach;

        // Transaction | AUTRES-DEPENSES
        $Transaction = ModeleClasse::getallbyName('transactions', 'id_agence', $id_agence);
        $Encaissement = 0;
        $Decaissement = 0;
        $retraitGainsAgent = 0;
        foreach ($Transaction as $data):
            $createdAt = dateConvertMois($e['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt == CE_MOIS()) {
                $TYPE = ModeleClasse::getoneByname('id', 'transactions', $data['typeTransaction']);
                $Type = ModeleClasse::getoneByname('id', 'type_depenses', $data['typeTransaction']);
                if ($data['typeTransaction'] == 1 && $data['statut_transaction'] == 'valider') //  Encaissement
                    $Encaissement += $data['montant'];
                elseif ($data['typeTransaction'] == 0 && $data['statut_transaction'] == 'valider') // Decaissement
                    $Decaissement += $data['montant'];
                elseif ($data['typeTransaction'] == -1 && $data['statut_transaction'] == 'valider') // Decaissement
                    $retraitGainsAgent += $data['montant'];

                $Objet = [
                    'libelle' => $Type['libelle'],
                    'montant' => formatNumber2($data['montant']) ?? 0,
                    'statut' => $data['statut_transaction']
                ];
                array_push($tableData, $Objet);
            }
        endforeach;

        $_SOLDE = 0;
        // CALCULE DU SOLDE
        $_SOLDE = (($montantEnvoie + $montantEntrant + $Encaissement) - ($montantRetrait + $montantSortant + $Decaissement + $retraitGainsAgent));

        $Global = [
            'envoieFrais' => formatNumber2($montantEnvoie) . ' ' . $libelleDeviseUser,
            'retrait' => formatNumber2($montantRetrait) . ' ' . $libelleDeviseUser,
            'fondEntrant' => formatNumber2($montantEntrant) . ' ' . $libelleDeviseUser,
            'fondSortant' => formatNumber2($montantSortant) . ' ' . $libelleDeviseUser,
            'encaissement' => formatNumber2($Encaissement) . ' ' . $libelleDeviseUser,
            'decaissement' => formatNumber2($Decaissement) . ' ' . $libelleDeviseUser,
            'retraitGainsAgent' => formatNumber2($retraitGainsAgent) . ' ' . $libelleDeviseUser,
            'gainsDepot' => formatNumber2($gainsEnvoie) . ' ' . $libelleDeviseUser,
            'gainsRetrait' => formatNumber2($gainsRetrait) . ' ' . $libelleDeviseUser,
            'solde_du_jour' => formatNumber2($_SOLDE) . ' ' . $libelleDeviseUser,
            'tableData' => $tableData
        ];

        array_push($response, $Global);

        // Retourner les caisses sous forme de JSON
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
