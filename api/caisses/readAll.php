<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datacaisses = [];
    try {
        // Récupérer toutes les caisses triées par ordre décroissant
        $read = ModeleClasse::getallDESC("caisses");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'agence associée à la caisse
                $agence = ModeleClasse::getoneByname('id', 'agences', $data['id_agence']);
                
                // Récupérer les informations sur le transfert associé à la caisse (s'il existe)
                $transfert = null;
                if ($data['id_transfert']) {
                    $transfert = ModeleClasse::getoneByname('id', 'transfert', $data['id_transfert']);
                }
                
                // Récupérer les informations sur le retrait associé à la caisse (s'il existe)
                $retrait = null;
                if ($data['id_retrait']) {
                    $retrait = ModeleClasse::getoneByname('id', 'retraits', $data['id_retrait']);
                }
                
                // Récupérer les informations sur la dépense associée à la caisse (s'il existe)
                $depense = null;
                if ($data['id_depense']) {
                    $depense = ModeleClasse::getoneByname('id', 'depenses', $data['id_depense']);
                }

                // Construire l'objet pour la caisse avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "montant" => $data["montant"] ?? 0,  // Montant de la caisse
                    "statut" => $data["statut"] ?? 0,  // Statut de la caisse
                    "typeOperation" => $data["typeOperation"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null,

                    "agence" => [
                        "id_agence" => $agence["id"] ?? null,  // ID de l'agence associée
                        "libelle" => $agence["libelle"] ?? null,  // Libellé de l'agence
                        "telephone" => $agence["telephone"] ?? null,  // Téléphone de l'agence
                        "adresse" => $agence["adresse"] ?? null,  // Adresse de l'agence
                    ],

                    "transfert" => $transfert ? [
                        "id" => $transfert["id"] ?? null,
                        "nomEnvoyeur" => $transfert["nomEnvoyeur"] ?? null,
                        "telEnvoyeur" => $transfert["telEnvoyeur"] ?? null,
                        "nomDestinataire" => $transfert["nomDestinataire"] ?? null,
                        "telDestinataire" => $transfert["telDestinataire"] ?? null,
                        "montantEnvoyer" => $transfert["montantEnvoyer"] ?? 0,
                    ] : null,

                    "retrait" => $retrait ? [
                        "id" => $retrait["id"] ?? null,
                        "statut" => $retrait["statut"] ?? null,
                    ] : null,

                    "depense" => $depense ? [
                        "id" => $depense["id"] ?? null,
                        "types" => $depense["types"] ?? null,
                        "montant" => $depense["montant"] ?? 0,
                        "motif" => $depense["motif"] ?? null,
                    ] : null,
                ];

                // Ajouter l'objet construit à la liste des caisses
                array_push($datacaisses, $objet);
            }
        } else {
            echo json_encode('Aucune caisse trouvée');
            exit;
        }

        // Retourner les caisses sous forme de JSON
        echo json_encode($datacaisses, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
