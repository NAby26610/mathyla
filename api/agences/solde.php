<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    extract($_GET);
    try {
        // Récupérer l'agence spécifique par son ID
        $agenceId = $_GET['id'];
        $agence = ModeleClasse::getone("agences", $agenceId);

        $Affectation = ModeleClasse::getoneByNameDesc('affectations', 'id_agence', $agenceId);
        $_SOLDE = $Affectation['soldeOuverture'];

        if ($agence):
            // Récupérer les informations de la zone associée à l'agence
            $zone = ModeleClasse::getoneByname("id", "zones", $agence["id_zone"]);
            $devise = ModeleClasse::getoneByname("id", "devise", $zone["id_devise"]);
            // Concaténer nom et prénom
            $createdBy = null;
            if (!empty($utilisateur["nom"]) || !empty($utilisateur["prenom"])) {
                $createdBy = trim(($utilisateur["nom"] ?? '') . ' ' . ($utilisateur["prenom"] ?? ''));
            }

            // OPERATION =================================================================
            // Récupérer le transfert spécifique par son ID (DEPOT)
            $transfert = ModeleClasse::getallbyName("transfert", 'created_by', $Affectation['id_utilisateur']);
            $gainsEnvoie = 0;
            foreach ($transfert as $data):
                // Calcul du gains
                $gainsEnvoie += ($data['frais'] * 35) / 100;
                $_SOLDE += ($data['montant'] + $gainsEnvoie);
            endforeach;

            // Récupérer le transfert spécifique par son ID (RETRAIT)
            $Retrait = ModeleClasse::getallbyName("transfert", 'modify_by', $Affectation['id_utilisateur']);
            $gainsRetrait = 0;
            foreach ($Retrait as $data):
                if ($data['id_zone'] == $zone['id'] || $zone['id_devise'] == $devise['id']):
                    $calc = (($data['frais'] * 35) / 100);
                    $gainsRetrait += $calc;
                else:
                    // LA ZONE EST DIFFERENT, ET LA DEVISE DEIFFERENT 
                    if ($zone['id_devise'] == 1) { // GNF - FCFA
                        $calc = (($r['frais'] * 35) / 100);
                        $gainsRetrait += $calc / $r['taux_du_jour'];
                    } elseif ($zone['id_devise'] == 2) { // FCFA - GNF
                        $calc = (($r['frais'] * 35) / 100);
                        $gainsRetrait += $calc * $r['taux_du_jour'];
                    }
                endif;
                $_SOLDE += $gainsRetrait;
                $_SOLDE -= $data['montantRetrait'];
            endforeach;

            // FOND RECU
            $TF_1 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceDestination', $id);
            foreach ($TF_1 as $data):
                $_SOLDE += $data['montant'];
            endforeach;

            // FOND ENVOYER
            $TF_2 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceSource', $id);
            foreach ($TF_2 as $data):
                $_SOLDE -= $data['montant'];
            endforeach;
        else:
            echo json_encode(["message" => "Agence non trouvée"]);
        endif;

        echo json_encode([formatNumber2($_SOLDE), $devise['libelle']], JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Aucune donnée reçue"]);
}
