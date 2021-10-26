<?php

namespace grintea\services\UI;

use Ajax\semantic\html\base\constants\Direction;
use models\User;
use Ubiquity\controllers\Router;
use grintea\services\UI\traits\FormElementsTrait;
use Ubiquity\translation\TranslatorManager;

class UserUILoader{
    use FormElementsTrait;

    public function userCreationForm( $jquery , $jsCallback = ''){
        $userForm = $jquery->semantic()->htmlForm('userCreationForm');
        $userForm->addItem($this->iconInputField("email", "user","text",null,TranslatorManager::trans('email',[],'grintea'),[['type'=>'empty','prompt'=>TranslatorManager::trans('no_email',[],'grintea')],['type'=>'email','prompt'=>'{value} '.TranslatorManager::trans('invalid_email',[],'grintea')]]));
        $userForm->addItem($this->passwordInputField("password", TranslatorManager::trans('password',[],'grintea'), [['type'=>'empty','prompt'=>TranslatorManager::trans('no_password',[],'grintea')],['type'=>'minLength[8]','prompt'=>TranslatorManager::trans('atLeastCharacters_password',[],'grintea')]]));
        $userForm->addItem($this->passwordInputField("repeat_password", TranslatorManager::trans('repeat_password',[],'grintea'), [['match[password]',TranslatorManager::trans('notCorresponding_password',[],'grintea')],['empty',TranslatorManager::trans('no_repeat_password',[],'grintea')]]));
        $userForm->addSubmit("submit",TranslatorManager::trans('create_account',[],'grintea'));
        $userForm->setSubmitParams(Router::path('index.createUser'), null, [
            'jsCallback'=> "data = JSON.parse(data); $jsCallback",
            'hasLoader'=> false
        ]);
        $userForm->addErrorMessage();
    }
}