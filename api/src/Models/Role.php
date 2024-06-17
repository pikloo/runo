<?php

namespace Runo\Models;

use PDO;
use Runo\Models\CoreModel;
use Runo\Utils\Database;

class Role extends CoreModel {
    private $title;

    public function insert(){
        $pdo = Database::getPDO();
        $sql = '
        INSERT INTO `'. $this->db_extension .'_role`(
        `title`
        ) values (:title)
        ';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindParam(':title',$this->title, PDO::PARAM_STR);
        $pdoStatement->execute();
        if ( $pdoStatement->rowCount() > 0){
            //Récupération de l'auto-incrément généré par Mysql
            $this->id = $pdo->lastInsertId();
            return true;
        }
        return false;
    }

    public function update(){
        $pdo = Database::getPDO();
        $sql = '
        UPDATE `'. $this->db_extension .'_role`
        SET `title` = :title
        WHERE id = :id
        ';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindParam(':title', $this->title, PDO::PARAM_STR);
        $pdoStatement->bindParam(':id', $this->id, PDO::PARAM_INT);
        $pdoStatement->execute();
        return ($pdoStatement->rowCount() > 0);
    }

    public function delete(){
        $pdo = Database::getPDO();
        $sql = '
        DELETE FROM `'. $this->db_extension .'_role`
        WHERE id = :id
        ';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindParam(':id', $this->id, PDO::PARAM_INT);
        $pdoStatement->execute();
        return ($pdoStatement->rowCount() > 0);
    }

    public static function findByTitle($value){
        $pdo = Database::getPDO();
        $sql = '
        SELECT * FROM `'. getenv('DB_PREFIX') .'_role`
        WHERE title = :value
        ';
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindParam(':value', $value, PDO::PARAM_STR);
        $pdoStatement->execute();
        return ($pdoStatement->rowCount() > 0)
            ? $pdoStatement->fetchObject(self::class)
             : null;
    }


    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
