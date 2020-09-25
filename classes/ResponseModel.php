<?php

trait ResponseModel
{
    public $responseStatus;
    public $responseMsg;
    public $failureMsg;
    public $failureStatus;

    public function responseOk($responseMsg,$responseStatus = 200,$api_message="")
    {  
        $data = ['ret' => ['data' => $responseMsg, 'status' => 1,'msg' => $api_message ], 'err' => ['data' => '', 'status' => 0]];


        header('Access-Control-Allow-Origin: *');
        header("$_SERVER[SERVER_PROTOCOL] $responseStatus OK");
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    public function responseErr($mdata = "", $responseStatus,$api_message="")
    {
        $data = ['ret' => ['data' => "", 'msg' => "", 'status' => 0], 'err' => ['data' => $mdata, 'status' => 1,'msg' => $api_message]];

        header('Access-Control-Allow-Origin: *');
        header("$_SERVER[SERVER_PROTOCOL] $responseStatus Exception");
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
    public function responseMis($responseMsg = "", $responseStatus = "401")
    {
        $data = ['ret' => ['data' => "", 'msg' => "", 'status' => 0], 'err' => ['data' => $responseMsg, 'status' => 1]];

        header('Access-Control-Allow-Origin: *');
        header("$_SERVER[SERVER_PROTOCOL] $responseStatus Error");
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
}
