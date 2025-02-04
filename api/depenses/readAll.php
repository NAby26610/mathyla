<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datadepenses = [];
    try {
        // Récupérer toutes les dépenses triées par ordre décroissant
        $read = ModeleClasse::getallDESC("depenses");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'agence associée à la dépense
                $agence = ModeleClasse::getoneByname('id', 'agences', $data['id_agence']);
                
                // Construire l'objet dépense avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "types" => $data["types"] ?? null,  // Type de dépense
                    "montant" => $data["montant"] ?? 0,  // Montant de la dépense
                    "motif" => $data["motif"] ?? null,  // Motif de la dépense
                    "statut" => $data["statut"] ?? 100,  // Statut de la dépense
                    "created_at" => $data["created_at"] ?? null,
                    "created_by" => $data["created_by"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null,
                    "agence" => [
                        "id_agence" => $agence["id"] ?? null,  // ID de l'agence
                        "libelle" => $agence["libelle"] ?? null,  // Libellé de l'agence
                        "telephone" => $agence["telephone"] ?? null,  // Téléphone de l'agence
                        "adresse" => $agence["adresse"] ?? null,  // Adresse de l'agence
                    ],
                ];

                // Ajouter l'objet construit à la liste des dépenses
                array_push($datadepenses, $objet);
            }
        } else {
            echo json_encode('Aucune dépense trouvée');
            exit;
        }

        // Retourner les dépenses sous forme de JSON
        echo json_encode($datadepenses, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
