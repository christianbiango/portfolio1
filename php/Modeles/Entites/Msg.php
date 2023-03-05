<?php

namespace Modeles\Entites;
if($_SERVER['PHP_SELF'] == '/portfolio/php/Modeles/Entites/Msg.php') header('Location: ../../vues/login/logout.html.php');

class Msg{
    private $id;
    private $nom;
    private $email;
    private $msg;
    private $date;
    private $traitement;
    private $traitement_date;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        if(is_numeric($id)){
            $this->id = $id;
        }
        return $this;
    }

    public function getNom(){
        return $this->nom;
    }

    public function setNom($nom){
        return $this->nom = $nom;
    }
    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        return $this->email = $email;
    }
    public function getMsg(){
        return $this->msg;
    }

    public function setMsg($msg){
        return $this->msg = $msg;
    }
    
    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        return $this->date = $date;
    }
    
    public function getTraitement(){
        return $this->traitement;
    }

    public function setTraitement($traitement){
        return $this->traitement = $traitement;
    }
    public function getTraitementDate(){
        return $this->traitement_date;
    }

    public function setTraitementDate($traitement_date){
        return $this->traitement_date = $traitement_date;
    }
}