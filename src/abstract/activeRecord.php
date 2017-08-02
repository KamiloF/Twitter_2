<?php

abstract class activeRecord implements activeRecordInterface {

	protected $id;
	protected static $db;

	public function __construct() {
		self::connect();
		$this -> id = -1;
	}

	public static function connect() {
		if (!self::$db) {
			self::$db = new db();
			self::$db -> changeDB('twitter');
		}
		return true;
	}

	public function save() {

	}

}
