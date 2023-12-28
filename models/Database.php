<?php

class Database
{
    private $dbName = DB_NAME;
    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;

    protected $dbh;
    protected $stmt;
    protected $error;

    protected $allowedColumns;

    public function __construct() {
        $dsn = 'mysql:host='. $this->dbHost .';dbname='. $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::FETCH_ASSOC => true
        );

        try {
            $this->dbh = new PDO($dsn, $this->dbUser, $this->dbPass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /** Method to execute a query:
     * @param sql $sql
     * @return void
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /** Method to bind parameters:
     * @param $param
     * @param $value
     * @param $type
     * @return void
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /** Method to execute the prepared statement:
     * @return mixed
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /** Check For Column Existence And Validation:
     * @param $valueCol string
     * @return int
     * @throws Exception
     */
    public function checkParam(...$valueCol) {
        // Allow alphanumeric characters and underscores in column names
        foreach ($valueCol as $col){
            if (!in_array($col, $this->allowedColumns) || !preg_match('/^[a-zA-Z]{1,20}+$/', $col)) {
                throw new Exception("Your column should exist in the table columns and consist of alphanumeric characters");
            }
        }

        return 1;
    }

    /** Method to fetch a single row:
     * @return mixed
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Method to fetch all rows:
     * @return mixed
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Method to get the row count:
     * @return mixed
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
}