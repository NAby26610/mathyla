<?php
require_once('../../config/database.php');

if (isset($_GET['codeTransfert'])) {
    $codeTransfert = $_GET['codeTransfert'];

    try {
        // Récupérer le transfert en fonction du codeTransfert
        $read = ModeleClasse::getoneByname('codeTransfert', 'transfert', $codeTransfert);
        
        if (!$read) {
            echo json_encode(['message' => 'Aucun transfert trouvé pour ce code']);
            return;
        }

        // Récupérer les informations de l'utilisateur ayant effectué le transfert
        $utilisateur = ModeleClasse::getoneByname('id', 'utilisateurs', $read['created_by'] ?? null);

        // Récupérer les informations de l'agence associée à la zone du transfert
        $agence = ModeleClasse::getoneByname('id', 'agences', $read['id_zone'] ?? null);
        
        // Récupérer les informations de la zone associée au transfert
        $zone = ModeleClasse::getoneByname('id', 'zones', $read['id_zone'] ?? null);

        // Récupérer les informations du retrait (si disponible)
        $utilisateurRetrait = $agenceRetrait = null;
        if (!empty($read['modify_by'])) {
            $utilisateurRetrait = ModeleClasse::getoneByname('id', 'utilisateurs', $read['modify_by']);
            $agenceRetrait = ModeleClasse::getoneByname('id', 'agences', $read['id_agence'] ?? null);
        }

        // Construire l'objet à retourner
        $objet = [
            "transfert" => [
                "id" => $read["id"] ?? null,
                "nomEnvoyeur" => $read["nomEnvoyeur"] ?? null,
                "telEnvoyeur" => $read["telEnvoyeur"] ?? null,
                "nomDestinataire" => $read["nomDestinataire"] ?? null,
                "telDestinataire" => $read["telDestinataire"] ?? null,
                "piece" => $read["piece"] ?? null,
                "montant" => $read["montant"] ?? 0,
                "frais" => $read["frais"] ?? 0,
                "codeTransfert" => $read["codeTransfert"] ?? null,
                "statut" => $read["statut"] ?? null,
                "commentaire" => $read["commentaire"] ?? null,
                "created_at" => $read["created_at"] ?? null,
                "updated_at" => $read["updated_at"] ?? null,
            ],
            "utilisateur" => $utilisateur ? [
                "id" => $utilisateur["id"] ?? null,
                "nom" => $utilisateur["nom"] ?? null,
                "prenom" => $utilisateur["prenom"] ?? null,
                "telephone" => $utilisateur["telephone"] ?? null,
                "email" => $utilisateur["email"] ?? null,
            ] : null,
            "agence_transfert" => $agence ? [
                "id_agence" => $agence["id"] ?? null,
                "libelle" => $agence["libelle"] ?? null,
                "telephone" => $agence["telephone"] ?? null,
            ] : null,
            "zone" => $zone ? [
                "id_zone" => $zone["id"] ?? null,
                "libelle" => $zone["libelle"] ?? null,
            ] : null,
            "retrait" => [
                "utilisateur_retrait" => $utilisateurRetrait ? [
                    "id" => $utilisateurRetrait["id"] ?? null,
                    "nom" => $utilisateurRetrait["nom"] ?? null,
                    "prenom" => $utilisateurRetrait["prenom"] ?? null,
                    "telephone" => $utilisateurRetrait["telephone"] ?? null,
                    "email" => $utilisateurRetrait["email"] ?? null,
                ] : null,
                "agence_retrait" => $agenceRetrait ? [
                    "id_agence_retrait" => $agenceRetrait["id"] ?? null,
                    "libelle" => $agenceRetrait["libelle"] ?? null,
                    "telephone" => $agenceRetrait["telephone"] ?? null,
                ] : null,
            ]
        ];

        // Retourner l'objet en JSON
        echo json_encode($objet, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
} else {
    echo json_encode(['message' => 'Code de transfert requis']);
}
?>
