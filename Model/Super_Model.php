<?php

class Super {
  //DBコネクト

  public static function dbConnect(){
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

  public static function selectAll ($dbh, $table) {
    $sql = 'SELECT * FROM {$table}';
    queryPost($dbh, $sql, $data);
  }
/*
  public static function search_By_Id($dbh, $table, $id_name, $id) {
    $sql = 'SELECT * FROM {$table}　WHERE {$id} = :{$id}';
    $data = array(':{$id}' => $id);
    queryPost($dbh, $sql, $data);
  }
  */

  public static function deleteRow ($dbh, $table, $id_name, $id) {
    $sql = 'UPDATE {$table} () SET delte_flg =  True WHERE ' + $id_name + ' = :' + $id_name ;
    $data = array(':'.$id_name => $id);
    queryPost($dbh, $sql, $data);
  }

  public static function reviveRow ($dbh, $table, $id_name, $id) {
    $sql = 'UPDATE {$table} () SET delte_flg =  False WHERE ' + $id_name + ' = :' + $id_name ;
    $data = array(':'.$id_name => $id);
    queryPost($dbh, $sql, $data);
  }

}


?>