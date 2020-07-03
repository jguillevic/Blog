<?php

namespace Framework\Config;

class Route
{
	private $path;
	private $controller;
	private $action;
	private $name;

	public function __construct($path, $controller, $action, $name)
	{
		$this->SetPath($path);
		$this->SetController($controller);
		$this->SetAction($action);
		$this->SetName($name);
	}

	public function GetPath()
	{
		return $this->path;
	}

	public function SetPath($value)
	{
		$this->path = $value;

		return $this;
	}

	public function GetController()
	{
		return $this->controller;
	}

	public function SetController($value)
	{
		$this->controller = $value;

		return $this;
	}

	public function GetAction()
	{
		return $this->action;
	}

	public function SetAction($value)
	{
		$this->action = $value;

		return $this;
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
}