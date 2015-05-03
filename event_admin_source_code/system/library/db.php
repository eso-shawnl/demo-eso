<?php
class DB {
	private $db;

	public function __construct($driver, $hostname, $username, $password, $database) {
		$class = 'DB\\' . $driver;

		if (class_exists($class)) {
			$this->db = new $class($hostname, $username, $password, $database);
		} else {
			exit('Error: Could not load database driver ' . $driver . '!');
		}
	}

	public function query($sql) {
		writeGetUrlInfo('(EXECUTE SQL>>>>):'.$sql);
		return $this->db->query($sql);
	}

	public function escape($value) {
		return $this->db->escape($value);
	}

	public function countAffected() {
		return $this->db->countAffected();
	}

	public function getLastId() {
		return $this->db->getLastId();
	}

    public function multi_query($sql) {
        writeGetUrlInfo('(EXECUTE SQL>>>>):'.$sql);
        return $this->db->multi_query($sql);
    }
}
