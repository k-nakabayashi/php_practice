<?php
 class Chat_Comment {

   //第３引数は０か１。０か希望者、１が出品者
   public static function insert_Chat_Comment ($dbh, $data_list) {
    $sql = 'INSERT INTO chat_comments (chat_board_id, chat_comment, applicant_exhibitor_flg, at_created ) VALUES (:chat_board_id, :chat_comment, :applicant_exhibitor_flg, :at_created)';
    $data_list[':at_created'] = date('Y-m-d H:i:s');
    return queryPost($dbh, $sql, $data_list);
   }

   public static function select_All_Chat_Comments ($dbh, $data_list) {
    $sql = 'SELECT * FROM chat_comments WHERE chat_board_id = :chat_board_id AND delete_flg = 0';
    return queryPost($dbh, $sql, $data_list);
   }
 }
?>