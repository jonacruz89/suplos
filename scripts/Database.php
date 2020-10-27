<?php

class Database extends PDO{
	
	public function __construct(
		$dbType, $dbHost, $dbName, $dbUser, $dbPass
	) {

		$dsn = "$dbType:host=$dbHost;port=3306;dbname=$dbName";
		parent::__construct($dsn, $dbUser, $dbPass);
	}
	
	public function select($sql, $array = [], $fetchMode = PDO::FETCH_ASSOC) {
		$sth = $this->prepare($sql);
		foreach ($array as $key => $value) {
			$sth->bindValue((string)$key, $value);
		}
		
		if(!$sth->execute()) {
			$this->handleError();
		} else{
			return $sth->fetchAll($fetchMode);
		}
	}
	
	public function insert($table, $data) {
		ksort($data);
		
		$fieldNames = implode('`, `', array_keys($data));
		$fieldValues = ':' . implode(', :', array_keys($data));
		
		$sth = $this->prepare(
			"INSERT INTO $table (`$fieldNames`)
			VALUES ($fieldValues)"
		);
		
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		
		if(!$sth->execute()) {
			$this->handleError();
			//print_r($sth->errorInfo());
		}
	}
	
	public function update($table, $data, $where) {
		ksort($data);
		
		$fieldDetails = NULL;
		foreach($data as $key=> $value) {
			$fieldDetails .= "`$key`=:$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');
		
		$sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
		
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		
		$sth->execute();
	}
	
	public function delete($table, $where, $limit = 1) {
		return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
	}
	
	/* count rows*/
	public function rowsCount($table){
			$sth = $this->prepare("SELECT * FROM $table");
			$sth->execute();
			return $sth->rowCount();
	}
	
	/* error check */
	private function handleError() {
		if ($this->errorCode() !== '00000') {
			if ((bool)$this->_errorLog === true) {
				echo json_encode($this->errorInfo());
			}
			throw new Exception("Error: " . implode(',', $this->errorInfo()));
		}
	}
	
}
?>