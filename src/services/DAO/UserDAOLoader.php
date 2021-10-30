<?php

namespace grintea\services\DAO;

use models\User;
use Ubiquity\orm\DAO;
use Ubiquity\security\data\EncryptionManager;

class UserDAOLoader{

	public function createUser(User $user) {
        $encryptedPassword = EncryptionManager::encrypt($user->getPassword());
        $user->setPassword( $encryptedPassword );
        return DAO::insert( $user );
	}

    public function getById(int $id){
        return DAO::getById(User::class, $id);
    }

    public function getByEmail(string $email, bool $included = false){
        return DAO::getOne(User::class,'email = :email',$included,['email'=>$email]);
    }

}