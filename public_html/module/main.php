<?php
class Main{
    protected $mysqli=false;
    public $id;
    public $status;
    public $status_text;
    private const IMAGE_FORMATS = array(
        "bmp","dib","rle","jpg","jfif","jpe","jpeg","jp2","j2k","jpf","jpm","jpg2","j2c","jpc","jpx","mj2","gif","tif","tiff","ico","jxr","hdp","wdp","png","webp","svg"
    );
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
    function get_user_rating($id){
        /*
            Выдаст рейтинг пользователя
        */
        $ret=$this->mysqli->query("call get_user_rating(".intval($id).");");
        
        if($ret->num_rows){
            if($re=$ret->fetch_assoc()){
                $ret->free();
                $this->clear_mysqli();
                return $re['rate'];
            }
        }
        return false;
    }
    function get_user_reviews($id){
        /*
            Выдаст отзывы о пользователе
        */
        $ret=$this->mysqli->query("call get_user_reviews(".intval($id).");");
        $ans=array();
        if($ret->num_rows){
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
        }
        return $ans;
    }
    function team_vacations($id){
        /*
            Выдаст отпуска сотрудников
        */
        $ret=$this->mysqli->query("call team_vacations(".intval($id).");");
        $ans=array();
        if($ret->num_rows){
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
        }
        return $ans;
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
        if($key!="CV" AND $key!="avatar"){
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
    function change_avatar(){
        /*
            Изменяет профиль пользователя
        */
        if(isset($_FILES['file'])){
            if(self::isPhoto($_FILES['file'])){
                $token=self::gen_token().".".(pathinfo($_FILES['file']['name']))['extension'];
                while(file_exists($_SERVER['DOCUMENT_ROOT'].'/user_files/profile_photo/'.$token)){
                    $token=self::gen_token().".".(pathinfo($file['name']))['extension'];
                }
                if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/user_files/profile_photo/'.$token)) {
                    $ret=$this->mysqli->query("SELECT edit_user_info(".$this->id.",'avatar','".$token."')  as ans;");
                    if($ret->num_rows){
                        if($re=$ret->fetch_assoc()){
                            $ret->free();
                            $this->clear_mysqli();
                            return $re['ans'];
                        }
                    }
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
    function get_users_rating($type_select=3){
        /*
            Вывести список пользователя
        */
        $ret=$this->mysqli->query("call sort_user_rating();");
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
        $ans=array();
        $ret=$this->mysqli->query("call get_project_members(".intval($pid).")");
        if($ret->num_rows){
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
        }
        return $ans;
    }
    function get_member_projects($uid){
        /*
            Вывести список пользователя
        */
        $ret=$this->mysqli->query("call get_member_projects(".intval($uid).");");
        $ans=array();
        if($ret->num_rows){
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
        }
        return $ans;
    }
    function search_user($query){
        /*
            Вывести список пользователя
        */
        $ret=$this->mysqli->query("call search_user('".$this->mysqli->real_escape_string($query)."');");
        $ans=array();
        if($ret->num_rows){
            while($re=$ret->fetch_assoc()){
                array_push($ans,$re);
            }
            $ret->free();
            $this->clear_mysqli();
        }
        return $ans;
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
            Удалить пользователя из команды
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
    function delete_team($tid){
        /*
            Добавить пользователя в команду
        */
        $ret=$this->mysqli->query("SELECT delete_team(".$this->id.",'".intval($tid)."')  as ans;");
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
    protected static function gen_token(){
        /*
            Функция для генерации строки в 64 символа, криптостойким алгоритмом
        */
      return hash("sha256", random_bytes(64));
    }
    protected static function isPhoto($file){
        /*
            Проверка является ли файл изображением (по расширению, а не по начинке, можно передать исполняемый файл как картинку, а потом сменить расширение)
        */
        $file_name=$file['name'];
        if(in_array(pathinfo($file_name)['extension'],self::IMAGE_FORMATS) AND (strpos(pathinfo($file_name)['filename'], '.')===false)){
            return true;
        }
        return false;
    }
}