<?php

namespace Framework\DAL;

class Database
{
	private $connect;
	private $transactionCount = 0;
	private $lastStmt = null;

	public function __construct()
	{
		$this->connect = self::GetPDO();

		self::SetCharacterSetUTF8($this->connect);
		self::SetErrorMode($this->connect);
	}

	public function __destruct()
	{
		if ($this->transactionCount > 0)
			self::Rollback();
	}

	private static function GetPDO()
	{
		return new \PDO(getenv("DBEngine").":host=".getenv("DBHost")."; dbname=".getenv("DBName")."", getenv("DBLogin"), getenv("DBPwd"));
	}

	public function GetLastInsertId()
	{
		return $this->connect->lastInsertId();
	}

	private static function SetCharacterSetUTF8($connect)
	{
		$connect->exec("SET CHARACTER SET utf8");
	}

	private static function SetErrorMode($connect)
	{
		$connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	private static function SetAutoCommit($connect)
	{
		$connect->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
	}

	public function Execute($query, $params = null)
	{
		$stmt = $this->connect->prepare($query);
		$this->lastStmt = $stmt;

		if (isset($params))
			$stmt->execute($params);
		else
			$stmt->execute();
		
		return $stmt;
	}

	public function GetRowCount()
	{
		return $this->lastStmt->rowCount();
	}

	public function Read($query, $params = null)
	{
		$stmt = $this->Execute($query, $params);

		$result = $stmt->fetchAll();

		return $result;
	}

	public function BeginTransaction()
	{
		if ($this->transactionCount == 0)
			$this->connect->beginTransaction();

		$this->transactionCount++;
	}

	public function Commit()
	{
		$this->transactionCount--;

		if ($this->transactionCount == 0)
			$this->connect->commit();
	}

	public function Rollback()
	{
		$this->connect->rollback();

		$this->transactionCount = 0;
	}
}