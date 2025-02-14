<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    extract($_GET);
    try {
        // Récupérer l'agence spécifique par son ID
        $agenceId = $_GET['id'];
        $agence = ModeleClasse::getone("agences", $agenceId);
        $infoAgence = [];
        $operations = [];
        
        $Affectation = ModeleClasse::getoneByNameDesc('affectations', 'id_agence', $agenceId);
        $_SOLDE = $Affectation['soldeOuverture'];

        if ($agence):
            // Récupérer les informations de la zone associée à l'agence
            $zone = ModeleClasse::getoneByname("id", "zones", $agence["id_zone"]);
            $devise = ModeleClasse::getoneByname("id", "devise", $zone["id_devise"]);

            // Récupérer les informations du créateur de l'agence
            $utilisateur = ModeleClasse::getoneByname("id", "utilisateurs", $agence["created_by"]);
            // Agent
            $agent = ModeleClasse::getoneByname("id", "utilisateurs", $Affectation["id_utilisateur"]);


            // Concaténer nom et prénom
            $createdBy = null;
            if (!empty($utilisateur["nom"]) || !empty($utilisateur["prenom"])) {
                $createdBy = trim(($utilisateur["nom"] ?? '') . ' ' . ($utilisateur["prenom"] ?? ''));
            }

            // OPERATION =================================================================
            // Récupérer le transfert spécifique par son ID (DEPOT)
            $transfert = ModeleClasse::getallbyName("transfert", 'created_by', $Affectation['id_utilisateur']);
            $Envoie = [];
            foreach ($transfert as $data):
                $_Envoie = [
                    'created_at' => $data["created_at"],
                    'libelle' => "Envoie",
                    'montant' => formatNumber2(floatval($data["montant"])),
                    'frais' => formatNumber2(floatval($data["frais"])),
                    'statut' => $data["statut"],
                ];
                array_push($operations, $_Envoie);
                if ($data['deductFrais'] == 1)
                    $_SOLDE += $data['montant'];
                elseif ($data['deductFrais'] == 0)
                    $_SOLDE += $data['montant'] + $data['montant'];
            endforeach;

            // Récupérer le transfert spécifique par son ID (RETRAIT)
            $Retrait = ModeleClasse::getallbyName("transfert", 'modify_by', $Affectation['id']);
            $Retrait = [];
            foreach ($Retrait as $data):
                $_Retrait = [
                    'created_at' => $data["created_at"],
                    'libelle' => "Retrait",
                    'montant' => formatNumber2(floatval($data["montant"])),
                    'frais' => formatNumber2(floatval($data["frais"])),
                    'statut' => $data["statut"],
                ];
                array_push($operations, $_Retrait);
                $_SOLDE -= $data['montantRetrait'];
            endforeach;

            // FOND RECU
            $TF_1 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceDestination', $id);
            $FondEntrant = [];
            foreach ($TF_1 as $data):
                $_FE = [
                    'created_at' => $data["created_at"],
                    'libelle' => "Fond entrant",
                    'montant' => formatNumber2(floatval($data["montant"])),
                    'frais' => -1,
                    'statut' => $data["statut"],
                ];
                array_push($operations, $_FE);
                $_SOLDE += $data['montant'];
            endforeach;

            // FOND ENVOYER
            $TF_2 = ModeleClasse::getallbyName("transfert_fond", 'id_agenceSource', $id);
            $FondSortant = [];
            foreach ($TF_2 as $data):
                $_FS = [
                    'created_at' => $data["created_at"],
                    'libelle' => "Fond sortant",
                    'montant' => formatNumber2(floatval($data["montant"])),
                    'frais' => -1,
                    'statut' => $data["statut"],
                ];
                array_push($operations, $_FS);
                $_SOLDE += $data['montant'];
            endforeach;

            // Construire l'objet agence à retourner
            $objet = [
                "id" => $agence["id"],
                "id_zone" => $agence["id_zone"] ?? null,
                "zone" => $zone["libelle"] ?? null,  // Nom de la zone associée
                "devise" => $devise["libelle"] ?? null,  // Nom de la zone associée
                "libelle" => $agence["libelle"],
                "telephone" => $agence["telephone"],
                "soldeInitial" => $agence["soldeInitial"],
                "soldeMax" => $agence["soldeMax"],
                "seuil" => $agence["seuil"],
                "indicatif" => $agence["indicatif"],
                "adresse" => $agence["adresse"] ?? null,
                "agent" => $agent["prenom"].' '.$agent["nom"] ?? null,
                "heureOuverture" => $agence["heureOuverture"] ?? null,
                "heureFermeture" => $agence["heureFermeture"] ?? null,
                "descriptions" => $agence["descriptions"] ?? null,
                "created_at" => $agence["created_at"],
                "created_by" => $createdBy, // Nom et prénom concaténés
                "updated_at" => $agence["updated_at"],
                'solde' => formatNumber2($_SOLDE),
            ];
            // array_push($infoAgence, $objet);
            $infoAgence = $objet;
        // Retourner l'objet agence sous forme de JSON
        else:
            echo json_encode(["message" => "Agence non trouvée"]);
        endif;
        $Response = [
            'infoAgence' => $infoAgence,
            'operations' => $operations,
        ];

        echo json_encode($Response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Aucune donnée reçue"]);
}
