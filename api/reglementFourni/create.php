<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $reglementFourni = $_POST['reglementFourni'];
    unset($_POST['reglementFourni']);

    try {
        // Creation d'une reglementFourni
        
            $ajout_partenaire = ModeleClasse::add(" $reglementFourni", $_POST);
            if ($ajout_partenaire) {
                $message = "reglementFourni ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de reglementFourni.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
