<?php

namespace grintea\services\UI;

use models\User;
use Ubiquity\controllers\Router;

class UserUILoader{

    public function userCreationForm( $jquery , $responseDiv){
        $userForm = $jquery->semantic()->dataForm('userCreation',new User());
        $userForm->setFields(['email','password','repeat_password','submit']);
        $userForm->setCaptions(['Email','Mot de passe','Répéter le mot de passe','Valider']);
        $userForm->fieldAsInput('repeat_password');
        $userForm->fieldAsSubmit('submit');
        $jquery->postFormOnClick('#userCreation-submit-0',Router::path ('index.createUser'),'userCreation', $responseDiv,[
            'hasloader'=>'internal'
        ]);
    }
}