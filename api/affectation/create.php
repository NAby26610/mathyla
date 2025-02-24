<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $affectation = $_POST['affectation'];
    unset($_POST['affectation']);

    try {
        // Creation d'une affectation
        
            $ajout_partenaire = ModeleClasse::add(" $affectation", $_POST);
            if ($ajout_partenaire) {
                $message = "affectation ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de affectation.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
