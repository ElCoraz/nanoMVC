<?php
//********************************************************************************** */
class DataBase
{
    //****************************************************************************** */
    public $conn;
    //****************************************************************************** */
    public $order = null;
    public $limit = null;
    public $offset = null;
    //****************************************************************************** */
    public $where = [];
    public $fields = [];
    //****************************************************************************** */
    public $tableName = '';
    //****************************************************************************** */
    function __construct()
    {
        $config = $GLOBALS['config'];

        $this->conn = new mysqli($config['servername'], $config['username'], $config['password']);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->select_db($config['database']);
    }
    //****************************************************************************** */
    function __destruct()
    {
        $this->conn->close();
    }
    //****************************************************************************** */
    function table($tableName)
    {
        $this->tableName = $tableName;
    }
    //****************************************************************************** */
    function select($field)
    {
        array_push($this->fields, $field);
    }
    //****************************************************************************** */
    function insert($field)
    {
        array_push($this->fields, $field);
    }
    //****************************************************************************** */
    function update($field)
    {
        array_push($this->fields, $field);
    }
    //****************************************************************************** */
    function limit($limit)
    {
        $this->limit = $limit;
    }
    //****************************************************************************** */
    function offset($offset)
    {
        $this->offset = $offset;
    }
    //****************************************************************************** */
    function order($order, $direction = null)
    {
        $this->order = htmlspecialchars($order, ENT_QUOTES) . ' ' . ($direction !== null ? htmlspecialchars($direction, ENT_QUOTES) : '');
    }
    //****************************************************************************** */
    function where($where)
    {
        array_push($this->where, $where);
    }
    //****************************************************************************** */
    function setValues()
    {
        $selectFields = "*";

        if (count($this->fields) > 0) {
            $selectFields = '';
            foreach ($this->fields as $field) {
                $selectFields .= htmlspecialchars($field, ENT_QUOTES)  . ',';
            }
            $selectFields = substr($selectFields, 0, strlen($selectFields) - 1);
        }

        $whereFields = "";

        if (count($this->where) > 0) {
            $whereFields = 'WHERE ';
            foreach ($this->where as $row) {
                foreach ($row as $key => $value) {
                    $whereFields .=  '`' . $this->tableName . '`.`'  . htmlspecialchars($key, ENT_QUOTES) . '`=\'' . htmlspecialchars($value, ENT_QUOTES) . '\',';
                }
            }
            $whereFields = substr($whereFields, 0, strlen($whereFields) - 1);
        }

        $order = '';
        $limit = '';
        $offset = '';

        if ($this->order !== null) {
            $order .= ' ORDER BY ' . $this->order;
        }

        if ($this->limit !== null) {
            $limit .= ' LIMIT ' . (int)htmlspecialchars($this->limit, ENT_QUOTES);
        }

        if ($this->offset !== null) {
            $offset .= ' OFFSET ' . (int)htmlspecialchars($this->offset, ENT_QUOTES);
        }

        return [
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset,
            'whereFields' => $whereFields,
            'selectFields' => $selectFields,
        ];
    }
    //****************************************************************************** */
    function executeInsert()
    {
        if (count($this->fields) > 0) {
            $fields = '';
            $values = '';
            
            foreach ($this->fields[0] as $key => $value) {
                $fields .= '`' . htmlspecialchars($key, ENT_QUOTES) . '`,';
                if (!is_numeric($value)) {
                    $values .= '\''. htmlspecialchars($value, ENT_QUOTES) . '\',';
                } else {
                    $values .= htmlspecialchars($value, ENT_QUOTES) . ',';
                }
            }

            $fields = substr($fields, 0, strlen($fields) - 1);
            $values = substr($values, 0, strlen($values) - 1);

            try {
                $result = $this->conn->query('INSERT INTO ' . '`' . $this->tableName . '`(' . $fields . ') VALUES (' . $values . ')');
                
                if ($result) {
                    return ['status' => 'success'];
                } else {
                    return ['status' => 'failed', 'message' => "Ошибка добавления новой задачи"];
                }
            } catch (Exception $e) {
                die("Wrong query: " . $e->getMessage());
            }
        }

        return ['status' => 'failed', 'message' => "Ошибка добавления новой задачи"];
    }
    //****************************************************************************** */
    function executeUpdate()
    {
        if (count($this->fields) > 0) {
            
            $values = '';
            
            foreach ($this->fields[0] as $key => $value) {
                if (is_numeric($value)) {
                    $values .= '`' . htmlspecialchars($key, ENT_QUOTES)  . '` = '. htmlspecialchars($value, ENT_QUOTES)  . ',';
                } else {
                    $values .= '`' . htmlspecialchars($key, ENT_QUOTES)  . '` = \''. htmlspecialchars($value, ENT_QUOTES)  . '\',';
                }
            }
         
            $whereFields = "";

            if (count($this->where) > 0) {
                $whereFields = 'WHERE ';
                foreach ($this->where as $row) {
                    foreach ($row as $key => $value) {
                        $whereFields .=  '`' . $this->tableName . '`.`'  . htmlspecialchars($key, ENT_QUOTES) . '`=\'' . htmlspecialchars($value, ENT_QUOTES) . '\',';
                    }
                }
                $whereFields = substr($whereFields, 0, strlen($whereFields) - 1);
            }
        
            $values = substr($values, 0, strlen($values) - 1);

            try {

                $result = $this->conn->query('UPDATE ' . '`' . $this->tableName . '` SET ' . $values . ' ' .  $whereFields);
                
                if ($result) {
                    return ['status' => 'success'];
                } else {
                    return ['status' => 'failed', 'message' => "Ошибка добавления новой задачи"];
                }
            } catch (Exception $e) {
                die("Wrong query: " . $e->getMessage());
            }
        }

        return ['status' => 'failed', 'message' => "Ошибка добавления новой задачи"];
    }
    //****************************************************************************** */
    function one()
    {
        $queryValues = $this->setValues();

        try {
            $result = $this->conn->query('SELECT ' . $queryValues['selectFields'] . ' FROM `' . $this->tableName . '` ' . $queryValues['whereFields'] . $queryValues['order'] . $queryValues['limit'] . $queryValues['offset']);

            if ($result->num_rows > 0) {
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    array_push($rows, $row);
                }
                return $rows[0];
            }
        } catch (Exception $e) {
            die("Wrong query: " . $e->getMessage());
        }

        return [];
    }
    //****************************************************************************** */
    function all()
    {
        $queryValues = $this->setValues();

        try {
            $result = $this->conn->query('SELECT ' . $queryValues['selectFields'] . ' FROM `' . $this->tableName . '` ' . $queryValues['whereFields'] . $queryValues['order'] . $queryValues['limit'] . $queryValues['offset']);

            if ($result->num_rows > 0) {
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    array_push($rows, $row);
                }
                return $rows;
            }
        } catch (Exception $e) {
            die("Wrong query: " . $e->getMessage());
        }

        return [];
    }
    //****************************************************************************** */
}
//********************************************************************************** */