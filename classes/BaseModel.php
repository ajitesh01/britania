<?php


class BaseModel {
    use ResponseModel;
    public $dbw;
    public $dbr;
    public $count;

    public function __construct() {
        $this->readConn();
        $this->writeConn();
    }

    public function readConn(){
        $this->dbr = new db(DBRHOST, DBRUSER, DBRPASS, DBRDB);
    }

    public function writeConn(){
        $this->dbw = new db(DBWHOST, DBWUSER, DBWPASS, DBWDB);
    }


    public function post() {
        return json_decode(file_get_contents("php://input"))?json_decode(file_get_contents("php://input")):null;
    }

    public function loggerRequest($severity="info", $req =""){
        
    }
    
}