<?php

require_once('../../config/database.php');

if (!empty($_POST)) :
    $_POST['mot_de_passe'] = md5($_POST['mot_de_passe']); // Cryptage du mot de passe
    extract($_POST);
    $response = [];

    try {
        // Récupérer les informations de l'utilisateur à partir de la méthode 'loginUser' de ModeleClasse
        $userAuth = ModeleClasse::loginUser('utilisateur', 'telephone', $telephone, 'mot_de_passe', $mot_de_passe);

        if ($userAuth) :
            // Utilisation de la méthode pour récupérer l'ID de l'entité de l'utilisateur
            $affectation = ModeleClasse::getIdEntiteByUtilisateur($userAuth['id']);

            if ($affectation) :
                $id_entite = $affectation['id_entite'];  // Récupérer l'ID de l'entité
            else :
                $id_entite = null;  // Si aucune affectation n'est trouvée, mettre à null
            endif;

            // Création de l'objet utilisateur avec les accès
            $response = [
                'statut' => 1,
                'message' => "Connexion réussie !",
                'idUser' => $userAuth['id'],
                'privilege' => $userAuth['privilege'],
                'nomComplet' => $userAuth['nom'] . ' ' . $userAuth['prenom'],
                'idEntite' => $id_entite,  // Ajout de l'ID de l'entité récupéré
                'access_token' => loginToken(32)  // Génération d'un token d'accès
            ];
        else :
            $response = [
                'statut' => 0,
                'message' => "Vos informations ne correspondent pas !"
            ];
        endif;

        echo json_encode($response, true);
    } catch (\Throwable $th) {
        // Si une exception se produit, affichage du message d'erreur
        echo json_encode($th->getMessage());
    }
else :
    echo json_encode("Aucune donnée reçue ?");
endif;
