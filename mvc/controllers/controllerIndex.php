<?php
//********************************************************************************** */
require_once './mvc/models/modelIndex.php';
//********************************************************************************** */
class ControllerIndex extends Controller
{
	//****************************************************************************** */
	function __construct()
	{
		$this->setParams();
		$this->setFields();

		$this->view = new View();
		$this->model = new ModelIndex();
	}
	//****************************************************************************** */
	function actionIndex()
	{
		$className = str_replace("Controller", "", (new \ReflectionClass($this))->getName());
		$this->view->generate('view' . $className . '.php', $this->params, $this->model->getData($this->params));
	}
	//****************************************************************************** */
	function actionGetbyid()
	{
		header('Content-Type: application/json');

		echo json_encode($this->model->GetByID($this->params));
	}
	//****************************************************************************** */
	function actionNewtask()
	{
		header('Content-Type: application/json');

		echo json_encode($this->model->newTask($this->fields));
	}
	//****************************************************************************** */
}
//********************************************************************************** */