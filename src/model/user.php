<?php

class user extends activeRecord {

	private $username;
	private $email;
	private $passwordHash;

	public function __construct() {
		parent::__construct();
		$this -> email = '';
		$this -> username = '';
		$this -> passwordHash = '';
	}

	public function getId() {
		return $this -> id;
	}

	public function getUsername() {
		return $this -> username;
	}

	public function getEmail() {
		return $this -> email;
	}

	public function getPasswordHash() {
		return $this -> passwordHash;
	}

	public function setUsername($username) {
		$this -> username = $username;
	}

	public function setEmail($email) {
		$this -> email = $email;
	}

	public function setPasswordHash($passwordHash) {
		$this -> passwordHash = password_hash($passwordHash, PASSWORD_BCRYPT);
	}

	public function save() {
		self::connect();
		if (self::$db -> conn != null) {
			if ($this -> id == -1) {
				$sql = "INSERT INTO users (username, email, passwordHash) values (:username, :email, :passwordHash)";
				$stmt = self::$db -> conn -> prepare($sql);
				$result = $stmt -> execute(['username' => $this -> username, 'email' => $this -> email, 'passwordHash' => $this -> passwordHash]);

				if ($result == true) {
					$this -> id = self::$db -> conn -> lastInsertId();
					return true;
				} else {
					echo self::$db -> conn -> error;
				}
			} else {
				$sql = "UPDATE users SET username = :username, email = :email, passwordHash = :passwordHash WHERE id = :id";
				$stmt = self::$db -> conn -> prepare($sql);
				$result = $stmt -> execute(['username' => $this -> username, 'email' => $this -> email, 'passwordHash' => $this -> passwordHash, 'id' => $this -> id]);

				if ($result == true) {
					return true;
				}
			}
		} else {
			echo "Brak polaczenia\n";
		}
		return false;
	}

	static public function loadById($id) {
		self::connect();
		$sql = "SELECT * FROM users WHERE id=:id";
		$stmt = self::$db -> conn -> prepare($sql);
		$result = $stmt -> execute(['id' => $id]);
		if ($result && $stmt -> rowCount() >= 1) {
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			$loadedUser = new user();
			$loadedUser -> id = $row['id'];
			$loadedUser -> username = $row['username'];
			$loadedUser -> passwordHash = $row['passwordHash'];
			$loadedUser -> email = $row['email'];
			return $loadedUser;
		}
		return null;
	}

	static public function loadAll() {
		self::connect();
		$sql = "SELECT * FROM users";
		$result = self::$db -> conn -> query($sql);
		$returnTable = [];
		if ($result !== false && $result -> rowCount() > 0) {
			foreach ($result as $row) {
				$loadedUser = new user();
				$loadedUser -> id = $row['id'];
				$loadedUser -> username = $row['username'];
				$loadedUser -> passwordHash = $row['passwordHash'];
				$loadedUser -> email = $row['email'];
				$returnTable[] = $loadedUser;
			}
		}
		return $returnTable;
	}

	public function delete() {
		self::connect();
		if ($this -> id != -1) {
			$sql = "DELETE FROM users WHERE id=:id";
			$stmt = self::$db -> conn -> prepare($sql);
			$result = $stmt -> execute(['id' => $this -> id]);
			if ($result == true) {
				$this -> id = -1;
				return true;
			}
			return false;
		}
		return true;
	}

	static public function loadByEmail($email) {
		self::connect();
		$sql = "SELECT * FROM users WHERE email=:email";
		$stmt = self::$db -> conn -> prepare($sql);
		$result = $stmt -> execute(['email' => $email]);
		if ($result && $stmt -> rowCount() == 1) {
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			$loadedUser = new user();
			$loadedUser -> id = $row['id'];
			$loadedUser -> username = $row['username'];
			$loadedUser -> passwordHash = $row['passwordHash'];
			$loadedUser -> email = $row['email'];
			return $loadedUser;
		}
		return null;
	}

	static public function loadByUsername($username) {
		self::connect();
		$sql = "SELECT * FROM users WHERE username=:username";
		$stmt = self::$db -> conn -> prepare($sql);
		$result = $stmt -> execute(['username' => $username]);
		if ($result && $stmt -> rowCount() == 1) {
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			$loadedUser = new user();
			$loadedUser -> id = $row['id'];
			$loadedUser -> username = $row['username'];
			$loadedUser -> passwordHash = $row['passwordHash'];
			$loadedUser -> email = $row['email'];
			return $loadedUser;
		}
		return null;
	}

}
