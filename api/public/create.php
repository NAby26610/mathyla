<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $table = $_POST['table'];
    unset($_POST['table']);

    try {
        // Creation d'une table
        
            $ajout_partenaire = ModeleClasse::add(" $table", $_POST);
            if ($ajout_partenaire) {
                $message = "table ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de table.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>
