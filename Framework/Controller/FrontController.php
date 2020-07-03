<?php

namespace Framework\Controller;

use \Framework\Tools\Helper\RoutesHelper;
use \Framework\Tools\Query\QueryParameterHelper;

class FrontController
{
	public function Run()
	{
        $path = strtolower(trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/'));
        
        $route = RoutesHelper::GetRoutesConfig()->GetRoute($path);

        if ($route !== null)
        {
            $controller = "\Controller\\" . $route->GetController();
            $action = $route->GetAction();

            $params = array();

            if ($_SERVER['REQUEST_METHOD'] === 'GET')
            {
                $query = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);

                if (!empty($query))
                {
                    $params = QueryParameterHelper::GetQueryParametersFromQuery($query);
                }
            }
            else if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                if (isset($_POST))
                {
                    $params = QueryParameterHelper::GetQueryParametersFromPost($_POST);
                }
            }

            return call_user_func_array(array(new $controller, $action), array($params));
        }
        else
        {
            return 'Not found';
        }
	}
}