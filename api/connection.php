<?php

class Connection
{
    private $type = "mysql";
    private $host = "mysql";
    private $user = "root";
    private $pass = "1234";
    private $db = "intelcost";
    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("$this->type:host=$this->host;dbname=$this->db", $this->user, $this->pass, [
                PDO::ATTR_PERSISTENT => true
            ]);
        } catch (PDOException $e) {
            throw $e->getMessage();
        }
    }

    public function select($table, $id = '')
    {
        $sql = "SELECT * FROM $table ";

        if (!empty($id)) {
            $sql .= "WHERE id = $id";
        }

        $exec = $this->conn->prepare($sql);

        if ($exec->execute()) {
            return $exec->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }
}
