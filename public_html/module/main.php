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
    function auth($email,$password){
        /*
            Вернёт токен и пароль в случае успеха
        */
        $ret=$this->mysqli->query("select check_auth('".$this->mysqli->real_escape_string($email)."','".$this->mysqli->real_escape_string($password)."') as ans;");
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                if($re['ans']){
                    $ret=$this->mysqli->query("select auth_user(".$re['ans'].") as ans;");
                    if($re=$ret->fetch_assoc()){
                        $ret->free();
                        if($re['ans']){
                            $ret=$this->mysqli->query("call get_auth_token(".$re['ans'].")");
                            if($re=$ret->fetch_assoc()){
                                $ret->free();
                                $this->clear_mysqli();
                                $this->id=$re['id'];
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
    function edit_user_info($uid){
        /*
            Изменяет профиль пользователя
        */
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