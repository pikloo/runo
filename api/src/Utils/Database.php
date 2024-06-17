<?php
namespace Runo\Utils;
use PDO;

class Database {
    private static $_instance;
    private $dbh;
    private function __construct() {
        getenv('DB_HOST');
        try {
            $this->dbh = new PDO(
                'mysql:host='. getenv('DB_HOST') .';dbname='. getenv('DB_NAME') .';charset=utf8',
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
            );
        }
        catch(\Exception $exception) {
            echo 'Erreur de connexion...<br>';
            echo $exception->getMessage().'<br>';
            echo '<pre>';
            echo $exception->getTraceAsString();
            echo '</pre>';
            exit;
        }
    }
    public static function getPDO() {
        // If no instance => create one
        if (empty(self::$_instance)) self::$_instance = new Database();
        return self::$_instance->dbh;
        
    }
}