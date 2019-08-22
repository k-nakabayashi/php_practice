<?php
  //item_id, applicant_id, exhibitor_id
  function start_Chat($data_list){
    //chat_board作成に必要なデータだけがある。
    try {
      //コネクト
      $dbh = dbConnect();
      $dbh->beginTransaction();
      
      try {

        //chatボードがすでにあるか確認　念の為
        $stmt = Chat_Board::check_Duplication_of_Chat_Board_for_Start ($dbh, $data_list);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);//帰り値はfalseを期待

        if ($result != false) {
          global $err_msg;
          $err_msg['common'] = MSG21;
          return;

        }

        //chat_boardを作る
        Chat_Board::create_Chat_Board($dbh, $data_list);
        $stmt = Chat_Board::get_Data_after_creating_Chat_Board($dbh, $data_list);
        $chat_board_data = $stmt->fetch(PDO::FETCH_ASSOC);//item_idとapplicant_Iがほしい
        
        if (empty($chat_board_data)) {
          global $err_msg;
          $err_msg['common'] = MSG07;
          return;

        }
        //args => chat_borad_id と絞り込み用でitem_id, applicant_id,
        $data_list = array(
          ':item_id' => $chat_board_data['item_id'],
          ':applicant_id' => $chat_board_data['applicant_id'],
          ':chat_board_id' => $chat_board_data['chat_board_id']
        );
        //commentレコードにchat_board_idを付与
        Comment::set_Chat_Board_Id_on_Comment($dbh, $data_list);
        
        
        //commentのレコードをカウント item_idがあればいい
        $data_list = array( ':item_id' => $chat_board_data['item_id'] );
        $stmt = Comment::count_Comment($dbh, $data_list);
        $comment_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $data_list = array(
          ':item_id' => $comment_data['item_id'],
          ':chat_board_count' => $comment_data['count_comment']
        );

        //itemレコードを更新する item_id　と　count_comment
        Item::set_Chat_Count_on_Item($dbh, $data_list);

        //判定
        if (!empty($err_msg)) {
          return;
        }

        //成功時
        $dbh->commit();
        return  $chat_board_data['chat_board_id'];

      } catch (PDOEException $e) {
        global $err_msg;
        $err_msg['common'] = MSG07;
  
        $dbh->rollback();
        return;
      }
    } catch (PDOEException $e) {
      global $err_msg;
      $err_msg['common'] = MSG07;

      $dbh->rollback();
      return;
    }
  }

  function open_Chat_Board ($data_list) {
    try {
      //コネクト
      $dbh = dbConnect();
      $dbh->beginTransaction();
      
      try {
        //引数はchat_board_id
        $stmt = Chat_Board::select_Chat_Board_by_Chat_Board_id($dbh, $data_list);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);//返り値は連想は配列をきたい

        if (empty($result)) {
          global $err_msg;
          $err_msg['common'] = MSG21;
          return;

        }

        //成功時
        $dbh->commit();
        return  $result['chat_board_id'];

      } catch (PDOEException $e) {
        global $err_msg;
        $err_msg['common'] = MSG07;
  
        $dbh->rollback();
        return;
      }
    } catch (PDOEException $e) {
      global $err_msg;
      $err_msg['common'] = MSG07;

      $dbh->rollback();
      return;
    }

  }

  function put_Item ($data_list_item) {
    try {
      //コネクト
      $dbh = dbConnect();
      $dbh->beginTransaction();
  
      try {
      
        //sql実行
        $stmt = Item::put_Item_By_Exhibitor_Id_and_List ($dbh, $data_list_item);//Super_Modelを継承したメソッド
  
        $dbh->commit();
  
        //不要かな?編集画面内でこのケースは対応済み。以前のDBデータを$user_imgにいれてる。
        // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
        //$pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1;
        
        return true;
  
        
      } catch (PDOEException $e) {
        global $err_msg;
        $err_msg['common'] = MSG07;
  
        $dbh->rollback();
        return false;
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      global $err_msg;
      $err_msg['common'] = MSG07;
      return null;
      return false;
    }
  }

  function edit_Put_Item ($data_list_item) {
    try {
      //コネクト
      $dbh = dbConnect();
      $dbh->beginTransaction();
  
      try {
      
        //sql実行
        $stmt = Item::edit_Put_Item_by_Ehibitor_Item ($dbh, $data_list_item);//Super_Modelを継承したメソッド
        if (empty($err_msg)) {
          $dbh->commit();
          
        } else {
          return false;
        }

  
        //不要かな?編集画面内でこのケースは対応済み。以前のDBデータを$user_imgにいれてる。
        // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
        //$pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1;
        
        return true;
  
        
      } catch (PDOEException $e) {
        global $err_msg;
        $err_msg['common'] = MSG07;
  
        $dbh->rollback();
        return false;
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      global $err_msg;
      $err_msg['common'] = MSG07;
      return null;
      return false;
    }
  }
?>