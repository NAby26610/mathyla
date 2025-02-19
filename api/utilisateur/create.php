<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $utilisateur = $_POST['utilisateur'];
    unset($_POST['utilisateur']);

    try {
        // Creation d'une utilisateur
        
            $ajout_partenaire = ModeleClasse::add(" $utilisateur", $_POST);
            if ($ajout_partenaire) {
                $message = "utilisateur ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de utilisateur.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
