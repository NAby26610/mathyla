<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $typeOperation = $_POST['typeOperation'];
    unset($_POST['typeOperation']);

    try {
        // Creation d'une typeOperation
        
            $ajout_partenaire = ModeleClasse::add(" $typeOperation", $_POST);
            if ($ajout_partenaire) {
                $message = "typeOperation ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de typeOperation.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
