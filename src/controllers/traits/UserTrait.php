<?php


namespace grintea\controllers\traits;

use grintea\AdminManager;
use grintea\exceptions\ValidationException;
use models\Setting;
use models\User;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\URequest;

trait UserTrait {

    private function checkUserValidators(User $user){
        $errors = [];
        if(!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL))
            $errors['email'] = TranslatorManager::trans('invalid_email',[],'grintea');
        if($this->loader->getDAOLoader('User')->getByEmail($user->getEmail()))
            $errors['email'] = TranslatorManager::trans('alreadyInUse_email',[],'grintea');
        if($user->getPassword() == null || $user->getPassword() == "" && count($user->getPassword()) < 8)
            $errors['password'] = TranslatorManager::trans('rules_password',[],'grintea');
        if(count($errors) !== 0)
            throw new ValidationException( $errors );
    }

	public function createUser(){
		if(URequest::isPost()){
            $user = new User();
            URequest::setPostValuesToObject($user);

            /* check for first user creation */
            $isAdminCreatedSetting = AdminManager::isAdminAccountCreated();
            if(!$isAdminCreatedSetting){
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
                print(\json_encode(['type'=>'error', 'header'=>TranslatorManager::trans('error',[],'grintea'), 'message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE));
                return;
            }

            $this->loader->getDAOLoader('User')->createUser($user);
            DAO::save($isAdminCreatedSetting);
            print(\json_encode(['type' => 'success', 'header' => TranslatorManager::trans('success',[],'grintea'), 'message' => TranslatorManager::trans('admin_account_created',[],'grintea'), 'redirect'=>Router::path('admin.index')], JSON_UNESCAPED_UNICODE));
        }
	}
}