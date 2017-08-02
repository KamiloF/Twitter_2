<?php

interface activeRecordInterface {

	public function getId();

	public function save();

	public function delete();

	static public function loadAll();

	static public function loadById($id);
}
