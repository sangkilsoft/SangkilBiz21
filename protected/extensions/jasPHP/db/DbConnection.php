<?php

class DbConnection {

    public $url;
    public $username;
    public $password;
    private $connection;
    private static $_instance = null;

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DbConnection();
        }

        return self::$_instance;
    }

    public function __construct() {
        $this->connection = Yii::app()->db;
    }

    public function listAll($sql, $parameters) {

        $sql = $this->changeParamToValue($sql, $parameters);

        $command = $this->connection->createCommand($sql);
        $command->execute();
        $result = $command->query();

        if ($result) {
            return $result->readAll();
        }
        return null;
    }

    private function changeParamToValue($sql, $parameters) {
        if (is_array($parameters)) {
            foreach ($parameters as $paramName => $paramValue) {
                $sql = $this->addParameterInSql($sql, $paramName, $paramValue);
            }
        } else {
            throw new JasperReaderException("Param need`s to be an array!");
        }

        return $sql;
    }

    private function addParameterInSql($sql, $paramName, $paramValue) {
        $sql = str_replace("\$P{" . $paramName . "}", $paramValue, $sql);

        return $sql;
    }

}