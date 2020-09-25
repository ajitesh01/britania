<?php
function setResponse($mdata) {
    $data = ['ret'=>['data'=>"", 'msg' => "", 'status' => 0],'err'=>['status'=>0,'msg'=>$mdata]];
    header($_SERVER["SERVER_PROTOCOL"]." 500 Exception"); 
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}

function underscoreToCamelCase($string, $capitalizeFirstCharacter = false) {

    $str = str_replace('_', '', ucwords($string, '_'));

    if (!$capitalizeFirstCharacter) {
        $str = lcfirst($str);
    }

    return $str;
}

function printD($data) {
    if(DEBUG) {
        
        if(DEBUGFILE){
            $bt =  debug_backtrace();
            $date = date("Y-m-d");
            $logFileName = "logs/log.$date.txt";
            // file_put_contents($logFileName, print_r($bt,true), FILE_APPEND);
            file_put_contents($logFileName, date(" H:i:s :: "), FILE_APPEND);
            file_put_contents($logFileName, print_r($data,true), FILE_APPEND);
            file_put_contents($logFileName,"\n", FILE_APPEND);
            
        }else{
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
    }
}

function corsOption(){
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    }
}

function sanitize($data)
{
	// $data= mysql_real_escape_string($data);
	$data= stripslashes($data);
	return $data;
	//or in one line code 
	//return(stripslashes(mysql_real_escape_string($data)));
}

function IsNullOrEmptyString($str){
    return (!isset($str) || trim($str) === '');
}