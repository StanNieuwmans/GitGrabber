<?php
/**
 * Created by PHPStorm v. 2017.1.4
 * Date: 7-11-2018
 * Time: 11:14
 * Filename: DBconf.php
 */


class DBconf {
	private $conn;
	private $stmt;


	public function OpenConnection()
	{
		$this->setConn(new PDO('mysql:host=localhost;dbname=Database', 'root', ''));
		$this->getConn()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function CloseConnection()
	{
		$this->setConn(null);
	}
	/** ---------------------Getters en setter--------------------*/
	/** ----------------------------------------------------------*/

	public function getConn()
	{
		return $this->conn;
	}

	private function setConn($conn)
	{
		$this->conn = $conn;
	}


	public function getStmt()
	{
		return $this->stmt;
	}


	public function setStmt($stmt)
	{
		$this->stmt = $stmt;
	}
}
