<?php

class tweet extends activeRecord {

	private $userId;
	private $text;
	private $creationDate;

	public function getId() {
		return $this -> id;
	}

	public function getUserId() {
		return $this -> userId;
	}

	public function getText() {
		return $this -> text;
	}

	public function getCreationDate() {
		return $this -> creationDate;
	}

	public function setUserId($userId) {
		$this -> userId = $userId;
	}

	public function setText($text) {
		$this -> text = $text;
	}

	public function setCreationDate($creationDate) {
		$this -> creationDate = $creationDate;
	}

	public function __construct() {
		parent::__construct();
		$this -> id = -1;
		$this -> userId = null;
		$this -> text = '';
		$this -> creationDate = null;
	}

	public function save() {
		self::connect();
		if (self::$db -> conn != null) {
			if ($this -> id == -1) {
				$sql = "INSERT INTO tweets (userId, text, creationDate) values (:userId, :text, :creationDate)";
				$stmt = self::$db -> conn -> prepare($sql);
				$result = $stmt -> execute(['userId' => $this -> userId, 'text' => $this -> text, 'creationDate' => $this -> creationDate]);

				if ($result == true) {
					$this -> id = self::$db -> conn -> lastInsertId();
					return true;
				} else {
					echo self::$db -> conn -> error;
				}
			} else {
				$sql = "UPDATE tweets SET userId = :userId, text = :text, creationDate = :creationDate WHERE id = :id";
				$stmt = self::$db -> conn -> prepare($sql);
				$result = $stmt -> execute(['userId' => $this -> userId, 'text' => $this -> text, 'creationDate' => $this -> creationDate, 'id' => $this -> id]);

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
		$sql = "SELECT * FROM tweets WHERE id=:id";
		$stmt = self::$db -> conn -> prepare($sql);
		$result = $stmt -> execute(['id' => $id]);
		if ($result && $stmt -> rowCount() >= 1) {
			$row = $stmt -> fetch(PDO::FETCH_ASSOC);
			$loadedTweet = new tweet();
			$loadedTweet -> id = $row['id'];
			$loadedTweet -> userId = $row['userId'];
			$loadedTweet -> text = $row['text'];
			$loadedTweet -> creationDate = $row['creationDate'];
			return $loadedTweet;
		}
		return null;
	}

	static public function loadAll() {
		self::connect();
		$sql = "SELECT * FROM tweets ORDER BY creationDate DESC";
		$result = self::$db -> conn -> query($sql);
		$returnTable = [];
		if ($result !== false && $result -> rowCount() > 0) {
			foreach ($result as $row) {
				$loadedUser = new tweet();
				$loadedUser -> id = $row['id'];
				$loadedUser -> userId = $row['userId'];
				$loadedUser -> text = $row['text'];
				$loadedUser -> creationDate = $row['creationDate'];
				$returnTable[] = $loadedUser;
			}
		}
		return $returnTable;
	}

	public static function loadAllByUserId($id) {
		self::connect();
		$sql = "SELECT * FROM tweets WHERE userId=:id ORDER BY creationDate DESC";
		$stmt = self::$db -> conn -> prepare($sql);
		$result = $stmt -> execute(['id' => $id]);
		$returnTable = [];
		if ($result !== false && $stmt -> rowCount() > 0) {
			foreach ($stmt as $row) {
				$loadedTweet = new tweet();
				$loadedTweet -> id = $row['id'];
				$loadedTweet -> userId = $row['userId'];
				$loadedTweet -> text = $row['text'];
				$loadedTweet -> creationDate = $row['creationDate'];
				$returnTable[] = $loadedTweet;
			}
		}
		return $returnTable;
	}

	public function delete() {
		self::connect();
		if ($this -> id != -1) {
			$sql = "DELETE FROM tweets WHERE id=:id";
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

}
