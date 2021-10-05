<?php
namespace grinto\models;

#[Table(name: "user")]
class User{

    #[Column(name: "id",dbType: "varchar(511)")]
    #[Validator(type: "length",constraints: ["max"=>511,"notNull"=>true])]
    private $id;


    #[Column(name: "mail",dbType: "varchar(255)")]
    #[Validator(type: "email",constraints: ["notNull"=>true])]
    #[Validator(type: "length",constraints: ["max"=>255])]
    private $mail;


    #[Column(name: "pass",dbType: "varchar(511)")]
    #[Validator(type: "length",constraints: ["max"=>511,"notNull"=>true])]
    private $pass;


    #[Column(name: "role",dbType: "enum('user','superuser','admin')")]
    #[Validator(type: "notNull",constraints: [])]
    private $role;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id=$id;
    }

    public function getMail(){
        return $this->mail;
    }

    public function setMail($mail){
        $this->mail=$mail;
    }

    public function getPass(){
        return $this->pass;
    }

    public function setPass($pass){
        $this->pass=$pass;
    }

    public function getRole(){
        return $this->role;
    }

    public function setRole($role){
        $this->role=$role;
    }

    public function __toString(){
        return ($this->role??'no value').'';
    }


}