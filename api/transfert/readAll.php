<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datatransferts = [];
    try {
        // Récupérer tous les transferts triés par ordre décroissant
        $read = ModeleClasse::getallDESC("transfert");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur la zone associée au transfert
                $zone = ModeleClasse::getoneByname('id', 'zones', $data['id_zone']);
                
                // Construire un objet pour le transfert avec les informations associées
                $objet = [
                    
                    
                        "id" => $data["id"],
                        "nomEnvoyeur" => $data["nomEnvoyeur"] ?? null,  // Nom de l'envoyeur
                        "telEnvoyeur" => $data["telEnvoyeur"] ?? null,  // Téléphone de l'envoyeur
                        "nomDestinataire" => $data["nomDestinataire"] ?? null,  // Nom du destinataire
                        "telDestinataire" => $data["telDestinataire"] ?? null,  // Téléphone du destinataire
                        "piece" => $data["piece"] ?? null,  // Type de pièce
                        "montant" => $data["montant"] ?? 0,  // Montant envoyé
                        "frais" => $data["frais"] ?? 0,  // Frais associés
                        "codeTransfert" => $data["codeTransfert"] ?? null,  // Code unique de transfert
                        "etatTransfert" => $data["etatTransfert"] ?? null,  // État du transfert
                        "statut" => $data["statut"] ?? null,  // Statut du transfert
                        "commentaire" => $data["commentaire"] ?? null,  // Commentaire sur le transfert
                        "created_at" => $data["created_at"] ?? null,  // Date de création du transfert
                        "created_by" => $data["created_by"] ?? null,
                        "updated_at" => $data["updated_at"] ?? null,  // Date de mise à jour du transfert
                        "zone" =>  $zone["libelle"] ?? null,  // Libellé de la zone
                        
                     
                            
                        
                
                ];

                // Ajouter l'objet construit à la liste des transferts
                array_push($datatransferts, $objet);
            }
        } else {
            echo json_encode('Aucun transfert trouvé');
            exit;
        }

        // Retourner les transferts sous forme de JSON
        echo json_encode($datatransferts, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
