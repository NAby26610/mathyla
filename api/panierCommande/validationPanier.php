<?php
// Inclusion de la configuration de la base de données
require_once('../../config/database.php');

// Initialisation de la variable $reponse qui contiendra la réponse JSON
$reponse = [];

// Vérification si le paramètre 'id_initcommande' est présent dans le corps de la requête POST
$data = json_decode(file_get_contents('php://input'), true);  // Lecture du corps de la requête POST

if (isset($data['id_initcommande'])) {
    // Récupération de l'ID de la Commande initial depuis le corps de la requête POST
    $id_initCommande = $data['id_initcommande'];

    // Vérification que l'ID n'est pas vide ou nul
    if (!empty($id_initCommande)) {
        // Recherche de l'Commande initial dans la base de données avec l'ID fourni
        $initCommande = ModeleClasse::getone('initcommande', $id_initCommande);
        
        // Si l'Commande initial existe
        if ($initCommande) {
            // Vérification du statut de l'Commande (doit être "En cours" pour pouvoir le valider)
            if (strtolower($initCommande['statut']) == "en cours") {
                // Mise à jour du statut de l'Commande à "validée"
                $update = ModeleClasse::update('initcommande', ['statut' => 'valider'], $id_initCommande);

                // Vérification si la mise à jour a réussi
                if ($update > 0) {
                    // Si la mise à jour réussit, on renvoie la réponse de succès
                    $reponse = [
                        'status' => 1, // Statut de réussite
                        'message' => "Commande validée avec succès.", // Message de succès
                        'data' => [ // Données supplémentaires sur la Commande validée
                            'id' => $id_initCommande, // ID de la Commande
                            'statut' => 'valider' // Nouveau statut de la Commande
                        ]
                    ];

                    // Créer une nouvelle commande si nécessaire
                    $newCommande = ModeleClasse::add('initcommande', ['statut' => 'en cours']);  // Exemple pour créer une nouvelle commande
                    if ($newCommande) {
                        $reponse['data']['new_commande_id'] = $newCommande['id']; // ID de la nouvelle commande
                    }

                } else {
                    // Si la mise à jour échoue, on renvoie un message d'échec
                    $reponse = ['status' => 0, 'message' => "Aucune modification n'a été effectuée sur la Commande."];
                }
            } else {
                // Si le statut de l'Commande n'est pas "En cours", on renvoie un message d'erreur
                $reponse = ['status' => 0, 'message' => "Aucune Commande 'En cours' trouvée."];
            }
        } else {
            // Si l'Commande initial n'est pas trouvée, on renvoie un message d'erreur
            $reponse = ['status' => 0, 'message' => "Aucune Commande trouvée avec cet ID."];
        }
    } else {
        // Si l'ID est manquant ou nul
        $reponse = ['status' => 0, 'message' => "L'ID de la Commande est manquant ou invalide."];
    }
} else {
    // Si le paramètre 'id_initcommande' est manquant dans le corps de la requête POST, on renvoie un message d'erreur
    $reponse = ['status' => 0, 'message' => "Le paramètre id_initcommande est manquant."];
}

// Retour de la réponse sous forme de JSON
echo json_encode($reponse);
?>
