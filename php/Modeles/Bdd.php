<?php

namespace Modeles;
use PDO;
use Modeles\Entites\Msg;
use Modeles\Entites\Login;
use Modeles\Entites\Front;
use Modeles\Entites\Images;
use Modeles\Entites\Textes;
use Modeles\Entites\Pagination;

abstract class Bdd {
    public static function pdo(){
        return new \PDO("mysql:host=127.0.0.1:3306;dbname=portfolio", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT ]);
    }

    public static function select(string $table, string $class, $dateOption = 'desc', $traitementOption = ''){
        $pdostatement = self::pdo()->query("SELECT * FROM $table ORDER BY date $dateOption" . ($traitementOption == "Traité d'abord" || $traitementOption == "Non traité d'abord" ? ', traitement ' . $traitementOption : ''));
        //var_dump($pdostatement);
        return $pdostatement->fetchAll(PDO::FETCH_CLASS, "Modeles\Entites\\" . ucfirst($class) );       
    }

    public static function selectInfos(string $table, string $class, int $id){
        $pdostatement = self::pdo()->query("SELECT created_at, nom, prenom, fonction, last_activity FROM $table WHERE id = $id");
        return $pdostatement->fetchAll(PDO::FETCH_CLASS, "Modeles\Entites\\" . ucfirst($class) );   
    }

    public static function calcTotalPages($nb_items, $table, $class, $dateOption = 'desc', $traitementOption = ''){

        $pdostatement = self::pdo()->query("SELECT COUNT(*) AS $nb_items FROM $table " . ($traitementOption == "on" ? 'WHERE traitement = 0' : ''));
        //var_dump($pdostatement);
        return $pdostatement->fetch();  
    }
/*
    public static function itemsPerPage($table, $pagination, $class){
        $texteRequete = "SELECT * FROM $table ORDER BY date DESC LIMIT :premier, :parpage;";

        $pdostatement = self::pdo()->prepare($texteRequete);
        //var_dump($pagination);
        //var_dump($pdostatement);

        $pdostatement->bindValue(':premier', $pagination->getPremier(), PDO::PARAM_INT);
        $pdostatement->bindValue(':parpage', $pagination->getParPage(), PDO::PARAM_INT);

        $pdostatement->execute();

        return $pdostatement->fetchAll(PDO::FETCH_ASSOC); 
    }
*/
    public static function itemsPerPage($table, $pagination, $class, $dateOption = 'desc', $traitementOption = ''){
        $texteRequete = "SELECT * FROM $table " . ($traitementOption == "on" ? 'WHERE traitement = 0' : '') . " ORDER BY date " .$dateOption . " LIMIT :premier, :parpage;";
        //var_dump($texteRequete);
/*
        $pdostatement = self::pdo()->query("SELECT * FROM $table ORDER BY date $dateOption" . ($traitementOption == "Traité d'abord" || $traitementOption == "Non traité d'abord" ? ', traitement ' . $traitementOption : ''));
*/
        $pdostatement = self::pdo()->prepare($texteRequete);
        //var_dump($pagination);
        //var_dump($pdostatement);

        $pdostatement->bindValue(':premier', $pagination->getPremier(), PDO::PARAM_INT);
        $pdostatement->bindValue(':parpage', $pagination->getParPage(), PDO::PARAM_INT);

        $pdostatement->execute();

        return $pdostatement->fetchAll(PDO::FETCH_ASSOC); 
    }

    public static function loginDataVerify(string $table, Login $l){
        // Préparation de la selection
        $sql = "SELECT id, username, password FROM $table WHERE username = :username";

        if($stmt = self::pdo()->prepare($sql)){
           // var_dump($stmt);
            // Lier les var à la requête préparée
            $stmt->bindValue(":username", $l->getUsername());
            //var_dump($stmt->execute());
            // Execution de la requête préparée
            if($stmt->execute()){
                // Si l'utilisateur existe, vérifier le mot de passe
                //var_dump($stmt->rowCount());
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){

                        $row_id = $row['id'];
                        //var_dump($row_id);
                        $row_username = $row['username'];
                        //var_dump($row_username);
                        $row_hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
                        //var_dump($row_hashed_password);
                        //var_dump($l->getPassword());
                        //var_dump(password_verify($l->getPassword(), $row_hashed_password));
                        if(password_verify($l->getPassword(), $row_hashed_password)){
                            return [$row_id, $row_username];
                        } else {
                            // Le mot de passe est invalide
                            return $l->setLoginErr('Nom d\'utilisateur ou mot de passe invalide');
                        } 
                    } 
                } else{
                    // L'utilisateur n'existe pas
                    return $l->setLoginErr('Nom d\'utilisateur ou mot de passe invalide');
                }
            } else {
                return $l->setLoginErr('La requête n\'a pas fonctionné. Veuillez réessayer plus tard.');
            }
        }
    }

    public static function selectById(string $table, string $class, int $id)
    {
        $pdostatement = self::pdo()->query("SELECT * FROM $table WHERE id = " . $id);
        $pdostatement->setFetchMode(PDO::FETCH_CLASS, "Modeles\Entites\\" . ucfirst($class));
        return $pdostatement->fetch();
    }

    /*
    public static function selectByTraitement(string $table, string $class, int $traitement)
    {
        $pdostatement = self::pdo()->query("SELECT * FROM $table WHERE traitement = " . $traitement);
        $pdostatement->setFetchMode(PDO::FETCH_CLASS, "Modeles\Entites\\" . ucfirst($class));
        return $pdostatement->fetch();
    }
    */

    public static function insertMsg(Msg $msg){
        $texteRequete = "INSERT INTO contact (nom, email, msg) VALUES (:nom, :email, :msg)";

        $pdostatement = self::pdo()->prepare($texteRequete);

        $pdostatement->bindValue(":nom", $msg->getNom());
        $pdostatement->bindValue(":email", $msg->getEmail());
        $pdostatement->bindValue(":msg", $msg->getMsg());

        return $pdostatement->execute();
    }

    public static function insertProjet(Front $projet){
        $texteRequete = "INSERT INTO projet (name, img, titre, text) VALUES (:name, :img, :titre, :text)";
        //var_dump($projet->getImg());

        $pdostatement = self::pdo()->prepare($texteRequete);
        //var_dump($pdostatement);

               // $pdostatement->bindValue(":img", $projet->getImg());
              // $pdostatement->bindValue(":titre", $projet->getTitre());
              // $pdostatement->bindValue(":text", $projet->getText());

        // Nom de l'image
        $filename = $_FILES['img']['name'];
       // var_dump($filename);

        // Location
        $targetFile = './uploads/' . $filename;
        //var_dump($targetFile);

        // Extention
        $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        //var_dump($fileExtension);

        // Valider l'extension d'image
        $extensionValide = array('png', 'jpeg', 'jpg');
        //var_dump($_FILES['img']['tmp_name']);

        if(in_array($fileExtension, $extensionValide)){
            // Uploader l'image
            if(move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)){
                // Executer
                $pdostatement->bindValue(":name", $filename);
                $pdostatement->bindValue(":img", $targetFile);
                $pdostatement->bindValue(":titre", $projet->getTitre());
                $pdostatement->bindValue(":text", $projet->getText());
                //var_dump($pdostatement);
                
            return $pdostatement->execute();
            }
        }



        return $pdostatement->execute();
    }
    public static function insertTexte(Textes $projet){
        $texteRequete = "INSERT INTO textes (text) VALUES (:text)";
        //var_dump($projet->getImg());

        $pdostatement = self::pdo()->prepare($texteRequete);
 
                $pdostatement->bindValue(":text", $projet->getText());
                
            return $pdostatement->execute();
    }


    public static function insertImage(Images $i){
        $texteRequete = "INSERT INTO images (name, img) VALUES (:name, :img)";
        //var_dump($projet->getImg());

        $pdostatement = self::pdo()->prepare($texteRequete);
        //var_dump($pdostatement);

               // $pdostatement->bindValue(":img", $projet->getImg());
              // $pdostatement->bindValue(":titre", $projet->getTitre());
              // $pdostatement->bindValue(":text", $projet->getText());

        // Nom de l'image
        $filename = $_FILES['img']['name'];
       // var_dump($filename);

        // Location
        $targetFile = './uploads/' . $filename;
        //var_dump($targetFile);

        // Extention
        $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        //var_dump($fileExtension);

        // Valider l'extension d'image
        $extensionValide = array('png', 'jpeg', 'jpg');
        //var_dump($_FILES['img']['tmp_name']);

        if(in_array($fileExtension, $extensionValide)){
            // Uploader l'image
            if(move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)){
                // Executer
                $pdostatement->bindValue(":name", $filename);
                $pdostatement->bindValue(":img", $targetFile);
                //var_dump($pdostatement);
                
            return $pdostatement->execute();
            }
        }



        return $pdostatement->execute();
    }

    public static function updateProjet($projet) : bool
    {
        $texteRequete = "UPDATE projet 
                         SET name = :name, img = :img, titre = :titre, text = :text, date_modification = now() WHERE id = :id";

        $pdostatement = self::pdo()->prepare($texteRequete);
        var_dump($projet->getName());

        $targetFile = './uploads/' . $projet->getName();
        $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        $extensionValide = array('png', 'jpeg', 'jpg');
        //var_dump($projet->getDate());

        $pdostatement->bindValue(":name", $projet->getName());
        $pdostatement->bindValue(":img", $targetFile);
        $pdostatement->bindValue(":titre", $projet->getTitre());
        $pdostatement->bindValue(":text", $projet->getText());
       // $pdostatement->bindValue(":date", $projet->getDate());
        $pdostatement->bindValue(":id", $projet->getId());

        if(in_array($fileExtension, $extensionValide)){
            if(move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)){
                return $pdostatement->execute();
            }
        }
        return 'Il y a eu une erreur';
    }

    public static function updateText($projet) : bool
    {
        $texteRequete = "UPDATE textes 
                         SET text = :text, date_modification = now() WHERE id = :id";

        $pdostatement = self::pdo()->prepare($texteRequete);
        //var_dump($projet->getName());

        $pdostatement->bindValue(":text", $projet->getText());
       // $pdostatement->bindValue(":date", $projet->getDate());
        $pdostatement->bindValue(":id", $projet->getId());
                return $pdostatement->execute();

    }


    public static function updateImage($projet) : bool
    {
        $texteRequete = "UPDATE images 
                         SET name = :name, img = :img, date_modification = now() WHERE id = :id";

        $pdostatement = self::pdo()->prepare($texteRequete);
        var_dump($projet->getName());

        $targetFile = './uploads/' . $projet->getName();
        $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        $extensionValide = array('png', 'jpeg', 'jpg');
        //var_dump($projet->getDate());

        $pdostatement->bindValue(":name", $projet->getName());
        $pdostatement->bindValue(":img", $targetFile);
       // $pdostatement->bindValue(":date", $projet->getDate());
        $pdostatement->bindValue(":id", $projet->getId());

        if(in_array($fileExtension, $extensionValide)){
            if(move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)){
                return $pdostatement->execute();
            }
        }
        return 'Il y a eu une erreur';
    }

    public static function deleteLivre(/*Msg*/ $m, $table)
    {
        return self::pdo()->exec("DELETE FROM $table WHERE id = " . $m->getId());
    }
    public static function deleteAll($table)
    {
        return self::pdo()->exec("DELETE FROM $table WHERE traitement = 1");
    }

    public static function updateLastActivity($table, $colonne, $id){

        return self::pdo()->exec("UPDATE $table SET $colonne = now() WHERE id = $id");
    }

    public static function updateMsg(Msg $m, $table){
        $texteRequete = "UPDATE $table SET traitement = :traitement WHERE id = :id";
        $pdostatement = self::pdo()->prepare($texteRequete);
        $pdostatement->bindValue(':traitement', $m->getTraitement());
        $pdostatement->bindValue(':id', $m->getId());
        return $pdostatement->execute();
    }
}