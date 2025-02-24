<?php

class ModeleClasse

{

    // Méthode pour récupérer l'ID de l'entité à partir de la table 'affectation'
    public static function getIdEntiteByUtilisateur($id_utilisateur)
    {
        global $connect;

        // Requête pour récupérer l'ID de l'entité de l'utilisateur
        $req = $connect->prepare("SELECT id_entite FROM affectation WHERE id_utilisateur = :id_utilisateur LIMIT 1");
        $req->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $req->execute();

        // Récupérer le résultat
        $result = $req->fetch(PDO::FETCH_ASSOC);

        return $result; // Retourner le tableau contenant l'ID de l'entité
    }
    static function getallDESC($table)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table);
        $result = $req->fetchAll();
        return $result;
    }

    static function getallGROUP($table, $key)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table . " GROUP BY " . $key);
        $result = $req->fetchAll();
        return $result;
    }

    // GROUP CLAUSE
    static function getallGROUP_clause($table, $name, $value, $key)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name . "= ?" . " GROUP BY " . $key);
        $req->execute([$value]);
        $result = $req->fetchAll();
        return $result;
    }

    static function getall($table)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table);
        $result = $req->fetchAll();
        return $result;
    }

    // all SUM 
    static function getallSUM($table, $var)
    {
        global $connect;
        $req = $connect->query("SELECT *, SUM($var) FROM " . $table);
        $result = $req->fetchAll();
        return $result;
    }

    // GeallByName egality
    static function getallbyName($table, $name, $value)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name . "= ?");
        $req->execute([$value]);
        $result = $req->fetchAll();
        return $result;
    }

    // GeallByName egality
    static function getallbyNameSUM($table, $name, $value, $clause)
    {
        global $connect;
        $req = $connect->prepare("SELECT *, SUM($clause) FROM " . $table . " WHERE " . $name . "= ?");
        $req->execute([$value]);
        $result = $req->fetchAll();
        return $result;
    }

    // GeallByName egality 2
    static function getallbyNameSUM2Clause($table, $name, $value, $clause, $champ, $val)
    {
        global $connect;
        $req = $connect->prepare("SELECT *, SUM($clause) FROM " . $table . " WHERE " . $name . "= ? AND " . $champ . "= ?");
        $req->execute([$value, $val]);
        $result = $req->fetchAll();
        return $result;
    }

    // GeallByName egality
    static function getallbyName2Clause($table, $name, $value1, $name2, $value2)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name . "= ? AND " . $name2 . "= ? ");
        $req->execute([$value1, $value2]);
        $result = $req->fetchAll();
        return $result;
    }

    // GeallByName egality (Inter)
    static function getallbyName2ClauseIntervalle($table, $name, $value1, $name2, $value2, $operateur1, $operateur2)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name . $operateur1 . " ? AND " . $name2 . $operateur2 . " ? ");
        $req->execute([$value1, $value2]);
        $result = $req->fetchAll();
        return $result;
    }


    // GeallByName2Clause
    static function getallbyNameIntervall_($table, $name1, $value1, $name2, $value2, $clause, $val)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name1 . ">= ? AND " . $name2 . "<= ? AND " . $clause . "= ?");
        $req->execute([$value1, $value2, $val]);
        $result = $req->fetchAll();
        return $result;
    }


    // GeallByName
    static function getallbyNameIntervall($table, $name1, $value1, $name2, $value2)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name1 . ">= ? AND " . $name2 . "<= ? ");
        $req->execute([$value1, $value2]);
        $result = $req->fetchAll();
        return $result;
    }

    // GeallByName different by clause
    static function getallbyNameDiff($table, $name, $value)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name . "!= ?");
        $req->execute([$value]);
        $result = $req->fetchAll();
        return $result;
    }


    // LE DERNIER ELEMENT
    static function getallbyNameDESC($table, $name, $value)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table . " WHERE " . $name . "=" . $value . " ORDER BY id DESC");
        $result = $req->fetchAll();
        return $result;
    }

    // GETAll GROUP
    static function getallbyName_group($table, $name, $value, $var)
    {
        global $connect;
        $req = $connect->query("SELECT *, SUM($var) FROM " . $table . " WHERE " . $name . "=" . $value);
        $result = $req->fetchAll();
        return $result;
    }



    static function getallJoin2($tablepk, $tablefk)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . ".id=" . $tablefk . ".id" . $tablepk);
        $result = $req->fetchAll();
        return $result;
    }

    // Jointure condition
    static function getallJoin_clause($tablepk, $pk, $tablefk, $fk)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk);
        $result = $req->fetchAll();
        return $result;
    }


    // Jointure condition
    static function getallJoin_clause2Where($tablepk, $pk, $tablefk, $fk, $champ, $val)
    {
        global $connect;
        $req = $connect->query("SELECT *, $tablepk.created_at FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " AND " . $champ . "=" . $val);
        $result = $req->fetchAll();
        return $result;
    }

    // Jointure condition
    static function getallJoin_clause2($tablepk, $pk, $tablefk, $fk, $champ, $val)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " AND " . $champ . "=" . $val);
        $result = $req->fetchAll();
        return $result;
    }

    // Jointure condition CAISSE_FOURNISSEUR
    static function getallJoin_clause2Where_($tablepk, $pk, $tablefk, $fk, $champ, $val)
    {
        global $connect;
        $req = $connect->query("SELECT *, $tablepk.created_at FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " AND " . $champ . "=" . $val);
        $result = $req->fetchAll();
        return $result;
    }

    // Jointure condition
    static function getallJoin_clause2WhereItem($tablepk, $pk, $tablefk, $fk, $champ, $val, $group)
    {
        global $connect;
        $req = $connect->query("SELECT *, $tablepk.created_at FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " AND " . $champ . "=" . $val . " GROUP BY " . $group);
        $result = $req->fetchAll();
        return $result;
    }



    // Jointure condition_id_
    static function getallJoin_clause_OrderbyID($tablepk, $pk, $tablefk, $fk)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " ORDER BY " . $tablepk . ".id DESC");
        $result = $req->fetchAll();
        return $result;
    }

    // Jointure condition
    static function getallJoin_clause2WhereSum($tablepk, $pk, $tablefk, $fk, $champ, $val, $group, $sum)
    {
        global $connect;
        $req = $connect->query("SELECT *, SUM($sum) FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " AND " . $champ . "=" . $val . " GROUP BY " . $group);
        $result = $req->fetchAll();
        return $result;
    }


    // Jointure condition_group
    static function getallJoin_clause_group($tablepk, $pk, $tablefk, $fk, $group, $var)
    {
        global $connect;
        $req = $connect->query("SELECT *, SUM($var) FROM " . $tablepk . " INNER JOIN " . $tablefk . " WHERE " . $tablepk . "." . $pk . "=" . $tablefk . "." . $fk . " GROUP BY " . $group);
        $result = $req->fetchAll();
        return $result;
    }

    //    static function sendJson($info){
    //     }

    // DELETE CLAUSE
    static function delete_clause($table, $champ, $id)
    {
        global $connect;
        $connect->query("DELETE FROM " . $table . " WHERE " . $champ . "=" . $id);
    }

    // CHECK USER INFO
    static function checkUserInfo($table, $username, $motDePasse)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE username=? AND motDePasse=?");
        $req->execute([$username, $motDePasse]);
        $result = $req->fetch();
        return $result;
    }


    // CHECK USER INFO
    static function checkUserWithPhone($table, $username, $motDePasse)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE tel=? AND motDePasse=?");
        $req->execute([$username, $motDePasse]);
        $result = $req->fetch();
        return $result;
    }


    // DELETE
    static function delete($id, $table)
    {
        global $connect;
        $connect->query("DELETE FROM " . $table . " WHERE id=" . $id);
    }


    // DELETE
    static function deleteClause($clause, $value, $table)
    {
        global $connect;
        $connect->query("DELETE FROM " . $table . " WHERE " . $clause . "= " . $value);
    }

    // UPDATE SIMPLE
    static function updateClause($table, $champ, $value, $clause, $clauseVal)
    {
        global $connect;
        $connect->query("UPDATE " . $table . " SET " . $champ . " = " . $value . " WHERE " . $clause . "=" . $clauseVal);
    }

    static function getone($table, $id)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table . " WHERE id=" . $id);
        $result = $req->fetch();
        return $result;
    }

    static function getoneDesc($table)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table . " ORDER BY id DESC LIMIT 1");
        $result = $req->fetch();
        return $result;
    }

    // DESC...
    static function getoneByNameDesc($table, $name, $value)
    {
        global $connect;
        $req = $connect->query("SELECT * FROM " . $table . " WHERE " . $name . "=" . $value . " ORDER BY id DESC LIMIT 1");
        $result = $req->fetch();
        return $result;
    }

    static function getoneByname($name, $table, $value)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $name . "= ?");
        $req->execute([$value]);
        $result = $req->fetch();
        return $result;
    }
    // Se connecter
    static function loginUser($table, $champ1, $value1, $champ2, $value2)
    {
        global $connect;
        $req = $connect->prepare("SELECT * FROM " . $table . " WHERE " . $champ1 . "= ? AND " . $champ2 . "= ?");
        $req->execute([$value1, $value2]);
        $result = $req->fetch();
        return $result;
    }

    public static function add($table, $post)
    {
        $dat = [];
        $names = "";
        foreach ($post as $p => $v) {
            array_push($dat, $v);
            $names .= htmlspecialchars(htmlentities($p)) . "=?,";
        }
        $names = "INSERT INTO " . $table . " SET " . substr($names, 0, -1);
        global $connect;
        $req = $connect->prepare($names);
        if ($req->execute($dat)) {
            return true;
        } else {
            return false;
        }
    }


    public static function update($table, $post, $id): void
    {
        $dat = [];
        $names = "";
        foreach ($post as $p => $v) {
            array_push($dat, $v);
            $names .= $p . "=?,";
        }
        array_push($dat, $id);
        $names = "UPDATE " . $table . " SET " . substr($names, 0, -1) . "WHERE id=?";
        global $connect;
        $req = $connect->prepare($names);
        $req->execute($dat);
    }
    // Fonction pour calculer la somme de la colonne spécifiée
    static function getallbyNameSUMO($table, $name, $value, $clause)
    {
        global $connect;
        // Préparer la requête SQL pour calculer la somme
        $req = $connect->prepare("SELECT SUM($clause) AS total FROM " . $table . " WHERE " . $name . "= ?");
        $req->execute([$value]);

        // Récupérer la première ligne de résultats (ici, ce sera uniquement la somme)
        $result = $req->fetch();

        // Retourner la somme de la colonne
        return $result['total'] ?? 0;  // Retourne 0 si aucune somme n'est trouvée
    }
    static function getallSUMCOLONNE($table, $var)
    {
        global $connect;
        // Modifier la requête pour obtenir uniquement la somme
        $req = $connect->query("SELECT SUM($var) AS total FROM " . $table);

        // Récupérer le résultat, qui contiendra uniquement la somme
        $result = $req->fetch();

        // Retourner la somme (si elle existe, sinon retourner 0)
        return $result['total'] ?? 0;
    }

    static function getTotalParJour($table, $name, $value, $clause)
    {
        global $connect;
        // Requête SQL pour calculer le total des montants en fonction de la date du jour et des autres critères
        $req = $connect->prepare("SELECT SUM($clause) AS total FROM " . $table . " WHERE " . $name . "= ? AND DATE(created_at) = CURDATE()");
        $req->execute([$value]);

        // Récupérer le résultat de la somme
        $result = $req->fetch();

        return $result['total'] ?? 0;  // Si aucun résultat, retourne 0
    }
    static function getTotalJournalier($table, $var)
    {
        global $connect;
        // Modifier la requête pour obtenir la somme du montant spécifique pour la date du jour
        $req = $connect->prepare("SELECT SUM($var) AS total FROM " . $table . " WHERE DATE(created_at) = CURDATE()");
        $req->execute();

        // Récupérer le résultat de la somme
        $result = $req->fetch();

        // Retourner la somme (si elle existe, sinon retourner 0)
        return $result['total'] ?? 0;
    }

    static function getNombreProprietesDisponibles()
    {
        global $connect;

        // Requête SQL pour récupérer le nombre de propriétés disponibles
        $req = $connect->query("SELECT COUNT(*) AS total FROM proprietes WHERE statut = 'disponible'");

        // Récupérer le résultat et retourner la somme
        $result = $req->fetch();

        // Retourner le nombre de propriétés disponibles (0 si aucune donnée)
        return $result['total'] ?? 0;
    }


    static function getNombreProprietesTotal()
    {
        global $connect;
        // Requête pour obtenir le nombre total de propriétés
        $req = $connect->query("SELECT COUNT(*) AS total FROM proprietes");

        // Récupérer le résultat
        $result = $req->fetch();

        // Retourner le total (si il existe, sinon retourner 0)
        return $result['total'] ?? 0;
    }

    static function getNombreReservationsTotal()
    {
        global $connect;
        // Requête pour obtenir le nombre total de réservations
        $req = $connect->query("SELECT COUNT(*) AS total FROM reservations");

        // Récupérer le résultat
        $result = $req->fetch();

        // Retourner le total (si il existe, sinon retourner 0)
        return $result['total'] ?? 0;
    }


    static function getTotalCommandesParFournisseur($fournisseurId)
    {
        global $connect;

        // Vérifier si l'ID du fournisseur est valide
        if (!is_numeric($fournisseurId)) {
            return 0; // Retourne 0 si l'ID est invalide
        }

        // Préparer et exécuter la requête
        $req = $connect->prepare("SELECT COUNT(*) AS total FROM initCommande WHERE id_fournisseur = ?");
        $req->execute([$fournisseurId]);

        // Récupération du résultat
        $result = $req->fetch();

        return $result['total'] ?? 0;
    }

    function getTotalVentesParClient()
    {
        global $connect;

        // Requête pour obtenir la somme des ventes par client
        $req = $connect->query("SELECT id_client, SUM(montant) AS total_ventes FROM initVente GROUP BY id_client");

        // Récupérer les résultats
        $result = $req->fetchAll();

        // Retourner les résultats ou un tableau vide si aucun résultat trouvé
        return $result ?? [];
    }
    static function getoneTotalSommebyNature($tablepk, $pk, $tablefk, $fk, $sumField, $natureField, $natureValue, $id_devise)
    {
        global $connect;

        // Préparer la requête avec jointure pour récupérer la somme des montants selon la nature
        $req = $connect->prepare("
        SELECT SUM(`$sumField`) AS total
        FROM `$tablepk`
        INNER JOIN `$tablefk` ON `$tablepk`.`$pk` = `$tablefk`.`$fk`
        WHERE `$tablefk`.`$natureField` = ? AND `$tablepk`.`id_devise` = ?
    ");

        // Exécuter la requête avec les paramètres fournis
        $req->execute([$natureValue, $id_devise]);

        // Récupérer le résultat de la somme
        $result = $req->fetch();

        // Retourner la somme ou 0 si aucun résultat
        return $result['total'] ?? 0;
    }
}
