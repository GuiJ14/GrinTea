<?php


namespace grintea\controllers\traits;

use grintea\AdminManager;
use grintea\exceptions\ValidationException;
use models\Setting;
use models\User;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;

trait UserTrait {

    private function checkUserValidators(User $user){
        $errors = [];
        if(!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL))
            $errors['email'] = 'Cette email n\'est pas valide';
        if($this->loader->getDAOLoader('User')->getByEmail($user->getEmail()))
            $errors['email'] = 'Cette email est déjà utilisé';
        if($user->getPassword() == null || $user->getPassword() == "" && count($user->getPassword()) < 8)
            $errors['password'] = 'Votre mot de passe ne respecte pas les règles';
        if(count($errors) !== 0)
            throw new ValidationException( $errors );
    }

	public function createUser(){
		if(URequest::isPost()){
            $user = new User();
            URequest::setPostValuesToObject($user);

            /* check for first user creation */
            $isAdminCreatedSetting = AdminManager::isAdminAccountCreated();
            $isAdminCreatedUser = $this->loader->getDAOLoader('User')->getById(1);
            if(!$isAdminCreatedSetting && !filter_var($isAdminCreatedUser, FILTER_VALIDATE_BOOLEAN)){
                $user->setGroups(1);
                $isAdminCreatedSetting = new Setting();
                $isAdminCreatedSetting->setType('isAdminAccountCreated');
                $isAdminCreatedSetting->setValue('true');
            }

            /* if mail or password don't respect validation */
            try {
                $this->checkUserValidators($user);
            }
            catch(\Exception $e){
                print(\json_encode(['type'=>'error','header'=>'Erreur','message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE));
                return;
            }

            $this->loader->getDAOLoader('User')->createUser($user);
            DAO::save($isAdminCreatedSetting);
            print(\json_encode(['type' => 'success', 'header' => 'Succès', 'message' => 'Compte admin créé'], JSON_UNESCAPED_UNICODE));
        }
	}
}