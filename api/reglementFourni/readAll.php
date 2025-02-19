<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datareglementFournis = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les reglementFournis triés par ordre décroissant
        $read = ModeleClasse::getallDESC("reglementFourni");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'entite
                $initCommande = ModeleClasse::getoneByname('id', 'initCommande', $data['id_initCommande']);
                
                // Construire un objet pour le reglementFourni avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_initCommande" => $initCommande["id"],
                    "montant" => $data["montant"],
                    "mode_de_paiement" => $data["modeRegler"], 
                    "statut" => $data["statut"], // Changement du nom de la clé pour éviter la duplication
                    "created_at" => $data["created_at"] ?? null,  // Date de création du reglementFourni
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de mise à jour du reglementFourni
                ];

                // Ajouter l'objet construit à la liste des reglementFournis
                array_push($datareglementFournis, $objet);
            }
        } else {
            echo json_encode('Aucun reglementFourni trouvé');
            exit;
        }

        // Retourner les reglementFournis sous forme de JSON
        echo json_encode($datareglementFournis, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
