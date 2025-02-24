<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datareglementFournis = [];  // Initialisation du tableau vide
    try {
        // Récupérer tous les reglementFournis triés par ordre décroissant
        $read = ModeleClasse::getallDESC("reglementFourni");

        // Vérifier si des données ont été récupérées
        if ($read && is_array($read)) {
            foreach ($read as $data) {
                // Si id_initCommande existe et n'est pas null
                if (isset($data['id_initCommande']) && $data['id_initCommande'] !== null) {
                    // Récupérer les informations sur l'entité liée à la commande
                    $initCommande = ModeleClasse::getoneByname('id', 'initCommande', $data['id_initCommande']);
                } else {
                    // Si id_initCommande est null, on peut le gérer différemment (par exemple, afficher un message ou laisser la valeur null)
                    $initCommande = null;
                }
                
                // Construire un objet pour chaque reglementFourni avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "id_initCommande" => $initCommande ? $initCommande["id"] : null, // Si initCommande existe
                    "montant" => $data["montant"],
                    "mode_de_paiement" => $data["modeRegler"], 
                    "statut" => $data["statut"], // Statut de paiement
                    "created_at" => $data["created_at"] ?? null,  // Date de création
                    "created_by" => $data["created_by"] ?? null,
                    "modify_at" => $data["modify_at"] ?? null,  // Date de modification
                ];

                // Ajouter l'objet à la liste des paiements
                array_push($datareglementFournis, $objet);
            }
        } else {
            echo json_encode('Aucun reglementFourni trouvé');
            exit;
        }

        // Appel de la fonction pour récupérer le total des paiements des fournisseurs
        $totalPaiementsFournisseurs = getTotalPaiementsFournisseurs();

        // Retourner les données sous forme de JSON avec le total des paiements
        echo json_encode([
            'total_paiements_fournisseurs' => $totalPaiementsFournisseurs, // Total des paiements
            'reglementFournis' => $datareglementFournis  // Liste des paiements
        ], JSON_PRETTY_PRINT);

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}

// Fonction pour obtenir le total des paiements des fournisseurs
function getTotalPaiementsFournisseurs()
{
    global $connect;
    try {
        // Requête pour récupérer le total des paiements
        $req = $connect->query("SELECT SUM(montant) AS total FROM reglementFourni");
        
        // Vérifier si la requête a réussi avant d'accéder aux données
        if ($req) {
            $result = $req->fetch();
            // Retourner le total ou 0 si aucun résultat
            return $result['total'] ?? 0;
        }
        return 0;
    } catch (\Throwable $th) {
        return 0; // Retourner 0 en cas d'erreur
    }
}
?>
