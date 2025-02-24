<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $datacaisse = [];

    try {
        // Vérifier si un id_typeOperation est passé en paramètre
        $id_typeOperation = isset($_GET['id_typeOperation']) ? intval($_GET['id_typeOperation']) : null;

        // Récupérer toutes les caisses triées par ordre décroissant
        $read = ModeleClasse::getallDESC("caisse");

        if (!empty($read)) {
            foreach ($read as $data) {
                // Récupérer le type d'opération
                $typeOperation = isset($data['id_typeOperation']) ? ModeleClasse::getoneByname('id', 'typeOperation', $data['id_typeOperation']) : null;

                // Vérifier si on filtre par type d'opération
                if ($id_typeOperation !== null && $data['id_typeOperation'] !== $id_typeOperation) {
                    continue; // Ignorer les opérations qui ne correspondent pas au filtre
                }

                // Récupérer le total des encaissements et décaissements selon l'id_typeOperation
                $totalEncaissement = ModeleClasse::getallbyNameSUMO("caisse", "id_typeOperation", 1, "montant") ?? 0;
                $totalDecaissement = ModeleClasse::getallbyNameSUMO("caisse", "id_typeOperation", 2, "montant") ?? 0;

                // Calcul du solde de la caisse
                $soldeCaisse = $totalEncaissement - $totalDecaissement;

                // Construire l'objet réponse
                $objet = [
                    "id_caisse" => $data["id"] ?? null,
                    "id_typeOperation" => $typeOperation["id"] ?? null,
                    "montant" => formatNumber1($data["montant"]),
                    "modeRegler" => $data["modeRegler"] ?? null,
                    "statut" => $data["statut"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
        

                    // Totaux calculés
                    "totalEncaissement" => formatNumber1($totalEncaissement),
                    "totalDecaissement" => formatNumber1($totalDecaissement),
                    "soldeCaisse" => formatNumber1($soldeCaisse)
                ];

                // Ajouter l'objet au tableau final
                $datacaisse[] = $objet;
            }
        } else {
            echo json_encode(["status" => 0, "message" => "Aucune caisse trouvée"], JSON_PRETTY_PRINT);
            exit;
        }

        // Retourner les données en JSON
        echo json_encode([
            "status" => 1,
            "data" => $datacaisse
        ], JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(["status" => 0, "error" => $th->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["status" => 0, "error" => "Méthode non autorisée"], JSON_PRETTY_PRINT);
}
?>
