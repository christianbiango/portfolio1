<?php

namespace Modeles\Entites;


class Login{
    private $id;
    private $username;
    private $password;
    private $username_err;
    private $password_err;
    private $login_err;

    private $created_at;
    private $nom;
    private $prenom;
    private $fonction;
    private $last_activity;

    public function __construct(){
        $this->username_err = '';
        $this->password_err = '';
        $this->login_err = '';
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        if(is_numeric($id)){
            $this->id = $id;
        }
        return $this;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        return $this->username = $username;
    }
    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        return $this->password = $password;
    }

    public function getUsernameErr(){
        return $this->username_err;
    }

    public function setUsernameErr($username_err){
        return $this->username_err = $username_err;
    }
    public function getPasswordErr(){
        return $this->password_err;
    }

    public function setPasswordErr($password_err){
        return $this->password_err = $password_err;
    }

    public function getLoginErr(){
        return $this->login_err;
    }

    public function setLoginErr($login_err){
        return $this->login_err = $login_err;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function setCreatedAt($created_at){
        return $this->created_at = $created_at;
    }

    public function getNom(){
        return $this->nom;
    }

    public function setNom($nom){
        return $this->nom = $nom;
    }

    public function getPrenom(){
        return $this->prenom;
    }

    public function setPrenom($prenom){
        return $this->prenom = $prenom;
    }
    public function getFonction(){
        return $this->fonction;
    }

    public function setFonction($fonction){
        return $this->fonction = $fonction;
    }
    public function getLastActivity(){
        return $this->last_activity;
    }

    public function setLastActivity($last_activity){
        return $this->last_activity = $last_activity;
    }


}