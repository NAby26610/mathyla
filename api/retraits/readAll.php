<?php
require_once('../../config/database.php'); // Corrigé le nom du fichier à inclure

if (isset($_GET)) {
    $transfertretraits = [];
    try {
        // Récupérer tous les retraits triés par ordre décroissant
        $read = ModeleClasse::getallDESC("retraits");
        if ($read) {
            foreach ($read as $transfert) {  // La variable ici est $transfert, pas $data
                // Récupérer les informations sur l'agence associée au retrait
                $agence = ModeleClasse::getoneByname('id', 'agences', $transfert['id_agence']);
                
                // Récupérer les informations sur le transfert associé au retrait
                $transfertData = ModeleClasse::getoneByname('id', 'transfert', $transfert['id_transfert']);  // Renommé à $transfertData
                
                // Construire l'objet pour le retrait avec les informations associées
                $objet = [
                    "retrait" => [
                        "id" => $transfert["id"],  // Utilisation de $transfert ici au lieu de $data
                        "statut" => $transfert["statut"] ?? 'effectué', 
                        
                        "agence" => [
                            "id_agence" => $agence["id"] ?? null,  // ID de l'agence associée
                            "libelle" => $agence["libelle"] ?? null,  // Libellé de l'agence
                            "telephone" => $agence["telephone"] ?? null,  // Téléphone de l'agence
                            "adresse" => $agence["adresse"] ?? null,  // Adresse de l'agence
                        ],
                       
                        "transfert" => [
                            "id" => $transfertData["id"],  // ID du transfert
                            "nomEnvoyeur" => $transfertData["nomEnvoyeur"] ?? null,  // Nom de l'envoyeur
                            "telEnvoyeur" => $transfertData["telEnvoyeur"] ?? null,  // Téléphone de l'envoyeur
                            "nomDestinataire" => $transfertData["nomDestinataire"] ?? null,  // Nom du destinataire
                            "telDestinataire" => $transfertData["telDestinataire"] ?? null,  // Téléphone du destinataire
                            "piece" => $transfertData["piece"] ?? null,  // Type de pièce
                            "montantEnvoyer" => $transfertData["montantEnvoyer"] ?? 0,  // Montant envoyé
                            "frais" => $transfertData["frais"] ?? 0,  // Frais associés
                            "codeTransfert" => $transfertData["codeTransfert"] ?? null,  // Code unique de transfert
                            "etatTransfert" => $transfertData["etatTransfert"] ?? null,  // État du transfert
                            "statut" => $transfertData["statut"] ?? null,  // Statut du transfert
                            "commentaire" => $transfertData["commentaire"] ?? null,  // Commentaire sur le transfert
                            "created_at" => $transfertData["created_at"] ?? null,  // Date de création du transfert
                            "created_by" => $transfertData["created_by"] ?? null,
                            "updated_at" => $transfertData["updated_at"] ?? null,  // Date de mise à jour du transfert
                        ],
                    ],
                ];

                // Ajouter l'objet construit à la liste des retraits
                array_push($transfertretraits, $objet);
            }
        } else {
            echo json_encode('Aucun retrait trouvé');
            exit;
        }

        // Retourner les retraits sous forme de JSON
        echo json_encode($transfertretraits, true);
    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
