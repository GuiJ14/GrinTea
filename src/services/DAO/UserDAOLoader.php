<?php

namespace grintea\services\DAO;

use models\User;
use Ubiquity\orm\DAO;

class UserDAOLoader {

	public function createUser(User $user){
		DAO::insert($user);
	}

}