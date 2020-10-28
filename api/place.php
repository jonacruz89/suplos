<?php

class Place
{
    static private $db;

    public function __construct()
    {
        self::$db = new Connection();
    }

    static function find($id)
    {
        return self::$db->select('places', $id);
    }

    static function all()
    {
        $results = self::$db->select('places');

        if (empty($results)) {
            self::migrate();
            $results = self::$db->select('places');
        }

        return $results;
    }

    static function migrate()
    {
        self::$db->conn->beginTransaction();
        $sql = "
            DROP TABLE IF EXISTS `places`;
        ";
        self::$db->conn->exec($sql);
        $sql = "
            CREATE TABLE `places` (
            `id` int(11) NOT NULL,
            `address` varchar(250) DEFAULT '',
            `city` varchar(100) DEFAULT '',
            `phone` varchar(20) DEFAULT '',
            `code` varchar(20) DEFAULT '',
            `type` varchar(50) DEFAULT '',
            `price` varchar(50) DEFAULT '',
            `is_favorite` boolean DEFAULT false,
            `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
            `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

            ALTER TABLE `places`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `places`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ";
        self::$db->conn->exec($sql);
        $sql = "
            ALTER TABLE `places`
            ADD PRIMARY KEY (`id`);
        ";
        self::$db->conn->exec($sql);
        $sql = "
            ALTER TABLE `places`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ";
        self::$db->conn->exec($sql);

        $string = file_get_contents(__DIR__ . "/data.json");

        $arrPlaces = json_decode($string);

        foreach ($arrPlaces as $value) {
            $sql = "
                INSERT INTO `places` (address, city, phone, code, type, price) 
                VALUES ('$value->Direccion', '$value->Ciudad', '$value->Telefono', '$value->Codigo_Postal', '$value->Tipo', '$value->Precio') ;
            ";
            self::$db->conn->exec($sql);
        }

        self::$db->conn->rollBack();
    }
}
