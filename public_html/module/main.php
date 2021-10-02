<?php
class Main{
    protected $mysqli=false;
    public $id;
    public $status;
    public $status_text;
    function __construct($uid=false){
		/*
		Конструктор, принимает на вход необязательный параметр id пользователя
		Если есть id, присваивается параметр id
		Создаёт соединение с базой данных
		*/
			$this->mysqli=$this->my_connect();
            if($uid){
				$this->id=intval($uid);
			}
  		}
  		function __destruct(){
  			/*
  			Деструктор, закрывает соединение с бд
  			*/
       	$this->mysqli->close();
        }
    function get_user_info($id){
        /*
            Выдаст информацию о пользователе
        */
        $ret=$this->mysqli->query("call get_user_info(".intval($id).");");
        
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                $this->clear_mysqli();
                return $re;
            }
        }
        return false;
    }
    function get_team_info($id){
        /*
            Выдаст информацию о команде
        */
        $ret=$this->mysqli->query("call get_team_info(".$this->id.",".intval($id).");");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                $this->clear_mysqli();
                return $re;
            }
        }
        return false;
    }
    function auth($email,$password){
        /*
            Вернёт токен и пароль в случае успеха
        */
        $ret=$this->mysqli->query("call check_auth('".$this->mysqli->real_escape_string($email)."','".$this->mysqli->real_escape_string($password)."');");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                $this->clear_mysqli();
                if($re['id']){
                    $this->id=$re['id'];
                    $ret=$this->mysqli->query("select auth_user(".$this->id.") as ans;");
                    if($re=$ret->fetch_assoc()){
                        $ret->free();
                        if($re['ans']){
                            $ret=$this->mysqli->query("call get_auth_token(".$re['ans'].")");
                            if($re=$ret->fetch_assoc()){
                                $ret->free();
                                $this->clear_mysqli();
                                return $re;
                            }
                        }
                    }
                }
            }
        }
        else{
            $this->status=401;
            $this->status_text="Incorrect login or password";
        }
        return false;
    }
    function check_token($token){
        $ret=$this->mysqli->query("call get_id_by_token('".$token."');");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                $this->clear_mysqli();
                $this->id=$re['id'];
                return $this->id;

            }
        }
        return false;
    }
    function edit_user_info($key,$val){
        /*
            Изменяет профиль пользователя
        */
        if($key!="CV"){
            $ret=$this->mysqli->query("SELECT edit_user_info(".$this->id.",'".$key."','".$val."')  as ans;");
            if($ret->num_rows){
                if($re=$ret->fetch_assoc()){
                    $ret->free();
                    $this->clear_mysqli();
                    return $re['ans'];
                }
            }
        }
        return false;
    }
    function get_users($type_select=3){
        /*
            Вывести список пользователя
        */
        $ret=$this->mysqli->query("call get_users(".intval($type_select).");");
        if($ret->num_rows){
            $ans=array();
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
            return $ans;
        }
        return false;
    }
    function get_project_members($pid){
        /*
            Вывести список пользователя
        */
        $ret=$this->mysqli->query("call get_project_members(".intval($pid).")");
        if($ret->num_rows){
            $ans=array();
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
            return $ans;
        }
        return false;
    }
    function create_team($name){
        /*
            Создать команду
        */
        $ret=$this->mysqli->query("SELECT create_team(".$this->id.",'".$name."')  as ans;");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                return $re['ans'];
            }
        }
        return false;
    }
    function add_team_member($tid,$mid){
        /*
            Добавить пользователя в команду
        */
        $ret=$this->mysqli->query("SELECT add_team_member(".$this->id.",'".intval($tid)."','".intval($mid)."')  as ans;");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                return $re['ans'];
            }
        }
        return false;
    }
    function delete_team_member($tid,$mid){
        /*
            Добавить пользователя в команду
        */
        $ret=$this->mysqli->query("SELECT delete_team_member(".$this->id.",'".intval($tid)."','".intval($mid)."')  as ans;");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                return $re['ans'];
            }
        }
        return false;
    }
    function add_review($rid,$rate,$title,$body){
        /*
            Добавить оценку пользователя
        */
        $ret=$this->mysqli->query("SELECT add_review(".$this->id.",'".intval($rid)."','".intval($rate)."','".$this->mysqli->real_escape_string($title)."','".$this->mysqli->real_escape_string($body)."')  as ans;");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                return $re['ans'];
            }
        }
        return false;
    }
    private function clear_mysqli(){
        while($this->mysqli->next_result()) $this->mysqli->store_result();
    }
    protected static function my_connect(){
        /*
            Функция создаёт объект mysqli,устанавливает кодировку и
            вовращает mysqli
        */
            include_once($_SERVER['DOCUMENT_ROOT']."/config/mysql_config.php");
            $mysqli= new mysqli(MYSQL_config['host'], MYSQL_config['user'], MYSQL_config['password'], MYSQL_config['db']);
            if ($mysqli -> connect_errno) {
            if(API_PARAM['debug_mode']){
                echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
            }
            http_response_code(500);
            exit();
        }
            $mysqli->set_charset("utf8_unicode_ci");
            return $mysqli;
    }
}