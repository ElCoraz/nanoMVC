<?php
//********************************************************************************** */
class ModelIndex extends Model
{
	//****************************************************************************** */
	public $limit = 3;
	//****************************************************************************** */
	public function getData($params = null)
	{
		$data = new DataBase();

		$data->table('list');
		$data->limit($this->limit);
		$data->offset(isset($params['page']) ? ($this->limit * (int)$params['page']) : 0);

		if (isset($params['order'])) {
			$data->order($params['order'], $params['direction']);
		}

		$pagination = new DataBase();

		$pagination->table('list');

		$result =  [
			'values' => $data->all(),
			'pagination' => $pagination->all(),
		];

		if (isset($params['order'])) {
			$result['order'] = $params['order'];
		}

		if (isset($params['direction'])) {
			$result['direction'] = $params['direction'];
		}

		return $result;
	}
	//****************************************************************************** */
	public function getByID($params = null)
	{
		$data = new DataBase();

		$data->table('list');
		$data->where(['id' => $params['id']]);

		return $data->one();
	}
	//****************************************************************************** */
	public function newTask($params = null)
	{
		$data = new DataBase();

		if ($params['id'] == -1) {
			$data->table('list');
			$data->insert([
				'id' => uniqid(),
				'name' => $params['name'],
				'text' => $params['text'],
				'email' => $params['email'],
				'status' => $params['status'] == 'true' ? "Выполнено" : "В работе",
				'isAdmin' => $params['isAdmin']
			]);

			return $data->executeInsert();
		} else {
			if (Authorization::IsAuthorization()) {
				$data = new DataBase();

				$data->table('list');
				$data->where(['id' => $params['id']]);
				$data->update([
					'name' => $params['name'],
					'text' => $params['text'],
					'email' => $params['email'],
					'status' => $params['status'] == 'true' ? "Выполнено" : "В работе",
					'isAdmin' => $params['isAdmin']
				]);

				return $data->executeUpdate();
			} else {
				return ['status' => 'failed', 'message' => "Необходимо авторизироваться"];
			}
		}
	}
	//****************************************************************************** */
}
//********************************************************************************** */