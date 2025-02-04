<?php
require_once('../../config/database.php');

if (isset($_GET['codeTransfert'])) {
    $codeTransfert = $_GET['codeTransfert'];
    $datatransfert = [];

    try {
        // Récupérer le transfert en fonction du codeTransfert
        $read = ModeleClasse::getoneByname('codeTransfert', 'transfert', $codeTransfert);
        
        if ($read) {
            // // Récupérer les informations sur l'agence associée au transfert
            // $agence = ModeleClasse::getoneByname('id', 'agences', $read['id_zone']); // Ici, on utilise id_zone pour récupérer l'agence
            
            // Récupérer les informations de la zone associée à l'agence
            $zone = ModeleClasse::getoneByname('id', 'zones', $read['id_zone']);  // Zone associée à l'agence
            
            // Construire un objet pour le transfert avec les informations associées
            $objet = [
                "transfert" => [
                    "id" => $read["id"],
                    "nomEnvoyeur" => $read["nomEnvoyeur"] ?? null,  // Nom de l'envoyeur
                    "telEnvoyeur" => $read["telEnvoyeur"] ?? null,  // Téléphone de l'envoyeur
                    "nomDestinataire" => $read["nomDestinataire"] ?? null,  // Nom du destinataire
                    "telDestinataire" => $read["telDestinataire"] ?? null,  // Téléphone du destinataire
                    "piece" => $read["piece"] ?? null,  // Type de pièce
                    "montant" => $read["montant"] ?? 0,  // Montant envoyé
                    "frais" => $read["frais"] ?? 0,  // Frais associés
                    "codeTransfert" => $read["codeTransfert"] ?? null,  // Code unique de transfert
                    "etatTransfert" => $read["etatTransfert"] ?? null,  // État du transfert
                    "statut" => $read["statut"] ?? null,  // Statut du transfert
                    "commentaire" => $read["commentaire"] ?? null,  // Commentaire sur le transfert
                    "created_at" => $read["created_at"] ?? null,  // Date de création du transfert
                    "created_by" => $data["created_by"] ?? null,
                    "updated_at" => $read["updated_at"] ?? null,  // Date de mise à jour du transfert
                ],
                "agence" => [
                    "id_agence" => $agence["id"] ?? null,  // ID de l'agence
                    "nom_agence" => $agence["libelle"] ?? null,  // Nom de l'agence
                    "adresse" => $agence["adresse"] ?? null,  // Adresse de l'agence
                    "telephone" => $agence["telephone"] ?? null,  // Téléphone de l'agence
                    "email" => $agence["email"] ?? null,  // Email de l'agence
                ],
                "zone" => [
                    "id_zone" => $zone["id"] ?? null,  // ID de la zone associée
                    "libelle" => $zone["libelle"] ?? null,  // Libellé de la zone
                ],
            ];

            // Ajouter l'objet construit à la liste des transferts
            $datatransfert = $objet;
        } else {
            echo json_encode('Aucun transfert trouvé pour ce code');
            exit;
        }

        // Retourner le transfert sous forme de JSON
        echo json_encode($datatransfert, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Code de transfert requis");
}
?>
