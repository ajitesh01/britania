<?php
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
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


    public function sendSmtpMail($subject = "sample subject", $body = "sample <b>Body</b>", $toList){
        $this->mail = new PHPMailer(true);
        try{
            // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $this->mail->isSMTP();                                            // Send using SMTP
            $this->mail->Host       = MAIL_SMTP_HOST;                    // Set the SMTP server to send through
            $this->mail->SMTPAuth   = MAIL_SMTP_AUTH;                                   // Enable SMTP authentication
            $this->mail->Username   = MAIL_SMTP_USER;                     // SMTP username
            $this->mail->Password   = MAIL_SMTP_PASS;                               // SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $this->mail->Port       = MAIL_SMTP_PORT;   
            $this->mail->setFrom(MAIL_SMTP_FROM, MAIL_SMTP_FROM_USER);
            $this->mail->isHTML(true);  

            if(is_array($toList)){
                foreach($toList as $to){
                    $this->mail->addAddress($to);  
                }
            }
            
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags($body);
            $this->mail->send();
        }catch(Exception $e){
            $this->responseErr($e->getMessage(), 500); 
        }

        return true;
    }
    
}