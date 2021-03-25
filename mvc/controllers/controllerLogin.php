<?php
//********************************************************************************** */
require_once 'controllerIndex.php';
//********************************************************************************** */
class ControllerLogin extends Controller
{
	//****************************************************************************** */
	function __construct()
	{
		$this->setParams();
		$this->setFields();

		$this->view = new View();
		$this->model = new ModelLogin();
	}
	//****************************************************************************** */
	function actionIndex()
	{
		$className = str_replace("Controller", "", (new \ReflectionClass($this))->getName());
		$this->view->generate('view' . $className . '.php', $this->params, $this->model->getData($this->params));
	}
	//****************************************************************************** */
	function actionLogin()
	{
		if (($this->fields['username'] == 'admin') && ($this->fields['password'] == '123')) {
			Authorization::setId(1);
			(new ControllerIndex())->actionIndex();
		} else {
			array_push($this->params, ['error' => "Проверьте правильность имени пользователя или пароля"]);
			$this->actionIndex();
		}
	}
	//****************************************************************************** */
	function actionLogout()
	{
		Authorization::close();

		(new ControllerIndex())->actionIndex();
	}
	//****************************************************************************** */
}
//********************************************************************************** */