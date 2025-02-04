<?php 
    require_once('../../config/database.php');
    $message = "";
    if (isset($_POST) && !empty($_POST)) {
        // Sécurisation des données
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_secure($_POST[$key]);
        }
        
        // Sécurisation et hachage du mot de passe
        $_POST['mot_de_passe'] = md5($_POST['mot_de_passe']);
        extract($_POST);
        
        // Vérification si l'email existe déjà
        try {
            $existingEmail = ModeleClasse::getoneByname('email', 'utilisateurs', $email);
            
            if ($existingEmail) {
                // Si l'email existe déjà, on renvoie un message d'erreur
                $message = "Un utilisateur avec cet e-mail existe déjà.";
            } else {
                // Si l'email n'existe pas, on procède à l'ajout
                $ajout = ModeleClasse::add("utilisateurs", $_POST);
                if (!$ajout) {
                    $message = "Enregistré avec succès.";
                } else {
                    $message = "Échec de l'enregistrement.";
                }
            }
            
            // Retourner la réponse en format JSON
            echo json_encode($message);
        } catch (\Throwable $th) {
            echo json_encode("Erreur : " . $th->getMessage());
        }
    } else {
        echo json_encode("Aucune donnée reçue.");
    }
?>
