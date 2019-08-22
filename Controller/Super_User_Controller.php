<?php
  //namespace Super_User;
  require($_SERVER['DOCUMENT_ROOT'].'/Model/Item_Model.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Model/User_Model.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Model/Comments_Model.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Model/Chat_Boards_Model.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Model/Chat_Comments_Model.php');

  require($_SERVER['DOCUMENT_ROOT'].'/Controller/Login_User_Controller.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Controller/Exhibitor_Controller.php');

  function  create_User($email, $pass) {

      //例外処理
    try {
      //コネクト
      $dbh = dbConnect();
      $dbh->beginTransaction();
      try {
        //email重複判定
        $stmt = User::check_Email_For_duplication($dbh, $email);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        ///メール重複の判定
        $duplication = array_shift($result);
        if (!(empty($duplication))) {
          
          //error_log('エラー発生:' . $e->getMessage());
          global $err_msg;
          $err_msg['duplication'] = MSG08;

          return $stmt;

        } 

        //登録する
        $stmt = User::insert_User($dbh, $email, $pass);
        if($stmt) {
          //ログイン有効期限（デフォルトを１時間とする）
          $sesLimit = 60*60;
          // 最終ログイン日時を現在日時に
          $_SESSION['login_date'] = time();
          $_SESSION['login_limit'] = $sesLimit;
          
          // ユーザーIDを格納
          $stmt = User::search_By_Email($dbh, $email);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          $user_id = $result['user_id'];
          $_SESSION['user_id'] = $user_id;//$dbh->lastInsertId();

          debug('セッション変数の中身：'.print_r($_SESSION,true));

          $dbh->commit();
          return $stmt;
        }
      } catch (PDOEException $e) {
        global $err_msg;
        $err_msg['common'] = MSG07;

        $dbh->rollback();
        return $stmt;
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      global $err_msg;
      $err_msg['common'] = MSG07;
      return $stmt;
    }
  }

  function  login_User($email, $pass) {
    //例外処理
    try {
      //コネクト
      $dbh = dbConnect();
      
      //フォーム値とテーブル情報を照合したい

      //ポスト値emailを使い を該当するユーザーデータを探す
      $stmt = User::search_By_Email($dbh, $email);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $pass_check = null;

      //登録されてるemailかチェックして取ってきた内容をチェック
      if (empty($result)) {
        global $err_msg;
        $err_msg["email_pass"] = MSG09;

        
      } else {
         //取ってきたパスを逆ハッシュして比較
        $pass_check = password_verify($pass, array_shift($result));
      }


      if ($pass_check == true) {
        //セッション保持
        $user_id = $result['user_id'];
        //ログイン有効期限（デフォルトを１時間とする）
        $sesLimit = 60*60;
        // 最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time();
        $_SESSION['login_limit'] = $sesLimit;
        // ユーザーIDを格納
        $_SESSION['user_id'] = $user_id;//$dbh->lastInsertId();

        debug('セッション変数の中身：'.print_r($_SESSION,true));

        //ログインする
        return true;

      } else {
        //パスワードが違う
        global $err_msg;
        $err_msg["email_pass"] = MSG09;
        return false;
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
      return $stmt;
   }
}

  function display_All_Items() {
        //例外処理
        try {
          //コネクト
          $dbh = dbConnect();
          
          $stmt =  Item::select_All_Items($dbh);
          $result = $stmt->fetchAll();

          if (!(empty($err_msg))) {
            global $err_msg;
            $err_msg["common"] = MSG07;

          } else {
            return $result;
          }
  
    
        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
       }
  }


  function  display_Item_Page($item_id) {
    try {
      //コネクト
      $dbh = dbConnect();
      
      //フォーム値とテーブル情報を照合したい

      //ポスト値emailを使い を該当するユーザーデータを探す
      $stmt =  Item::select_by_Item_id($dbh, $item_id);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);


      //登録されてるemailかチェックして取ってきた内容をチェック
      if (empty($result)) {
        global $err_msg;
        $err_msg["common"] = MSG07;

      } else {
        return $result;
      }


    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
   }
  }

  function  display_Exhibitor_Name($exhibitor_id) {
    try {
      //コネクト
      $dbh = dbConnect();
      
      //フォーム値とテーブル情報を照合したい

      //ポスト値emailを使い を該当するユーザーデータを探す
      $stmt =  User::search_By_User_Id($dbh, $exhibitor_id);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);


      //登録されてるemailかチェックして取ってきた内容をチェック
      if (empty($result)) {
        global $err_msg;
        $err_msg["common"] = MSG07;

      } else {
        return $result;
      }


    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
   }
  }

  function  display_Item_Applicants_Comments($item_id) {
    try {
      //コネクト
      $dbh = dbConnect();
      

      $stmt =  Comment::search_Profiles_and_Comments($dbh, $item_id);
      $result = $stmt->fetchAll();
      /*
      if (empty($stmt)) {
        global $err_msg;
        $err_msg["common"] = MSG07;

      } else {
        return $result;
      }
      */

      return $result;//中身がなくてもいいため


    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
   }
  }
  
  function sort_New_Items () {
    //例外処理
    try {
      //コネクト
      $dbh = dbConnect();

      $stmt =  Item::select_All_Items_by_New_Order($dbh);
      $result = $stmt->fetchAll();

      if (empty($result)) {
        global $err_msg;
        $err_msg["common"] = MSG07;

      } else {
        return $result;
      }


    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
  function sort_Items_by_Good () {
    //例外処理
    try {
      //コネクト
      $dbh = dbConnect();
      
      $stmt =  Item::select_All_Items_by_Item_Good_Count($dbh);
      $result = $stmt->fetchAll();

      if (empty($result)) {
        global $err_msg;
        $err_msg["common"] = MSG07;

      } else {
        return $result;
      }


    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }

  function sort_Items_by_Chat () {
    //例外処理
    try {
      //コネクト
      $dbh = dbConnect();
      
      $stmt =  Item::select_All_Items_by_Chat_Board_Count($dbh);
      $result = $stmt->fetchAll();

      if (empty($result)) {
        global $err_msg;
        $err_msg["common"] = MSG07;

      } else {
        return $result;
      }


    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }    
  }


  function  send_Reset_Mail() {}
  

  function  reset_Pass() {}
  function  confirm_Authen_Reset() {}
  function  update_Pass() {}
    
  
  function  display_Link_to_Chat() {}
  
  
  function  display_Link_to_Chat_by_Exihibitor() {}
  
  
  function  display_Link_to_Chat_by_Applicant() {}
  
  function dbConnect(){
    //接続情報
    $dsn = 'mysql:dbname=free_auction;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    //DBへの接続準備
    $options = array(
      // SQL実行失敗時にはエラーコードのみ設定
      PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
      // デフォルトフェッチモードを連想配列形式に設定
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
      // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
  
    // PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);
  
    return $dbh;
  
  }
    
?>