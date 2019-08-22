<?php
  
  class Item {

    public static function put_Item_By_Exhibitor_Id_and_List ($dbh, $data_list_item) {
      
      $sql = 'INSERT INTO items
        (item_img1,
        item_img2,
        item_img3,
        item_title,
        item_thumb,
        item_detail,
        item_category,
        item_status,
        item_area,
        exhibitor_id,
        at_created) 
        VALUES 
        (:item_img1,
        :item_img2,
        :item_img3,
        :item_title,
        :item_thumb,
        :item_detail,
        :item_category,
        :item_status,
        :item_area,
        :exhibitor_id,
        :at_created)';      
            
        $data_list_item[':at_created'] = date('Y-m-d H:i:s');
        return queryPost($dbh, $sql, $data_list_item);
    }

    public static function edit_Put_Item_by_Ehibitor_Item ($dbh, $data_list_item) {
      
      $sql = 'UPDATE items SET     
        item_img1 = :item_img1,
        item_img2 = :item_img2,
        item_img3 = :item_img3,
        item_title = :item_title,
        item_thumb = :item_thumb,
        item_detail = :item_detail,
        item_category = :item_category,
        item_status = :item_status,
        item_area = :item_area,
        at_created = :at_created WHERE item_id = :item_id AND exhibitor_id = :exhibitor_id';
            
      $data_list_item[':at_created'] = date('Y-m-d H:i:s');
      return queryPost($dbh, $sql, $data_list_item);
    }

    
    public static function select_All_Items ($dbh) {
      $sql = 'SELECT * FROM items WHERE delete_flg = :delete_flg';
      $data_list_item = array(":delete_flg" => 0);
      return queryPost($dbh, $sql, $data_list_item);
    }
    

    public static function select_by_Item_id($dbh, $item_id){

      $sql = 'SELECT * FROM items WHERE item_id = :item_id AND delete_flg = 0';
      $data[':item_id'] = $item_id;
      return queryPost($dbh, $sql, $data);
    }

    public static function select_All_Items_by_New_Order($dbh) {
      $sql = 'SELECT * FROM items WHERE delete_flg = :delete_flg ORDER BY at_updated DESC';
      $data_list_item = array(":delete_flg" => 0);
      return queryPost($dbh, $sql, $data_list_item);
    }
    
    public static function select_All_Items_by_Item_Good_Count($dbh) {
      $sql = 'SELECT * FROM items WHERE delete_flg = :delete_flg ORDER BY item_good_count DESC';
      $data_list_item = array(":delete_flg" => 0);
      return queryPost($dbh, $sql, $data_list_item);
    }
    
    public static function select_All_Items_by_Chat_Board_Count($dbh) {
      $sql = 'SELECT * FROM items WHERE delete_flg = :delete_flg ORDER BY chat_board_count DESC';
      $data_list_item = array(":delete_flg" => 0);
      return queryPost($dbh, $sql, $data_list_item);
    }

    public static function make_Good_to_Item ($dbh, $data_list_item) {
      $sql = 'UPDATE items SET item_good_count = :item_good_count  WHERE item_id = :item_id AND delete_flg = 0';
      $data_list_item[':item_good_count'] = $data_list_item[':item_good_count'] + 1;
      return queryPost($dbh, $sql, $data_list_item);
    }

    //item_idとカウント結果が引数
    public static function set_Chat_Count_on_Item ($dbh, $data_list_item) {
      $sql = 'UPDATE items SET chat_board_count = :chat_board_count  WHERE item_id = :item_id AND delete_flg = 0';
      return queryPost($dbh, $sql, $data_list_item);
    }
    
    public static function select_Items_with_My_Comments_and_Chat_Board_Id ($dbh, $data_list) {
      $sql = 'SELECT * FROM items LEFT JOIN comments ON items.item_id = comments.item_id  WHERE (exhibitor_id = :exhibitor_id OR applicant_id = :applicant_id ) AND ( chat_board_id IS NOT NULL ) AND (  items.delete_flg = 0 AND comments.delete_flg = 0 )';
      return queryPost($dbh, $sql, $data_list);
    }

    public static function select_Items_with_My_Comments ($dbh, $data_list) {
      $sql = 'SELECT * FROM items LEFT JOIN comments ON items.item_id = comments.item_id  WHERE (applicant_id = :applicant_id AND comment IS NOT NULL ) AND (  items.delete_flg = 0 AND comments.delete_flg = 0 )';
      return queryPost($dbh, $sql, $data_list);
    }

    public static function select_My_Items_with_Chat_Boards ($dbh, $data_list) {
      $sql = 'SELECT * FROM items LEFT JOIN chat_boards ON items.item_id = chat_boards.item_id WHERE chat_boards.exhibitor_id = :exhibitor_id AND items.delete_flg = 0 AND chat_boards.delete_flg = 0';
      return queryPost($dbh, $sql, $data_list);
    }
  }
?>