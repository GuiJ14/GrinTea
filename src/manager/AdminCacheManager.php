<?php

namespace grinto\manager;

class AdminCacheManager {

	private function _generateSQL(){
		$sql = file_get_contents('sql');
		if(isset($sql)){
			try {
				$dbh = new \PDO('mysql:host=localhost;dbname=test', 'root', '');
				$dbh->query($sql);
			} catch (\PDOException $e) {
				print "Erreur !: " . $e->getMessage() . "<br/>";
				die();
			}
		}
	}

	private function _generateCache(){

	}

}