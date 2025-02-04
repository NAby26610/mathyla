<?php
require_once('../../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') { 
    $datacaisses = [];

    try {
        // Récupérer toutes les caisses triées par ordre décroissant
        $read = ModeleClasse::getallDESC("caisses");

        if (!empty($read)) {
            foreach ($read as $data) {
                // Récupérer les informations de l'agence associée
                $agence = isset($data['id_agence']) ? ModeleClasse::getoneByname('id', 'agences', $data['id_agence']) : null;

                // Récupérer les informations du transfert (s'il existe)
                $transfert = isset($data['id_transfert']) ? ModeleClasse::getoneByname('id', 'transfert', $data['id_transfert']) : null;

                // Récupérer les informations du retrait (s'il existe)
                $retrait = isset($data['id_retrait']) ? ModeleClasse::getoneByname('id', 'retraits', $data['id_retrait']) : null;

                // Récupérer les informations de la dépense (s'il existe)
                $depense = isset($data['id_depense']) ? ModeleClasse::getoneByname('id', 'depenses', $data['id_depense']) : null;

                // Récupérer les informations de l'utilisateur (créateur de la caisse)
                $utilisateur = ModeleClasse::getoneByname('id', 'utilisateurs', $data['created_by']);

                // Calculer les sommes pour décaissements et encaissements
                $totalDecaissement = ModeleClasse::getallbyNameSUMO("caisses", "typeOperation", "sortie", "montant");
                $totalEncaissement = ModeleClasse::getallbyNameSUMO("caisses", "typeOperation", "entrer", "montant");

                // Assurer que les valeurs ne soient pas null
                $totalDecaissement = $totalDecaissement ?? 0;
                $totalEncaissement = $totalEncaissement ?? 0;

                // Calculer le solde de la caisse
                $montantTotalPaiement = $data['montant'] ?? 0;
                $soldeCaisse = $totalEncaissement - $totalDecaissement + $montantTotalPaiement;

                // Construire l'objet sous une seule structure aplatie
                $objet = [
                    // Infos caisse
                    "id_caisse" => $data["id"] ?? null,
                    "montant" => formatNumber1($data["montant"]),
                    "statut" => $data["statut"] ?? 0,
                    "typeOperation" => $data["typeOperation"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null,

                    // Infos créateur (utilisateur)
                    "nom_utilisateur" => $utilisateur["nom"] ?? null,
                    "prenom_utilisateur" => $utilisateur["prenom"] ?? null,
                    "adresse_utilisateur" => $utilisateur["adresse"] ?? null,
                    "telephone_utilisateur" => $utilisateur["telephone"] ?? null,

                    // Infos transfert (si existant)
                    "nomEnvoyeur" => $transfert["nomEnvoyeur"] ?? null,
                    "telEnvoyeur" => $transfert["telEnvoyeur"] ?? null,
                    "nomDestinataire" => $transfert["nomDestinataire"] ?? null,
                    "telDestinataire" => $transfert["telDestinataire"] ?? null,

                    // Infos retrait (si existant)
                    "statut_retrait" => $retrait["statut"] ?? null,

                    // Ajout des totaux calculés
                    'totalDecaissement' => formatNumber1($totalDecaissement),
                    'totalEncaissement' => formatNumber1($totalEncaissement),
                    'soldeCaisse' => formatNumber1($soldeCaisse)
                ];

                // Ajouter l'objet construit à la liste des caisses
                $datacaisses[] = $objet;
            }
        } else {
            echo json_encode(['message' => 'Aucune caisse trouvée']);
            exit;
        }

        // Retourner les caisses sous forme de JSON
        echo json_encode($datacaisses, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>
