<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialisation de la réponse
    $response = [];

    // Vérifier si les paramètres requis sont présents
    if (!isset($_POST['id_user'], $_POST['startDate'], $_POST['endDate'])) {
        echo json_encode(["error" => "Paramètres manquants"]);
        exit;
    }

    extract($_POST);

    try {
        // Récupération de l'agence de l'utilisateur
        $Affectation = ModeleClasse::getoneByNameDesc('affectations', 'id_utilisateur', $id_user);
        $AgenceUser = ModeleClasse::getoneByNameDesc('agences', 'id', $Affectation['id_agence']);
        $zoneUser = ModeleClasse::getoneByName('id', 'zones', $AgenceUser['id_zone']);

        // Récupération des envoie
        $Envoie = ModeleClasse::getallbyName('transfert', 'created_by', $id_user);
        foreach ($Envoie as $r) {
            $gainsRetrait = 0;
            $createdAt = dateConvert($r['created_at']);
            // Vérifier si la date de création est dans l'intervalle
            if ($createdAt >= $startDate && $createdAt <= $endDate) {
                // GAINS
                // Agence d'EXPEDITION
                $AE = ModeleClasse::getoneByname('id', 'agences', $r['id_agence']);
                $Zone_Exp = ModeleClasse::getoneByname('id', 'zones', $AE['id_zone']);
                if ($r['id_zone'] == $Zone_Exp['id'] || $Zone_Exp['id_devise'] == $zoneUser['id_devise']):
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
                $OBJET = [
                    'id' => $r['id'],
                    'created_at' => $r['created_at'],
                    'libelle' => 'Retrait d\'argent',
                    'Agence' => $AgenceUser['libelle'],
                    'montantRetrait' => formatNumber2($r['montant']),
                    'frais' => formatNumber2($r['frais']),
                    'gains' => ($gainsRetrait),
                    'statut' => $r['statut'],
                ];
                array_push($response, $OBJET);
            }
        }

         // Récupération des retrait
         $Retrait = ModeleClasse::getallbyName('transfert', 'modify_by', $id_user);
         foreach ($Retrait as $r) {
             $gainsRetrait = 0;
             $createdAt = dateConvert($r['created_at']);
             // Vérifier si la date de création est dans l'intervalle
             if ($createdAt >= $startDate && $createdAt <= $endDate) {
                 // GAINS
                 // Agence d'EXPEDITION
                 $AE = ModeleClasse::getoneByname('id', 'agences', $r['id_agence']);
                 $Zone_Exp = ModeleClasse::getoneByname('id', 'zones', $AE['id_zone']);
                 if ($r['id_zone'] == $Zone_Exp['id'] || $Zone_Exp['id_devise'] == $zoneUser['id_devise']):
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
                 $OBJET = [
                     'id' => $r['id'],
                     'created_at' => $r['created_at'],
                     'libelle' => 'Retrait d\'argent',
                     'Agence' => $AgenceUser['libelle'],
                     'montantRetrait' => formatNumber2($r['montant']),
                     'frais' => formatNumber2($r['frais']),
                     'gains' => ($gainsRetrait),
                     'statut' => $r['statut'],
                 ];
                 array_push($response, $OBJET);
             }
         }

        // Retour Apis
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
