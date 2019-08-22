
<?php
  //共通変数・関数ファイルを読込み
  require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
  require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

  //ユーザーの情報をとる
  $user_info = select_By_User_Id($_SESSION['user_id']);
  


//チャットを開く
if (!(empty($_POST))) {
  $item_id = $_POST['item_id'];
  $applicant_id = $user_info['user_id'];
  $exhibitor_id = $_POST['exhibitor_id'];

  //itemデータがほしい
  $item = display_Item_Page($item_id);



  //chat_board_idがほしい
  $data_list = array(
    ':item_id' => $item_id,
    ':applicant_id' => $applicant_id,
    ':exhibitor_id' => $exhibitor_id
  );
  $chat_board = get_Chat_Board_Data($data_list);

    //chat_boardがあるか判定
  if (!(empty($chat_board))) {

    $_SESSION['item'] = $item;
    $_SESSION['chat_board_id'] = $chat_board['chat_board_id'];
    header("Location:/Views/auction/Chat-Board.php");
    exit;

  } else {
    global $err_msg;
    $err_msg['chat_board'] = MSG22;
  }

}

//アイテムをとってくる
$itme_list = null;
if (empty($_GET['my_sort_style'])) {
  //チャットうむの判定も仕込む→無理。ソート後にやる
  $data_list = array(
    ':exhibitor_id' => $user_info['user_id'],
    ':applicant_id' => $user_info['user_id']
  );
  $item_list = display_All_Items();//_with_Chat_flg($data_list);//commets LEFT JOIN itemsでやってる。
  //$item_list = display_All_Items_with_Chat_flg($data_list);//commets LEFT JOIN itemsでやってる。

} 

if (!empty($_GET['my_sort_style'])) {
  $sort_style = $_GET['my_sort_style'];

  switch ($sort_style) {
    case 'all':
      $item_list =  display_All_Items();
      break;

    case 'want':
      $data_list = array(
        ':applicant_id' => $user_info['user_id']
      );
      $item_list = sort_My_Items_by_Want($data_list);
      break;

    case 'chat':
      $data_list = array(
        ':applicant_id' => $user_info['user_id'],
        ':exhibitor_id' => $user_info['user_id']
      );
      $item_list = sort_My_Items_by_Chat($data_list);
      break;

    case 'put':
      $data_list = array(
        ':exhibitor_id' => $user_info['user_id']
      );
      $item_list = sort_My_Items_by_Put($data_list);
      break;    
  }
}
?>

<?php
  //html
  include($_SERVER['DOCUMENT_ROOT']."/views/component/head.php");
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/header_for_member.php");

?>
<div class="u-Container-Y--large">
  <div class="l-Main-Wrapper">
    <div class="l-Main-Wrapper__Side-Menu">
        <div class="l-Side-Menu">
          <div class="mypqge-profle">
            <p class="mypqge-profle__name">
              <?php 
                if (!(empty($user_info['user_name']))) {
                  echo $user_info['user_name'];
                }                   
              ?>
              <span>さん</span></p>
          </div>
          <ul class="mypage-Link-List">
            <li class="u-has-Top-Margin20" ><a href="/Views/my/MyProfile.php">マイプロフィール</a></li>
            <li class="u-has-Top-Margin20" ><a href="/Views/my/edit-My-Profile.php">プロフィール編集</a></li>
            <li class="u-has-Top-Margin20" ><a href="/Views/my/put-My-Item.php">出品</a></li>
          </ul>
        </div>
    </div>
        <!--l-Side-Menu-->
    <div class="l-Main-Wrapper__Main-Menu">
        <div class="l-Main-Menu c-Form-Select-Parent"><!--幅と余白は確定-->

          <!--機能　ソートとペジネーション-->
          <form action="" method="GET">
            <div class="c-Item-Function c-Form-Select">

                <!---ソート-->
              <div class="c-Item-Function__srort">              
                <div class="c-Form-Drop c-Form-Select">
                  <label>どれを検索ますか？</label>
                  <div class="c-Form-Drop__select c-Form-Select__wrapper u-has-No-Left-Margin c-Form-Select__Arrow-Down">
                    <select class="c-Form-Select-Down" name="my_sort_style" id="">
                      <option value="all">全て</option>
                      <option value="want">欲しいモノ</option>
                      <option value="chat">やりとり中</option>
                      <option value="put">出品中</option>
                    </select>
                  </div>
                </div>
              </div>
              <!--ペジネーション switch文で受け取る-->
              <!--
              <div class="c-Item-Function_pagenation">
                  <div class="c-Form-Drop  c-Form-Select">
                    <label>表示件数の変更</label>
                    <div class="c-Form-Drop__select c-Form-Select__wrapper u-has-No-Left-Margin c-Form-Select__Arrow-Down">
                      <select class="c-Form-Select-Down" name="" id="">
                          <option value="">30件ずつ表示</option>
                          <option value="">20件ずつ表示</option>
                          <option value="">10件ずつ表示</option>
                      </select>
                    </div>
                  </div>
              </div>
              -->
              <div class="top-Sort-Form-Button">
                  <input type="submit" value="絞り込む">
                </div>
            </div>
          </form>
          


          <!--一覧表示--->
          <ul class="c-obj-List">
          <?php echo display_Err_Msg('chat_board'); ?>
          <?php  foreach($item_list as $item): ?>
            <li class="mypage-item-List__obj-Thumbnail c-obj-List__obj-Thumbnail"><!--関節クラス扱い-->
              <div class="c-obj-Thumbnail">
                <div class="mypage-iteme-Thumbnail__img">
                    <img src="<?php echo $item['item_img1']; ?>" alt="アイテム画像">
                    <!--擬似要素でたすき掛け-->
                </div>
                <div class="mypage-item-Thumbnail__paragraph c-obj-Thumbnail__paragraph">
                  <h3 class="u-Text-Style5"><?php echo $item['item_title']; ?></h3>
                  <ul class="c-obj-Thumbnail__link-list">
                    <li><a href="/Views/item/Item-Detail.php?item_id=<?php echo $item['item_id']; ?>">商品詳細へ</a></li>
                  
                    <!--希望者ﾖ-->
                    <?php if ($user_info['user_id'] != $item['exhibitor_id']): ?>
                      <form action="" method="POST">
                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                        <input type="hidden" name="exhibitor_id" value="<?php echo $item['exhibitor_id']; ?>">
                        <li><input type="submit" value="チャットへ"></li>
                      </form>
                    <?php endif; ?>

                    <!--出品者用-->
                    <?php if ($user_info['user_id'] == $item['exhibitor_id']):?>
                      <li><a href="/Views/my/edit-My-Item.php?item_id=<?php echo $item['item_id']; ?>">出品内容詳細へ</a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </li>            
            <?php endforeach; ?>
          </ul>


        </div>
        <!--l-Main-Menu-->
    </div>
  </div>
</div>
<!--u-Container-Y--large-->
<?php
//フッター
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/footer.php");

?>
