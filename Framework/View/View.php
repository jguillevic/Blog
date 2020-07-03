<?php

namespace Framework\View;

class View
{
	private $layout;
	private $content;

	public function __construct($contentFileName)
	{
		$this->layout = join(DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', 'Template', 'Layout.phtml'));
		$this->content = join(DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', 'Template', $contentFileName . '.phtml'));
	}

	public function Render($params = null)
	{
		ob_start();
		
    	include($this->layout);

    	ob_end_flush();
	}
}