<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $categorie = $_POST['categorie'];
    unset($_POST['categorie']);

    try {
        // Creation d'une categorie
        
            $ajout_partenaire = ModeleClasse::add(" $categorie", $_POST);
            if ($ajout_partenaire) {
                $message = "categorie ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de categorie.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
