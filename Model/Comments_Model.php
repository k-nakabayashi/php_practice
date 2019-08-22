<?php

class Comment {

  public static function search_Profiles_and_Comments($dbh, $item_id) {
    $sql = 'SELECT * FROM users INNER JOIN comments ON users.user_id = comments.applicant_id WHERE item_id = :item_id';//　AND delete_flg = 0';
    $data = array(':item_id' => $item_id);
    return queryPost($dbh, $sql, $data);
  }

  public static function search_Profile_and_Comment($dbh, $chat_board_id) {
    $sql = 'SELECT * FROM users INNER JOIN comments ON users.user_id = comments.applicant_id WHERE chat_board_id = :chat_board_id';//　AND delete_flg = 0';
    $data = array(':chat_board_id' => $chat_board_id);
    return queryPost($dbh, $sql, $data);
  }

  public static function select_by_Item_id_and_Applicant_id ($dbh, $data_list_item) {
    $sql = 'SELECT * FROM comments WHERE applicant_id = :applicant_id AND item_id = :item_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list_item);
  }

  public static function insert_Comment_to_Item($dbh, $data_list_item) {
    $sql = 'INSERT INTO comments (item_id, applicant_id, comment, at_created ) VALUES (:item_id, :applicant_id, :comment, :at_created)';
    $data_list_item[':at_created'] = date('Y-m-d H:i:s');
    return queryPost($dbh, $sql, $data_list_item);
  }

  public static function set_Chat_Board_Id_on_Comment ($dbh, $data_list_item) {
    $sql = 'UPDATE comments SET chat_board_id = :chat_board_id, chat_board_flg = 1 WHERE item_id = :item_id AND applicant_id = :applicant_id AND delete_flg = 0';
    //$data_list_item[':at_upadted'] = date('Y-m-d H:i:s');
    return queryPost($dbh, $sql, $data_list_item);
  }

  public static function count_Comment ($dbh, $data_list_item) {
    $sql = 'SELECT item_id, count(*) as count_comment FROM comments WHERE item_id = :item_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list_item);
  }


  
}
?>