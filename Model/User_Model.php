<?php
  
  class User{

    public static function check_Email_For_duplication ($dbh, $email) {
      //$sql = 'SELECT * FROM users　WHERE email = :email AND delete_flg = 0'; 
      $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0'; // AND delete_flg = 0';
      $data = array(':email' => $email);

      return queryPost($dbh, $sql, $data);//stmt
    }

    public static function insert_User($dbh, $email, $pass) {
      $sql = 'INSERT INTO users ( email, pass, login_time, at_created) VALUES ( :email, :pass, :login_time, :at_created )';

      $data = array(':email' => $email, 
                    ':pass' => password_hash($pass, PASSWORD_DEFAULT), 
                    ':login_time' => date('Y-m-d H:i:s'),
                    ':at_created' => date('Y-m-d H:i:s'));

      return queryPost($dbh, $sql, $data);//stmt
    }
    
    public static function search_By_Email ($dbh, $email) {
      //$sql = 'SELECT * FROM users　WHERE email = :email AND delete_flg = 0'; 
      $sql = 'SELECT pass,user_id FROM users WHERE email = :email AND delete_flg = 0'; 
      $data = array(':email' => $email);

      return queryPost($dbh, $sql, $data);//stmt
    }

    public static function search_By_User($dbh, $email, $pass) {
      //$sql = 'SELECT * FROM users　WHERE email = :email AND delete_flg = 0'; 
      $sql = 'SELECT count(*) FROM users WHERE email = :email  AND delete_flg = 0';
      $data = array(':email' => $email);

      return queryPost($dbh, $sql, $data);
    }

    public static function search_By_User_Id($dbh, $user_id) {
      //$sql = 'SELECT * FROM users　WHERE email = :email AND delete_flg = 0'; 
      $sql = 'SELECT * FROM users WHERE user_id = :user_id AND delete_flg = 0'; // AND delete_flg = 0';
      $data = array(':user_id' => $user_id);

      return queryPost($dbh, $sql, $data);
    }

    public static function edit_Profile_By_User_Id_and_List ($dbh, $user_id, $data_list_user) {
      $sql = 'UPDATE users SET user_img = :user_img, user_name = :user_name, email = :email, pass = :pass, message = :message  WHERE user_id = :user_id AND delete_flg = 0'; // AND delete_flg = 0';
  
      return queryPost($dbh, $sql, $data_list_user);
    }

    public static function check_Login_Info() {

    }
    /*
    function searchById($id_namem, $id) {
      $sql = 'SELECT * FROM users　WHERE {$id_name} = :{$id_name}';
      $data = array(':{$id}' => $id);
      queryPost($dbh, $sql, $data);
    }
    */

    public static function upadateRow () {
      //見本
      $sql = 'UPDATE users SET () ';
      //$data = array("{$id}" => $id);
      queryPost($dbh, $sql, $data);
    }

    public static function login_User () {
      $sql = "SELECT * FROM users WHERE user_id = user_id";
    }
    
    //query実行
    public static function queryPost($dbh, $sql, $data){
      //var_dump($sql);
      //クエリー作成
      $stmt = $dbh->prepare($sql);
      //プレースホルダに値をセットし、SQL文を実行
      
      $db_data = $stmt->execute($data);
      if(!$db_data){
        debug('クエリに失敗しました。');
        global $err_msg;
        $err_msg['common'] = MSG07;
        return 0;
      }
      debug('クエリ成功。');
      return $stmt;
    }

}

?>