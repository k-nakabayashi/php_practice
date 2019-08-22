<?php

class Chat_Board {

  public static function check_Duplication_of_Chat_Board ($dbh, $data_list){
    $sql = 'SELECT * FROM chat_boards WHERE chat_board_id = :chat_board_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list);
  }

  public static function select_Chat_Board_by_Chat_Board_id ($dbh, $data_list){
    $sql = 'SELECT * FROM chat_boards WHERE chat_board_id = :chat_board_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list);
  }

  public static function check_Duplication_of_Chat_Board_for_Start ($dbh, $data_list){
    $sql = 'SELECT * FROM chat_boards WHERE item_id = :item_id AND applicant_id = :applicant_id AND exhibitor_id = :exhibitor_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list);
  }

  public static function select_Chat_Board_by_Three_Ids ($dbh, $data_list){
    $sql = 'SELECT * FROM chat_boards WHERE item_id = :item_id AND applicant_id = :applicant_id AND exhibitor_id = :exhibitor_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list);
  }

  public static function create_Chat_Board ($dbh, $data_list) {
    $sql = 'INSERT INTO chat_boards ( item_id, applicant_id, exhibitor_id, at_created ) VALUES (:item_id, :applicant_id, :exhibitor_id, :at_created)';

    $data_list[':at_created'] = date('Y-m-d H:i:s');
    return queryPost($dbh, $sql, $data_list);
  }
  public static function  get_Data_after_creating_Chat_Board ($dbh, $data_list) {
    $sql = 'SELECT * FROM chat_boards WHERE item_id = :item_id AND applicant_id = :applicant_id AND exhibitor_id = :exhibitor_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list);
  }

}
?>