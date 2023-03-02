<?php
namespace Modeles\Entites;

class Textes{
    private $id;
    private $text;
    private $date;
    private $date_modification;


    public function getId(){
        return $this->id;
    }

    public function setId($id){
        if(is_numeric($id)){
            $this->id = $id;
        }
        return $this;
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
}
?>