<?php


require_once('../../config/database.php');

if (!empty($_POST)) :
    $_POST['mot_de_passe'] = md5($_POST['mot_de_passe']);
    extract($_POST);
  // echo json_encode($_POST);
    $response = [];
    // Essaie de te connecter
    try {
        $userAuth = ModeleClasse::loginUser('utilisateurs', 'email',$email, 'mot_de_passe',$mot_de_passe);
        if ($userAuth) :
            // Création de l'objet utilisateur avec les accès
            $response = [
                'statut' => 1,
                'message' => "Connexion réussi !",
                'idUser' => $userAuth['id'], 
                'nomComplet' => $userAuth['nom']. '' .$userAuth['prenom'],
                'access_token' => loginToken(32)  // Génération d'un token d'accès
            ];
        else :
            $response = [
                'statut' => 0,
                'message' => "Vos information ne correspondent_ pas !"
            ];
        endif;
        echo json_encode($response, true);
    } catch (\Throwable $th) {
        // Si tu n'arrive pas
        echo json_encode($th->getMessage());
    }
else :
    echo json_encode("Aucune données reçu ?");
endif;