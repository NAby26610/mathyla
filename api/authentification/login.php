<?php


require_once('../../config/database.php');

if (!empty($_POST)) :
    $_POST['mot_de_passe'] = md5($_POST['mot_de_passe']);
    extract($_POST);
    // echo json_encode($_POST);
    $response = [];
    // Essaie de te connecter
    try {
        $userAuth = ModeleClasse::loginUser('utilisateurs', 'telephone', $telephone, 'mot_de_passe', $mot_de_passe);
        if ($userAuth) :
            // Création de l'objet utilisateur avec les accès
            $Agence = ModeleClasse::getoneByNameDesc('affectations', 'id_utilisateur', $userAuth['id']);
            $privilege = 0;
            if ($userAuth['roles'] == 'admin')
                $privilege = 1;
            else
                $privilege = 0;
            $response = [
                'message' => "Connexion réussi !",
                'id_agence' => $Agence['id_agence'] ?? null,
                'idUser' => $userAuth['id'],
                'nomComplet' => $userAuth['prenom'] . ' ' . $userAuth['nom'],
                'statut' => 1,
                'privilege' => $privilege,
                'access_token' => loginToken(32)  // Génération d'un token d'accès
            ];
        else :
            $response = [
                'statut' => 0,
                'message' => "Vos information ne correspondent_ pas !"
            ];
        endif;
        echo json_encode($response, JSON_PRETTY_PRINT);
    } catch (\Throwable $th) {
        // Si tu n'arrive pas
        echo json_encode($th->getMessage());
    }
else :
    echo json_encode("Aucune données reçu ?");
endif;
