<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datafactures = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les factures triés par ordre décroissant
        $read = ModeleClasse::getallDESC("facture");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entite
                $initVente = ModeleClasse::getoneByname('id', 'initVente', $data['id_initVente']);
                
                // Construire un objet pour le facture avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_initVente" => $initVente["id"],
                    "montant" => $data["montant"],
                    "mode_de_paiement" => $data["modePaiement"],  // Changement du nom de la clé pour éviter la duplication
                    "created_at" => $data["created_at"] ?? null,  // Date de création du facture
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du facture
                ];

                // Ajouter l'objet construit à la liste des factures
                array_push($datafactures, $objet);
            }
        } else {
            echo json_encode('Aucun facture trouvé');
            exit;
        }

        // Retourner les factures sous forme de JSON
        echo json_encode($datafactures, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
