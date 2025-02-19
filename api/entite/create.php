<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $entite = $_POST['entite'];
    unset($_POST['entite']);

    try {
        // Creation d'une entite
        
            $ajout_partenaire = ModeleClasse::add(" $entite", $_POST);
            if ($ajout_partenaire) {
                $message = "entite ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de entite.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
