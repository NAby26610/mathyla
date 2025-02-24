<?php
require_once('../../config/database.php');

$reponse = [];

if (isset($_POST) && !empty($_POST)) {
    extract($_POST);

    // Vérifier s'il existe déjà une commande en cours pour ce fournisseur
    $init_achats = ModeleClasse::getonebyNameDESC('initcommande', 'id_fournisseur', $id_fournisseur);
    if ($init_achats && $init_achats['statut'] == "en cours") {
        echo json_encode([
            "status" => 0, // Une commande est deja en cours pour ce fournisseur
            "message" => "Une commande en cours existe déjà pour ce fournisseur. Terminez-la d'abord.",
        ]);
        exit;
    }

    // Sécurisation des données avant insertion
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }

    // Insertion des données dans la table initcommande
    try {
        // Ajout de la commande dans la base de données
        if (ModeleClasse::add('initcommande', $_POST)) {
            $reponse = [
                'status'=>1,
                'message'=> "Commencer a ajouter au BON..."
            ];
        } else {
            $reponse = ['status' => 0, 'message' => "Échec de l'ajout de la commande."];
        }
    } catch (\Throwable $th) {
        $reponse = ['status' => 0, 'message' => $th->getMessage()];
    }

    // Retourner la réponse JSON
    echo json_encode($reponse);
} else {
    // Si aucune donnée n'est reçue
    echo json_encode(['status' => 0, 'message' => "Aucune donnée reçue."]);
}
