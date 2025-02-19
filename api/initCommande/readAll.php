<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datainitCommandes = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les initCommandes triés par ordre décroissant
        $read = ModeleClasse::getallDESC("initCommande");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entite
                $entite = ModeleClasse::getoneByname('id', 'entite', $data['id_entite']);
                $fournisseur = ModeleClasse::getoneByname('id', 'fournisseur', $data['id_fournisseur']);
                
                // Construire un objet pour le initCommande avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_entite" => $entite["id"],
                    "id_fournisseur" => $fournisseur["id"],
                    "statut" => $data["statut"],
                    "created_at" => $data["created_at"] ?? null,  // Date de création du initCommande
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du initCommande
                ];

                // Ajouter l'objet construit à la liste des initCommandes
                array_push($datainitCommandes, $objet);
            }
        } else {
            echo json_encode('Aucun initCommande trouvé');
            exit;
        }

        // Retourner les initCommandes sous forme de JSON
        echo json_encode($datainitCommandes, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
