<?php

//require($_SERVER['DOCUMENT_ROOT'].'/Controller/Super_User_Controller.php');
//require($_SERVER['DOCUMENT_ROOT'].'/Model/Item_Model.php');
//require($_SERVER['DOCUMENT_ROOT'].'/Model/User_Model.php');

function display_All_Items_with_Chat_flg($data_list) {
  //例外処理
  try {
    //コネクト
    $dbh = dbConnect();

    //アイテムごとにコメントがあるか確認が必要か？

    //
    $stmt =  Comment::select_All_Items_and_Comments($dbh, $data_list);
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


function select_By_User_Id($user_id){

  try {
    //コネクト
    $dbh = dbConnect();
  
    //sql実行
    //ポスト値emailを使い を該当するユーザーデータを探す
    $stmt = User::search_By_User_Id($dbh, $user_id);//Super_Modelを継承したメソッド
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //結果がない場合
    if (empty($user_info)) {
      return null;
    }

    return $user_info;
  
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    global $err_msg;
    $err_msg['common'] = MSG07;
    return null;
  }
}

function edit_Profile ($user_id, $data_list_user) {
  try {
    //コネクト
    $dbh = dbConnect();
    $dbh->beginTransaction();

    try {
    
      //sql実行
      $stmt = User::edit_Profile_By_User_Id_and_List ($dbh, $user_id, $data_list_user);//Super_Modelを継承したメソッド

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



function comment_to_Item ($data_list_item, $comment) {
  try {
    //コネクト
    $dbh = dbConnect();
    $dbh->beginTransaction();
    try {
      //コメント済みか確認　
      
      $stmt = Comment::select_by_Item_id_and_Applicant_id($dbh, $data_list_item);
      $check_duplication = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!(empty($check_duplication))) {
        global $err_msg;
        $err_msg["comment"] = MSG20;
        return false;
      }
      

      //イイねしたい
      //まずイイねを取ってくる
      $stmt = Item::select_by_Item_id($dbh, $data_list_item[':item_id']);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $data_list_item_for_Good = array(':item_id' => $result['item_id'], ':item_good_count' => $result['item_good_count']);

      //いいねする
      $stmt = Item::make_Good_to_Item($dbh,  $data_list_item_for_Good);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      //コメントする
      $data_list_item['comment'] = $comment;
      $stmt =  Comment::insert_Comment_to_Item($dbh, $data_list_item);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!(empty($result))) {
        global $err_msg;
        $err_msg["common"] = MSG07;
        return false;

      } else {

        $dbh->commit();
        return $result;
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
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

function  display_Applicant_Profile($chat_board_id) {
  try {
    //コネクト
    $dbh = dbConnect();
    

    $stmt =  Comment::search_Profile_and_Comment($dbh, $chat_board_id);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($result)) {
      global $err_msg;
      $err_msg["common"] = MSG07;
      return;
    }
    
    return $result;

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    global $err_msg;
    $err_msg['common'] = MSG07;
    return null;
 }
}

//chat_board_idとapplicant_exhibitor_flg
function create_Chat_Comment ($data_list) {
  try {
    //コネクト
    $dbh = dbConnect();
    
    $stmt = Chat_Comment::insert_Chat_Comment($dbh, $data_list);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!(empty($err_msg))) {
      global $err_msg;
      $err_msg["common"] = MSG07;
      return;
    }
    
    return $result;

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    global $err_msg;
    $err_msg['common'] = MSG07;
    return null;
 }
}

//chat_board_id
function diplay_Chat_Comments ($data_list) {
  try {
    //コネクト
    $dbh = dbConnect();
    
    $stmt =  Chat_Comment::select_All_Chat_Comments($dbh, $data_list);
    $result = $stmt->fetchAll();

    if (!(empty($err_msg))) {
      global $err_msg;
      $err_msg["common"] = MSG07;
      return;
    }
    
    return $result;

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    global $err_msg;
    $err_msg['common'] = MSG07;
    return null;
 }
}

//item_id, applicant_id, exhibitor_id
function get_Chat_Board_Data ($data_list) {
  try {
    //コネクト
    $dbh = dbConnect();
    
    $stmt =  Chat_Board::select_Chat_Board_by_Three_Ids($dbh, $data_list);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!(empty($err_msg))) {
      global $err_msg;
      $err_msg["common"] = MSG07;
      return;
    }
    
    return $result;

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    global $err_msg;
    $err_msg['common'] = MSG07;
    return null;
 }
}

function sort_My_Items_by_Chat ($data_list) {
    //例外処理
    try {
      //コネクト
      $dbh = dbConnect();
  
      //アイテムごとにコメントがあるか確認が必要か？
  
      //applicant_idとexhibitor_id　中身はどっちもuser_id
      //どっちかでヒットすればいい
      $stmt =  Item::select_Items_with_My_Comments_and_Chat_Board_Id($dbh, $data_list);
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

//applicant_id=user_id
function sort_My_Items_by_Want ($data_list) {
  //例外処理
  try {
    //コネクト
    $dbh = dbConnect();

    //アイテムごとにコメントがあるか確認が必要か？

    //applicant_idとexhibitor_id　中身はどっちもuser_id
    //どっちかでヒットすればいい
    $stmt =  Item::select_Items_with_My_Comments($dbh, $data_list);
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

//exhibitor_id
function sort_My_Items_by_Put ($data_list) {
    //例外処理
    try {
      //コネクト
      $dbh = dbConnect();
  
      //アイテムごとにコメントがあるか確認が必要か？
  
      //applicant_idとexhibitor_id　中身はどっちもuser_id
      //どっちかでヒットすればいい
      $stmt =  Item::select_My_Items_with_Chat_Boards($dbh, $data_list);
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
/*
Super_User		display_Item_Page	
  require($_SERVER['DOCUMENT_ROOT']."/DAO/DAO_User.php");

*/

/*
  //イイね！する
	function make_Good_By_User () {
    UPDATE items SET good_count = good_count + 1 WHERE item_id AND ( NOT exhibitor_Id = :user_id
  };
			
	function comment_to_Item() {
    if ( exhiibitor_id != user_id )	
    
      check_Duplication_of_Comment	{ 
        SELECT * FROM comments WHER applicant_id = user_id AND item_id = : item_id:→emptyならOK 
      }
    
      if(empty(check_Duplication_of_Comment)) {	
        create_Comment	INSER INTO comments VALUES () 
      }
  }
			
Super_User		sort_User_Items	SELECT * FROM items;
		sort_Items_to_Chat	SELECT * FROM item WHERE item_id =
			(SELECT item_id FROM chat_commenst WHERE applicant_id = :user_id);
			
		edit_User_Info	ここではまだ起動しない
			
			
		confirm_User_Info_Edited	update users set   *   where user_id = : user_id;
			
		open_Chat_Board	
	request_scope		
			
		display_My_Profile	SELECT * FROM users WHERE user_id = : user_id; ※セッションを使う
			
		display_My_Comment	SELECT * FROM comments WHERE applicant_id = : user_id AND item_id = : item_id;
			
	if( applicant_exhibitor_flg == false )	diplay_Chat_Comments_by_Applicant	select * from chat_comments  where chat_board_id = (
			select chat_board_id from chat_boards where applicant_id = : user_id AND item_id = : item_id);
			
	if( applicant_exhibitor_flg == false )	create_Chat_Comments_by_Applicant	INSERT INTO chat_comments VALUES();
			SELECT * FROM chat_comments  WHERE chat_board_id = : chat_board_id;

*/

?>