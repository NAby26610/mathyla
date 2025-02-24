<?php
// Inclusion de la configuration de la base de données
require_once ('../../config/database.php');

// Initialisation de la variable $reponse qui contiendra la réponse JSON
$reponse = [];

// Vérification si le paramètre 'id_initCommande' est présent dans la requête POST
if (isset($_POST['id_initCommande'])) {
    // Récupération de l'ID de la commande initiale depuis la requête POST
    $id_initCommande = $_POST['id_initCommande'];

    // Recherche de la commande initiale dans la base de données avec l'ID fourni
    $initCommande = ModeleClasse::getone('initCommande', $id_initCommande);

    // Si la commande initiale existe
    if ($initCommande) {
        // Vérification du statut de la commande (doit être "En cours" pour pouvoir la valider)
        if ($initCommande['statut'] == "En cours") {
            // Mise à jour du statut de la commande à "valider"
            $update = ModeleClasse::update('initcommande', ['statut' => 'valider'], $id_initCommande);

            // Si la mise à jour est réussie
            if ($update) {
                // Récupération des paniers associés à cette commande
                $paniers = ModeleClasse::getallbyName("paniercommande", "id_initcommande", $id_initCommande);
                $paniersValides = [];

                foreach ($paniers as $panier) {
                    $paniersValides[] = [
                        "id" => $panier["id"],
                        "quantite" => $panier["quantite"],
                        "statut" => "valider"
                    ];
                }

                // Réponse avec statut 1 pour indiquer que la commande a été validée avec succès
                $reponse = [
                    'status' => 1,
                    'message' => "Commande validée avec succès.",
                    'data' => [
                        'id' => $id_initCommande,
                        'statut' => 'valider',
                        'paniers_valides' => $paniersValides
                    ]
                ];
            } else {
                // Si la mise à jour échoue, réponse avec statut 0 et message d'échec
                $reponse = ['status' => 0, 'message' => "Échec de la validation de la commande."];
            }
        } else {
            // Si le statut de la commande n'est pas "En cours", on renvoie un message d'erreur
            $reponse = ['status' => 0, 'message' => "Aucune commande 'En cours' trouvée."];
        }
    } else {
        // Si la commande initiale n'est pas trouvée, on renvoie un message d'erreur
        $reponse = ['status' => 0, 'message' => "Aucune commande trouvée."];
    }
} else {
    // Si le paramètre 'id_initCommande' est manquant dans la requête POST, on renvoie un message d'erreur
    $reponse = ['status' => 0, 'message' => "Le paramètre id_initCommande est manquant."];
}

// Retour de la réponse sous forme de JSON
echo json_encode($reponse, JSON_PRETTY_PRINT);
?>
