<?php

class Database {

    protected static $connection;

    public function connect() {
        if (!isset(self::$connection)) {
            $config = parse_ini_file('config/config.ini');
            self::$connection = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);
        }
        if (self::$connection === false) {
            return false;
        }
        return self::$connection;
    }

    public function query($query) {
        $connection = $this->connect();
        $result = $connection->query($query);
        return $result;
    }

    public function select($query) {
        $rows = array();
        $result = $this->query($query);
        if ($result->num_rows == 0) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function create($filename) {
        $query = "create table $filename (Id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, Date Date unique, Open DOUBLE(10,6),"
                . "High DOUBLE(10,6), Low DOUBLE(10,6), Close DOUBLE(10,6), Volume INT(11), Adj_Close DOUBLE(10,6))";
        $result = $this->query($query);
        return $result;
    }

    public function upload($data, $table) {
        $startrow = 0;
        $query = "insert into $table (Date, Open, High, Low, Close, Volume, Adj_Close) values";
        $arrayval = array();
        foreach ($data as $row) {
            if ($startrow == 0) {
                $startrow = 1;
                continue;
            }
            $date = date('Y-m-d', strtotime($row[0]));
            $open = $row[1];
            $high = $row[2];
            $low = $row[3];
            $close = $row[4];
            $volume = $row[5];
            $adj_close = $row[6];
            $arrayval[] = "('$date', '$open', '$high', '$low', '$close', '$volume', '$adj_close')";
        }
        $query.= implode(',', $arrayval);
        $result = $this->query($query);
        return $result;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this->connect();
        return $connection->error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this->connect();
        return "'" . $connection->real_escape_string($value) . "'";
    }
    public function update($comp_name, $obj2){
      
    }
}