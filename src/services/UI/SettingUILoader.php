<?php

namespace grintea\services\UI;
use grintea\services\UI\traits\FormElementsTrait;
use Ubiquity\controllers\Router;

class SettingUILoader{
    use FormElementsTrait;

    public function settingsForm( $jquery ){
        $settingsForm = $jquery->semantic()->htmlForm('settingsForm');
        $settingsForm->addItem($this->iconInputField("email", "user","text",null,"Email",[['type'=>'empty','prompt'=>'Veuillez entrer un email'],['type'=>'email','prompt'=>'{value} n\'est pas un email valide']]));
        $settingsForm->addSubmit("submit","Valider");
        $settingsForm->setSubmitParams(Router::path('index.createUser'), null, [
            'jsCallback'=>'data = JSON.parse(data); ajaxCallback(data, setMessage.bind(null, document.getElementById("response"), data), next);',
            'hasLoader'=>false
        ]);
        $settingsForm->addErrorMessage();
    }

}