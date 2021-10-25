<?php

namespace grintea\services\UI;

use Ajax\semantic\html\base\constants\Direction;
use models\User;
use Ubiquity\controllers\Router;
use grintea\services\UI\traits\FormElementsTrait;

class UserUILoader{
    use FormElementsTrait;

    public function userCreationForm( $jquery , $jsCallback = ''){
        $userForm = $jquery->semantic()->htmlForm('userCreationForm');
        $userForm->addItem($this->iconInputField("email", "user","text",null,"Email",[['type'=>'empty','prompt'=>'Veuillez entrer un email'],['type'=>'email','prompt'=>'{value} n\'est pas un email valide']]));
        $userForm->addItem($this->passwordInputField("password", "Mot de passe", [['type'=>'empty','prompt'=>'Veuillez entrer un mot de passe'],['type'=>'minLength[8]','prompt'=>'Le mot de passe doit faire au minimum 8 caractères']]));
        $userForm->addItem($this->passwordInputField("repeat_password", "Répéter le mot de passe", [['match[password]','Les mots de passe renseignés ne correspondent pas'],['empty','Veuillez répéter votre mot de passe']]));
        $userForm->addSubmit("submit","Créer le compte");
        $userForm->setSubmitParams(Router::path('index.createUser'), null, [
            'jsCallback'=> "data = JSON.parse(data); $jsCallback",
            'hasLoader'=> false
        ]);
        $userForm->addErrorMessage();
    }
}