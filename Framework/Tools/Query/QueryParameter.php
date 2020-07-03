<?php

namespace Framework\Tools\Query;

class QueryParameter
{
	private $name;
	private $value;

	public function __construct($name, $value)
	{
		$this->SetName($name);
		$this->SetValue($value);
	}

	public function GetName()
	{
		return $this->name;
	}

	public function SetName($value)
	{
		$this->name = $value;

		return $this;
	}

	public function GetValue()
	{
		return $this->value;
	}

	public function SetValue($value)
	{
		$this->value = $value;

		return $this;
	}
}