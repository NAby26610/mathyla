<?php
require_once('../../config/database.php');

if (isset($_GET['id'])) {
    $datautilisateur = [];
    try {
        // Récupérer l'utilisateur spécifique par son ID
        $utilisateurId = $_GET['id'];
        $utilisateur = ModeleClasse::getone("utilisateurs", $utilisateurId);
        
        if ($utilisateur):
            // Construire l'objet utilisateur à retourner
            $objet = [
                "id" => $utilisateur["id"],
                "nom" => $utilisateur["nom"],
                "prenom" => $utilisateur["prenom"],
                "telephone" => $utilisateur["telephone"],
                "roles" => $utilisateur["roles"],
                "email" => $utilisateur["email"],
                "adresse" => $utilisateur["adresse"] ?? null,
                "created_at" => $utilisateur["created_at"],
                "modify_at" => $utilisateur["modify_at"],
            ];

            // Retourner l'objet utilisateur sous forme de JSON sans le mot de passe
            echo json_encode($objet, true);
        else:
            echo json_encode('Utilisateur non trouvé');
        endif;

    } catch (\Throwable $th) {
        echo json_encode($th->getMessage());
    }
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
