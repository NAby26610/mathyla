<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datatransactions = [];
    try {
        // Récupérer toutes les transactions triées par ordre décroissant
        $read = ModeleClasse::getallDESC("transactions");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'agence associée à la transaction
                $agence = ModeleClasse::getoneByname('id', 'agences', $data['id_agence']);
                
                // Récupérer les informations sur la devise associée à la transaction
                $devise = ModeleClasse::getoneByname('id', 'devise', $data['id_devise']);
                
                // Construire un objet pour la transaction avec les informations sur l'agence et la devise
                $objet = [
                    "id" => $data["id"],
                    "transaction" => [
                        "montant" => $data["montant"] ?? 0,
                        "typeTransaction" => $data["typeTransaction"] ?? null,
                        "created_at" => $data["created_at"] ?? null,
                        "created_by" => $data["created_by"] ?? null,
                        "updated_at" => $data["updated_at"] ?? null,
                       
                        "agence" => [
                            "id_agence" => $agence["id"] ?? null,  // ID de l'agence associée
                            "libelle" => $agence["libelle"] ?? null,  // Libellé de l'agence
                            "telephone" => $agence["telephone"] ?? null,  // Téléphone de l'agence
                            "adresse" => $agence["adresse"] ?? null,  // Adresse de l'agence
                            "soldeInitial" => $agence["soldeInitial"] ?? null,  // Solde initial de l'agence
                            "soldeMax" => $agence["soldeMax"] ?? null,  // Solde maximum de l'agence
                            "seuil" => $agence["seuil"] ?? null,
                            "indicatif" => $agence["indicatif"] ?? null,
                            "adresse" => $agence["adresse"] ?? null,
                            "heureOuverture" => $agence["heureOuverture"] ?? null,
                            "heureFermeture" => $agence["heureFermeture"] ?? null,
                            "descriptions" => $agence["descriptions"] ?? null,
                            "created_at" => $agence["created_at"] ?? null,
                        ],
                       
                        "devise" => [
                            "id_devise" => $devise["id"] ?? null,  // ID de la devise associée
                            "libelle" => $devise["libelle"] ?? null,  // Libellé de la devise
                   
                        ],
                        
                    ],
                ];

                // Ajouter l'objet construit à la liste des transactions
                array_push($datatransactions, $objet);
            }
        } else {
            echo json_encode('Aucune transaction trouvée');
            exit;
        }

        // Retourner les transactions sous forme de JSON
        echo json_encode($datatransactions, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
