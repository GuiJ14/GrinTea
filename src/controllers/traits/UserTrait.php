<?php


namespace grintea\controllers\traits;


use models\User;
use Ubiquity\controllers\ControllerBase;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;

trait UserTrait {

	public function createUser(){
		if(URequest::isPost()){
            $user = new User();
            URequest::setPostValuesToObject($user);
            $this->loader->getDAOLoader('User')->createUser($user);
        }
	}
}