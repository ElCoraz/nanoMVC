<?php
//********************************************************************************** */
require_once './mvc/components/authorization.php';
//********************************************************************************** */
require_once 'view.php';
require_once 'model.php';
require_once 'asserts.php';
require_once 'controller.php';
//********************************************************************************** */
class Route
{
	//****************************************************************************** */
	static function launch()
	{
		Authorization::start();

		$assert = Asserts::setAsserts();

		if ($assert != null) {
			echo $assert;
			return; 
		}

		$actionName = 'index';
		$controllerName = 'index';

		$routes = explode('/', $_SERVER['REQUEST_URI']);

		if (!empty($routes[1])) {
			$pos = strpos($routes[1], '?');
			if ($pos !== false) {
				$tempControllerName =  substr($routes[1], 0, $pos);
				if (strlen($tempControllerName)> 0) {
					$controllerName = $tempControllerName;
				}
			} else {
				$controllerName = $routes[1];
			}
		}
		
		if (!empty($routes[2])) {
			$pos = strpos($routes[2], '?');
			if ($pos !== false) {
				$tempActionName =  substr($routes[2], 0, $pos);
				if (strlen($tempActionName)> 0) {
					$actionName = $tempActionName;
				}
			} else {
				$actionName = $routes[2];
			}
		}

		$modelName = 'Model' . $controllerName;
		$controllerName = 'Controller' . $controllerName;
		$actionName = 'action' . $actionName;

		$modelFile = strtolower($modelName) . '.php';
		$modelPath = "./mvc/models/" . $modelFile;

		if (file_exists($modelPath)) {
			include "./mvc/models/" . $modelFile;
		}

		$controllerFile = strtolower($controllerName) . '.php';
		$controllerPath = "./mvc/controllers/" . $controllerFile;

		if (file_exists($controllerPath)) {
			include $controllerPath;
		} else {
			throw new Exception("Don't find controller - " . $controllerPath);
		}

		$controller = new $controllerName;
		$action = $actionName;

		if (method_exists($controller, $action)) {
			$controller->$action();
		} else {
			throw new Exception("Don't find method - " . $actionName . ' in controller - ' . $controllerName);
		}
	}
	//****************************************************************************** */
}
//********************************************************************************** */