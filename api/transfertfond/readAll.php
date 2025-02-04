<?php
require_once('../../config/database.php');

if (isset($_GET)) {
    $datatransferts = [];
    try {
        // Récupérer tous les transferts de fonds triés par ordre décroissant
        $read = ModeleClasse::getallDESC("transfert_fond");
        if ($read) {
            foreach ($read as $data) {
                // Récupérer les informations sur l'agence source
                $agenceSource = ModeleClasse::getoneByname('id', 'agences', $data['id_agenceSource']);

                // Récupérer les informations sur l'agence destinataire
                $agenceDestinataire = ModeleClasse::getoneByname('id', 'agences', $data['id_agenceDestinataire']);

                // Récupérer les informations sur la devise associée au transfert
                $devise = null;
                if ($data['id_devise']) {
                    $devise = ModeleClasse::getoneByname('id', 'devise', $data['id_devise']);
                }

                // Construire l'objet pour le transfert de fond avec les informations associées
                $objet = [
                    "id" => $data["id"],
                    "montant" => $data["montant"] ?? 0,  // Montant du transfert
                    "statut" => $data["statut"] ?? 'en attente',  // Statut du transfert
                    "commentaire" => $data["commentaire"] ?? null,  // Commentaire sur le transfert
                    "created_by" => $data["created_by"] ?? null,
                    "created_at" => $data["created_at"] ?? null,
                    "updated_at" => $data["updated_at"] ?? null,
                    "agenceSource" => $agenceSource["libelle"] ?? null,  // Libellé de l'agence source
                    "agenceDestinataire" =>  $agenceDestinataire["libelle"] ?? null,  
                    "devise" =>  $devise["libelle"] ?? null,  // Nom de la devise



                ];

                // Ajouter l'objet construit à la liste des transferts de fonds
                array_push($datatransferts, $objet);
            }
        } else {
            echo json_encode('Aucun transfert de fond trouvé');
            exit;
        }

        // Retourner les transferts de fonds sous forme de JSON
        echo json_encode($datatransferts, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>