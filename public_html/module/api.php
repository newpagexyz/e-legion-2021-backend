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
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "get_user_reviews":
                if(isset($_GET['uid'])){
                    $uid=$_GET['uid'];
                }
                else if(isset($_POST['uid'])){
                    $uid=$_POST['uid'];
                }
                else{
                    if($token){
                        if($uid=$Main->check_token($token)){
                            
                        }
                        else{
                            http_response_code(400);
                            echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                            exit;
                        }
                    }
                    else{
                        http_response_code(400);
                        echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill id or your token for your info")));
                        exit;
                    }
                }
                $ans=$Main->get_user_reviews($uid);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "user_info":
                if(isset($_GET['uid'])){
                    $uid=$_GET['uid'];
                }
                else if(isset($_POST['uid'])){
                    $uid=$_POST['uid'];
                }
                else{
                    if($token){
                        if($uid=$Main->check_token($token)){
                            
                        }
                        else{
                            http_response_code(400);
                            echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                            exit;
                        }
                    }
                    else{
                        http_response_code(400);
                        echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill id or your token for your info")));
                        exit;
                    }
                }
                $ans=$Main->get_user_info($uid);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "get_calendar":
                if($token){
                    if($uid=$Main->check_token($token)){
                        $ans=$Main->get_calendar();
                        if($ans!==false){
                            echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                        }
                        else{
                            echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                        }
                    }
                    else{
                        http_response_code(400);
                        echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                        exit;
                    }
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill id or your token for your info")));
                    exit;
                }
            break;
            case "get_member_teams":
                if(isset($_GET['uid'])){
                    $uid=$_GET['uid'];
                }
                else if(isset($_POST['uid'])){
                    $uid=$_POST['uid'];
                }
                else{
                    if($token){
                        if($uid=$Main->check_token($token)){
                            
                        }
                        else{
                            http_response_code(400);
                            echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                            exit;
                        }
                    }
                    else{
                        http_response_code(400);
                        echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill id or your token for your info")));
                        exit;
                    }
                }
                $ans=$Main->get_member_projects($uid);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "get_user_rating":
                if(isset($_GET['uid'])){
                    $uid=$_GET['uid'];
                }
                else if(isset($_POST['uid'])){
                    $uid=$_POST['uid'];
                }
                else{
                    if($token){
                        if($uid=$Main->check_token($token)){
                            
                        }
                        else{
                            http_response_code(400);
                            echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                            exit;
                        }
                    }
                    else{
                        http_response_code(400);
                        echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill id or your token for your info")));
                        exit;
                    }
                }
                $ans=$Main->get_user_rating($uid);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "change_avatar":
                if($token){
                    if($Main->check_token($token)){
                        $ans=$Main->change_avatar();
                        if($ans!==false){
                            echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                        }
                        else{
                            echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                        }
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));    
                    }
                }
            break;
            case "edit_user_info":
                if(isset($_GET['key']) AND isset($_GET['val'])){
                    $key=$_GET['key'];
                    $val=$_GET['val'];
                }
                else if(isset($_POST['key']) AND isset($_POST['val'])){
                    $key=$_POST['key'];
                    $val=$_POST['val'];
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill key and value")));
                    exit;
                }
                if($token){
                    if($Main->check_token($token)){
                        $ans=$Main->edit_user_info($key,$val);
                        if($ans!==false){
                            echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                        }
                        else{
                            echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                        }
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));    
                    }
                }
            break;
            case "add_review":
                if(isset($_GET['rid']) AND isset($_GET['rate']) AND isset($_GET['title']) AND isset($_GET['body'])){
                    $rid=$_GET['rid'];
                    $rate=$_GET['rate'];
                    $title=$_GET['title'];
                    $body=$_GET['body'];
                }
                else if(isset($_POST['rid']) AND isset($_POST['rate']) AND isset($_POST['title']) AND isset($_POST['body'])){
                    $rid=$_POST['rid'];
                    $rate=$_POST['rate'];
                    $title=$_POST['title'];
                    $body=$_POST['body'];
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill all fields")));
                    exit;
                }
                if($token){
                    if($Main->check_token($token)){
                        $ans=$Main->add_review($rid,$rate,$title,$body);
                        if($ans!==false){
                            echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                        }
                        else{
                            echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                        }
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));    
                    }
                }
            break;
            case "add_event":
                if(isset($_GET['name']) AND isset($_GET['description']) AND isset($_GET['type']) AND isset($_GET['place'])  AND isset($_GET['start_time'])  AND isset($_GET['end_time'])){
                    $name=$_GET['name'];
                    $description=$_GET['description'];
                    $type=$_GET['type'];
                    $place=$_GET['place'];
                    $start_time=$_GET['start_time'];
                    $end_time=$_GET['end_time'];
                }
                else if(isset($_POST['name']) AND isset($_POST['description']) AND isset($_POST['type']) AND isset($_POST['place'])  AND isset($_POST['start_time'])  AND isset($_POST['end_time'])){
                    $name=$_POST['name'];
                    $description=$_POST['description'];
                    $type=$_POST['type'];
                    $place=$_POST['place'];
                    $start_time=$_POST['start_time'];
                    $end_time=$_POST['end_time'];
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill all fields")));
                    exit;
                }
                if($token){
                    if($Main->check_token($token)){
                        $ans=$Main->add_event($name,$description,$type,$place,$start_time,$end_time);
                        if($ans!==false){
                            echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                        }
                        else{
                            echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                        }
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));    
                    }
                }
            break;
            case "delete_event":
                if(isset($_GET['eid'])){
                    $eid=$_GET['eid'];
                }
                else if(isset($_POST['eid'])){
                    $eid=$_POST['eid'];
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill all fields")));
                    exit;
                }
                if($token){
                    if($Main->check_token($token)){
                        $ans=$Main->delete_event($eid);
                        if($ans!==false){
                            echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                        }
                        else{
                            echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                        }
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));    
                    }
                }
            break;
            case "add_event_member":
                if(isset($_GET['eid']) AND isset($_GET['uid'])){
                    $eid=$_GET['eid'];
                    $uid=$_GET['uid'];
                }
                else if(isset($_POST['eid']) AND isset($_POST['uid'])){
                    $eid=$_POST['eid'];
                    $uid=$_POST['uid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Set team id and member id")));
                    exit;
                }
                if($Main->check_token($token)){
                    $ans=$Main->add_event_member($eid,$uid);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                }
            break;
            case "delete_event_member":
                if(isset($_GET['eid']) AND isset($_GET['uid'])){
                    $eid=$_GET['eid'];
                    $uid=$_GET['uid'];
                }
                else if(isset($_POST['eid']) AND isset($_POST['uid'])){
                    $eid=$_POST['eid'];
                    $iid=$_POST['uid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Set team id and member id")));
                    exit;
                }
                if($Main->check_token($token)){
                    $ans=$Main->delete_event_member($eid,$uid);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                }
            break;
            case "get_users":
                $role=3;
                if(isset($_GET['role'])){
                    $role=$_GET['role'];
                }
                else if(isset($_POST['role'])){
                    $role=$_POST['role'];
                }
                $ans=$Main->get_users($role);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "team_vacations":
                if(isset($_GET['tid'])){
                    $tid=$_GET['tid'];
                }
                else if(isset($_POST['tid'])){
                    $tid=$_POST['tid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill team id")));    
                }
                $ans=$Main->team_vacations($tid);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "search_user":
                if(isset($_GET['query'])){
                    $query=$_GET['query'];
                }
                else if(isset($_POST['query'])){
                    $query=$_POST['query'];
                }
                else{
                    http_response_code(400);
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill all fields")));
                    exit;
                }
                $ans=$Main->search_user($query);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "get_users_rating":
                $ans=$Main->get_users_rating($role);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "get_team_info":
                if(isset($_GET['tid'])){
                    $pid=$_GET['tid'];
                }
                else if(isset($_POST['tid'])){
                    $pid=$_POST['tid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill team id")));    
                    exit;
                }
                if($Main->check_token($token)){
                    $ans=$Main->get_team_info($pid);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));  
                }
            break;
            case "get_team_members":
                if(isset($_GET['tid'])){
                    $pid=$_GET['tid'];
                }
                else if(isset($_POST['tid'])){
                    $pid=$_POST['tid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Fill team id")));    
                    exit;
                }
                $ans=$Main->get_project_members($pid);
                if($ans!==false){
                    echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                }
            break;
            case "create_team":
                if(isset($_GET['name'])){
                    $role=$_GET['name'];
                }
                else if(isset($_POST['name'])){
                    $role=$_POST['name'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Set team name")));
                }
                if($Main->check_token($token)){
                    $ans=$Main->create_team($role);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                }
            break;
            case "delete_team_member":
                if(isset($_GET['tid']) AND isset($_GET['mid'])){
                    $tid=$_GET['tid'];
                    $mid=$_GET['mid'];
                }
                else if(isset($_POST['tid']) AND isset($_POST['mid'])){
                    $tid=$_POST['tid'];
                    $mid=$_POST['mid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Set team id and member id")));
                    exit;
                }
                if($Main->check_token($token)){
                    $ans=$Main->delete_team_member($tid,$mid);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                }
            break;
            case "delete_team":
                if(isset($_GET['tid'])){
                    $tid=$_GET['tid'];
                }
                else if(isset($_POST['tid'])){
                    $tid=$_POST['tid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Set team id and member id")));
                    exit;
                }
                if($Main->check_token($token)){
                    $ans=$Main->delete_team($tid);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
                }
            break;
            case "add_team_member":
                if(isset($_GET['tid']) AND isset($_GET['mid'])){
                    $tid=$_GET['tid'];
                    $mid=$_GET['mid'];
                }
                else if(isset($_POST['tid']) AND isset($_POST['mid'])){
                    $tid=$_POST['tid'];
                    $mid=$_POST['mid'];
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>400,"description"=>"Set team id and member id")));
                    exit;
                }
                if($Main->check_token($token)){
                    $ans=$Main->add_team_member($tid,$mid);
                    if($ans!==false){
                        echo json_encode(array("error"=>array('status'=>false),"body"=>$ans));
                    }
                    else{
                        echo json_encode(array("error"=>array('status'=>true,"code"=>$Main->status,"description"=>$Main->status_text),"body"=>$ans));
                    }
                }
                else{
                    echo json_encode(array("error"=>array('status'=>true,"code"=>403,"description"=>"Invalid token")));
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