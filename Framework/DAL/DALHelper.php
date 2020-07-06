<?php

namespace Framework\DAL;

class DALHelper
{
    	/**
	 * Set query condition in WHERE from array $data
	 * @param array $data
	 * @param string $tableAlias table alias for the condition
	 * @param string $columnName column name for the condition
	 * @param array $params completed parameters for the query
	 * @return string query condition in WHERE
	 */
	public static function SetArrayParams($data, $tableAlias, $columnName, &$params) : string
	{
		$query = sprintf("%s.%s IN (", $tableAlias, $columnName);

		$i = 0;
		foreach ($data as $d) 
		{ 
			if ($i > 0)
				$query .= ", ";

			$query .= sprintf(":%s", $columnName).$i;

			$params[$columnName.$i] = $d;

			$i++;
		}

		$query .= ")";

		return $query;
	}
}