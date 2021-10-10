<?php

namespace grintea\services\UI;

use Ajax\JsUtils;
use models\User;
use Ubiquity\controllers\Router;

class AdminUILoader{

	public function firstLaunch( $jquery ){
		$createDB = $jquery->semantic()->htmlButton('initConfig','Installer');
		$createDB->getOnClick(Router::path('index.initConfig'),'#response');
		$this->userCreationForm( $jquery );
	}

	private function userCreationForm( $jquery ){
		$userForm = $jquery->semantic()->dataForm('userCreation',new User());
		$userForm->setFields(['email','password','repeat_password']);
		$userForm->setCaptions(['Email','Mot de passe','Répéter le mot de passe']);
		$userForm->fieldAsInput('repeat_password');
		$jquery->postFormOnClick('#userCreationSubmit',Router::path ('index.createUser'),'userCreation','#response',[
			'hasloader'=>'internal'
		]);
	}
}