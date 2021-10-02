<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/module/main.php");
    header("Content-type: application/json; charset=utf-8"); 
    if(isset($_GET["method"])){
        $Main=new Main();
        $session=false;
        $token=false;
        if(isset($_GET['token'])){
            $token=$_GET['token'];
        }
        elseif(isset($_POST['token'])){
            $token=$_POST['token'];
        }
        elseif(isset($_COOKIE['token'])){
            $token=$_COOKIE['token'];
        }
        switch($_GET["method"]){
            case "auth":
                if(isset($_GET['email']) AND isset($_GET['password'])){
                    $email=$_GET['email'];
                    $password=$_GET['password'];
                }
                elseif(isset($_POST['email']) AND isset($_POST['password'])){
                    $email=$_POST['email'];
                    $password=$_POST['password'];
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Incorrect query. Please make sure that tou fill password and email fields")));
                    exit;
                }
                $ans=$Main->auth($email,$password);
                if($ans){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            default:
            http_response_code(400);
            echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Incorrect method")));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Method not set")));
    }