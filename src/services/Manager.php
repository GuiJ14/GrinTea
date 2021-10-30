<?php

namespace grintea\services;

class Manager {

	public array $UILoaders;
	public array $DAOLoaders;

	public function __construct(){
		$this->instantiateClasses('UI');
		$this->instantiateClasses('DAO');
	}

	private function instantiateClasses($type){
		$files = \scandir(dirname(__FILE__) . \DS . $type);
		foreach ($files as $file){
            if(( !is_dir(\dirname( __FILE__ ) . \DS. $type . \DS . $file))){
                $key = \explode($type, $file)[0];
                $class = __NAMESPACE__ . \DS . $type . \DS . pathinfo($file, PATHINFO_FILENAME);
                $arrayName = $type . 'Loaders';
                $this->$arrayName[$key] = new $class();
            }
		}
	}

	public function getDAOLoader($name){
		return $this->DAOLoaders[$name];
	}

	public function getUILoader($name){
		return $this->UILoaders[$name];
	}
}