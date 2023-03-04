<?php

namespace Modeles;
use PDO;
use Modeles\Entites\Msg;
use Modeles\Entites\Login;
use Modeles\Entites\Front;
use Modeles\Entites\Images;
use Modeles\Entites\Textes;
use Modeles\Entites\Pagination;

// Toutes les constantes importantes qui seront réutilisées dans ce fichier ou les controleurs.
const UN = 1;
const IS_TRUE = true;
const IS_FALSE = false;

// Bdd class opère toutes les requêtes en base de données. Abstraite, elle ne peut pas être instanciée
abstract class Bdd {

    // La fonction pdo effectue la connexion à la base de données.
    public static function pdo(){
        return new \PDO("mysql:host=127.0.0.1:3306;dbname=portfolio", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
    }

    //  Les deux méthodes de sélection 

    /**
     * select function : Sélectionne des informations d'une table
     *
     * @param string $table : la table selectionnée
     * @param string $class : la classe dont on souhaite retourner une objet
     * @param boolean $id : l'id de l'utilisateur, dans le cas où l'on souhaite afficher les informations du compte
     * @param boolean $onlyConnectionInfos : si l'on souhaite afficher les informations de l'utilisateur ou non
     * @return void : le nouvel objet
     */
    public static function select(string $table, string $class, $id = IS_FALSE, bool $onlyConnectionInfos = IS_FALSE){
        
        // Conditions ternaires : dans le cas où on veut afficher une liste de toute la table ce sont les condition 1 qui sera interprétée, dans le cas où l'on veut uniquement les informations du compte, ce sont les conditions 2.
        $informations = ($onlyConnectionInfos == IS_FALSE ? "*" : "created_at, nom, prenom, fonction, last_activity");
        $forUserInformations = ($onlyConnectionInfos == IS_FALSE ? "" : " WHERE id = $id");

        // requête PDO
        $stmt = self::pdo()->query("SELECT $informations FROM $table ORDER BY date desc $forUserInformations");
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Modeles\Entites\\" . ucfirst($class) );       
    }

    /**
     * selectById function : sélectionne une ligne à partir d'un id. Cette sélection permettra par la suite de modifier ou supprimer un élément du back-office.
     *
     * @param string $table -> La table à sélectionner
     * @param string $class -> la classe dont l'objet sera instancié avec FETCH_CLASS
     * @param integer $id -> l'id en bdd de l'élément
     * 
     */
    public static function selectById(string $table, string $class, int $id)
    {
        $stmt = self::pdo()->query("SELECT * FROM $table WHERE id = " . $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Modeles\Entites\\" . ucfirst($class));
        return $stmt->fetch();
    }


    // Les 3 méthodes de pagination

    /**
     * Fonction calcTotalPages : calcul et retourne le nombre total de pages à partir du nombre d'éléments sélectionnés
     *
     * @param string $nb_items -> passer de préference "nb_items" en param
     * @param string $table -> La table à selectionner
     * @param string $traitementOption -> Pour trier si l'on veut que seul les messages non traités s'affichent ou non
     */
    public static function calcTotalPages(string $nb_items, string $table, string $traitementOption = ''){
        // requête PDO
        $stmt = self::pdo()->query("SELECT COUNT(*) AS $nb_items FROM $table " . self::traitementTernaire($traitementOption)); // Voir la méthode traitementTernaire
        return $stmt->fetch();  
    }

    /**
     * itemPerPage function : Sélectionne et retourne les éléments à afficher par page.
     *
     * @param string $table : La table selectionnée
     * @param Pagination $pagination : L'objet contenant les informations sur la pagination en question
     * @param string $dateOption
     * @param string $traitementOption
     * @return void : une array associative de tous les éléments de la page donnée
     */
    public static function itemsPerPage(string $table, Pagination $pagination, $dateOption = 'desc', $traitementOption = ''){

        $texteRequete = "SELECT * FROM $table " . self::traitementTernaire($traitementOption) . "ORDER BY date " . $dateOption . " LIMIT :premier, :parpage;";

        // requête préparée
        $stmt = self::pdo()->prepare($texteRequete);

        // Association de paramètres aux valeurs utilisées pour la requête. 
        $stmt->bindValue(':premier', $pagination->getPremier(), PDO::PARAM_INT);
        $stmt->bindValue(':parpage', $pagination->getParPage(), PDO::PARAM_INT);

        // Execution de la requête
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public static function traitementTernaire($traitementOption){
        $traitementTernaire = ($traitementOption == "on" ? 'WHERE traitement = 0 ' : '');
        return $traitementTernaire;
    }


    // La méthode de vérification de connexion au back-office

    /**
     * loginDataVerify function : Vérifie en base de données si l'utilisateur a rentré des informations de connexion correctes
     *
     * @param string $table : la table selectionnée
     * @param Login $l : Objet contenant les informations du formulaire de connexion envoyé
     * @return void : en cas de succès -> Une array associative contenant le nom d'utilisateur et l'id du compte, sinon -> un string d'erreur
     */
    public static function loginDataVerify(string $table, Login $l){
        // Préparation de la selection par le nom d'utilisateur
        $texteRequete = "SELECT id, username, password FROM $table WHERE username = :username";

        if($stmt = self::pdo()->prepare($texteRequete)){
            // Lier les variables à la requête préparée
            $stmt->bindValue(":username", $l->getUsername());

            // Execution de la requête préparée
            if($stmt->execute()){
                // Si l'utilisateur existe, vérifier le mot de passe

                // rowCount est une fonction php qui calcule le nombre de lignes affectées par la requête. Une seule ligne doit $etre affectée, alors on vérifie que rowCOunt == 1
                if($stmt->rowCount() == UN){

                    if($row = $stmt->fetch()){

                        // L'id de la ligne
                        $rowId = $row['id'];

                        // L'username de la ligne
                        $rowUsername = $row['username'];

                        $rowHashedPassword = $row['password'];

                        // Vérification du mot de passe.
                        if(password_verify($l->getPassword(), $rowHashedPassword)){
                            return [$rowId, $rowUsername];
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
                // La requête a échoué
                return $l->setLoginErr('La requête n\'a pas fonctionné. Veuillez réessayer plus tard.');
            }
        }
    }


    // Les 2 méthodes d'insertion

    /**
     * insertProjet function : Ajoute un projet en base de données
     *
     * @param Front $projet : contient le nom de l'image, le titre et le texte du projet que l'utilisateur souhaite insérer
     * @return void : booléen
     */
    /**
     * insertProjet function : Ajoute un projet, une image ou un texte en base de données
     *
     * @param Object $projet : contient les valeurs à ajouter
     * @param string $table : la table selectionnée
     * @param string $colonnes : les colonnes selectionnées
     * @param string $valeurs : les valeurs selectionnées
     * @return void : un booléen
     */
    public static function insertProjet(Object $projet, string $table, string $colonnes, string $valeurs){
        $texteRequete = "INSERT INTO $table ($colonnes) VALUES ($valeurs)";

        $stmt = self::pdo()->prepare($texteRequete);

        // Voir annotation de tableChoiceStatement
        $result = self::tableChoiceStatement($stmt, $projet, $table, $id = IS_FALSE);

        return $result;
    }

    /**
     * insertMsg function : Insère un message du formulaire de contact en base de données
     *
     * @param Msg $msg -> les informations du message, stocké dans l'objet $msg
     */
    public static function insertMsg(Msg $msg){
        $texteRequete = "INSERT INTO contact (nom, email, msg) VALUES (:nom, :email, :msg)";

        $stmt = self::pdo()->prepare($texteRequete);

        $stmt->bindValue(":nom", $msg->getNom());
        $stmt->bindValue(":email", $msg->getEmail());
        $stmt->bindValue(":msg", $msg->getMsg());

        return $stmt->execute();
    }

    // Les 3 méthodes de modification

    /**
     * updateProjet function : modifie un projet en base de données
     *
     * @param Object $projet : l'objet qui contient les informations qui écraseront les anciennes
     * @param string $table : la table selectionnée
     * @param string $colonnesEtValeurs : les colonnes et leurs valeurs à modifier
     * @return boolean
     */
    public static function updateProjet(Object $projet, string $table, string $colonnesEtValeurs) : bool
    {
        $texteRequete = "UPDATE $table 
                         SET " . $colonnesEtValeurs . ", date_modification = now() WHERE id = :id";

        $stmt = self::pdo()->prepare($texteRequete);

        // Si l'on souhaite modifier un projet ou image, la fonction fileManagement s'occupe de les modifier, sinon c'est simplement un texte que l'on veut modifier et il suffit de bind la valeur de ce texte avant d'exécuter la requête.
        $result = self::tableChoiceStatement($stmt, $projet, $table, $projet->getId());

        return $result;
    }

    // Met à jour la dernière date de connexion de l'utilisateur
    public static function updateLastActivity($table, $colonne, $id){

        return self::pdo()->exec("UPDATE $table SET $colonne = now() WHERE id = $id");
    }

    // Toggle le status traité ou non traité des messages
    public static function updateTraitementMsg(Msg $m, $table){
        $texteRequete = "UPDATE $table SET traitement = :traitement WHERE id = :id";
        $stmt = self::pdo()->prepare($texteRequete);
        $stmt->bindValue(':traitement', $m->getTraitement());
        $stmt->bindValue(':id', $m->getId());
        return $stmt->execute();
    }

    // La méthode de suppression
    public static function deleteItem($item, string $table, $deleteAllCheckedMessages = IS_FALSE)
    {
        return self::pdo()->exec("DELETE FROM $table WHERE " . ($deleteAllCheckedMessages == IS_FALSE ? 'id = ' . $item->getId() : 'traitement = 1') );
    }

    // Les 2 méthodes de soutient aux méthodes d'insertion et de modification
        /**
     * tableChoiceStatement function : Méthode de soutient aux différentes méthodes d'insertion et de modification. Selon la table selectionnée cette méthode insert ou modifie un texte, une image ou les deux en base de données.
     *
     * @param [type] $stmt : la requête préparée par la méthode ayant appelé tableChoiceStatement. Cette requête sera exécutée par la méthode fileManagement si les données du formulaire contiennent une image, sinon $stmt sera exécutée par tableChoiceStatement
     * @param Object $projet : le projet déjà passé en paramètre dans la méthode ayant appelé tableChoiceStatement
     * @param string $table : la table selectionnée déjà passée en paramètre dans la méthode ayant appelé tableChoiceStatemebt
     * @param boolean $id : dans le cas où l'on souhaite modifier une image un projet ou un texte, l'id sera celui de la ligne concernée
     */
    public static function tableChoiceStatement($stmt, Object $projet, string $table, $id = IS_FALSE){
        if($table == 'projet' || $table == 'images'){
            // Si l'on souhaite insérer ou modifier dans projet ou image, la fonction fileManagement s'occupe de gérer et d'insérer ou modifier l'image, sinon c'est simplement un texte que l'on veut ajouter et il suffit de bind la valeur de ce texte avant d'exécuter la requête.
            $management = self::fileManagement($stmt, $projet, $table, $id);
            return $management;
        } else if($table == 'textes'){
            $stmt->bindValue(":text", $projet->getText());
            // Cette structure conditionnelle permet de sélectionner une ligne précisément lorsque l'on cherche à modifier un texte
            if($id != false) $stmt->bindValue(":id", $projet->getId());
            return $stmt->execute();
        } 
    }

    /**
     * fileManagement function : méthode de soutient à la méthode tableChoiceStatement. Si une image est à insérer en base de données, fileManagement s'en occupe
     *
     * @param [type] $stmt : le pdostatement préparé par la méthode ayant appelé tableChoiceStatement et fileManagement
     * @param Object $projet : le projet déjà passé en paramètre dans tableChoiceStatement
     * @param string $table : La table déjà passée en paramètre dans tableChoiceStatement
     * @return void : un booléen
     */
    public static function fileManagement($stmt, Object $projet, string $table, $id = IS_FALSE){
                // Nom de l'image
                $filename = $_FILES['img']['name'];

                // Location
                $targetFile = './uploads/' . $filename;
        
                // Extention
                $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
                $fileExtension = strtolower($fileExtension);
        
                // Valider l'extension d'image
                $extensionValide = array('png', 'jpeg', 'jpg','gif','webp');
        
                if(in_array($fileExtension, $extensionValide)){
                    // Uploader l'image
                    if(move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)){
                        // Executer
                        $stmt->bindValue(":name", $filename);
                        $stmt->bindValue(":img", $targetFile);

                        // Dans le cas où c'est un projet entier qu'on veut insérer, il y aura aussi un titre et un texte à insérer en plus de l'image et de son nom
                        if($table == 'projet'){
                            $stmt->bindValue(":titre", $projet->getTitre());
                            $stmt->bindValue(":text", $projet->getText());
                        }
                        // Cette structure conditionnelle permet de sélectionner une ligne précisément lorsque l'on cherche à modifier une image simple ou celle d'un projet
                        if($id != false){
                            $stmt->bindValue(":id", $projet->getId());
                        }
                        
                    return $stmt->execute();
                    }
                }
                return "Il y a eu une erreur";
    }

    // Félicitations pour être arrivé jusque-là...
}