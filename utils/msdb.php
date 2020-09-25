<?php

class db {

    protected $connection;
	protected $query;
    public $rawResultSet;
    public $resultArrayList;
	
	public function __construct($dbhost = DBWHOST, $dbuser = DBWUSER, $dbpass = DBWPASS, $dbname = DBWDB, $charset = 'utf8') {
        $connectionOptions = array(
            "Database" => $dbname, //"ccdb", // update me
            "Uid" => $dbuser, //"sambit", // update me
            "PWD" => $dbpass //"beta=X'X^-1X'y" // update me
        );
        
        $this->connection = sqlsrv_connect($dbhost, $connectionOptions);
		if ($this->connection == false) {
            throw new Exception('Failed to connect to MSSQL - ' );
            die( print_r( sqlsrv_errors(), true));
		}
		
    }
    
    public function __destruct() {
        
        sqlsrv_close($this->connection);  
    }
	
    public function query($query, $params = null) {
        if($params == null) {
            $this->rawResultSet = sqlsrv_query($this->connection, $query);  
        }else{
            $this->rawResultSet = sqlsrv_query($this->connection, $query, $params);  
        }
		
        if ($this->rawResultSet == FALSE && DEBUG === 1) {
            echo $query;
            die(print_r(sqlsrv_errors(),true));  
        } 
            
        $this->resultArrayList = [];
        while($row = sqlsrv_fetch_array($this->rawResultSet, SQLSRV_FETCH_ASSOC))  
        {  
            array_push($this->resultArrayList, $row);
        }  
        sqlsrv_free_stmt($this->rawResultSet);  
		return $this;
    }

	

}