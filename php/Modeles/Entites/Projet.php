<?php
namespace Modeles\Entites;


class Projet{
    private $id;
    private $name;
    private $img;
    private $titre;
    private $text;
    private $date;
    private $date_modification;
    private $oldFile;


    public function getId(){
        return $this->id;
    }

    public function setId($id){
        if(is_numeric($id)){
            $this->id = $id;
        }
        return $this;
    }
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        return $this->name = $name;
    }

    public function getImg(){
        return $this->img;
    }

    public function setImg($img){
        return $this->img = $img;
    }

    public function getTitre(){
        return $this->titre;
    }

    public function setTitre($titre){
        return $this->titre = $titre;
    }

    public function getText(){
        return $this->text;
    }

    public function setText($text){
        return $this->text = $text;
    }
    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        return $this->date = $date;
    }
    public function getDateModification(){
        return $this->date_modification;
    }

    public function setDateModification($date_modification){
        return $this->date_modification = $date_modification;
    }


    public function getOldFile(){
        return $this->oldFile;
    }

    public function setOldFile($oldFile){
        return $this->oldFile = $oldFile;
    }
}
?>