<?php
  //sessionをとりたい
  require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
  require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");
  
  //html
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/head.php");

  // ログインしている場合のheader
  if( !empty($_SESSION['login_date']) ){
    debug('ログイン済みユーザーです。');

    // 現在日時が最終ログイン日時＋有効期限を超えていた場合
    if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
      debug('ログイン有効期限オーバーです。');

      // セッションを削除（ログアウトする）
      session_destroy();

    }else{
      debug('ログイン有効期限以内です。');
      include($_SERVER['DOCUMENT_ROOT']."/Views/component/header_for_member.php");
    }

  } else {
    //未ログインユーザー用のヘッダー
    include($_SERVER['DOCUMENT_ROOT']."/Views/component/header.php");
  }
  
  global $item_id;
  if (!(empty($_GET['item_id']))) {
    $item_id = $_GET['item_id'];
  }
  //商品詳細
  $item = display_Item_Page($item_id);

  //存在しないアイテムのアクセスは禁止
  if (empty($item)) {
    header("Location:/");
    exit;
  }

  if (!(empty($_POST))) {
    $post_switch = $_POST['post_switch'];

    //チャットとコメントの振り分け
    switch ( $post_switch) {

      case 'chat':

        $post_user = $_POST['user'];
        $post_chat_board_id = $_POST['chat_board_id'];
        $resut = null;

        //出品者と希望社の振り分け
        switch ($post_user) {

          case 'exhibitor':
            if (!empty($post_chat_board_id)) {

              $data_list = array(
                ':chat_board_id' => $post_chat_board_id
              );

              $result = open_Chat_Board($data_list);//成功すると、$chat_board_data['chat_board_id']　

            } else {

              $data_list = array(
                ':item_id' => $item['item_id'],
                ':applicant_id' => $_POST['user_id'],
                ':exhibitor_id' => $item['exhibitor_id']
              );
            
              $result = start_Chat($data_list);// 成功すると、$chat_board_data['chat_board_id']
            }
            break;
          case 'user':
            $data_list = array(
              ':chat_board_id' => $post_chat_board_id
            );
            $result = open_Chat_Board($data_list);//成功すると、$chat_board_data['chat_board_id']　       
        }

        //成功判定。※失敗するとempty
        if (!(empty($result))) {
          $_SESSION['item'] = $item;
          $_SESSION['chat_board_id'] = $result;
          //遷移先では受け取ったら、このセッション速削除?
          header("Location:/Views/auction/Chat-Board.php");
          exit;
          break;

        } 
        break;//一層目のbreak

      case 'comment':
        $comment = $_POST["comment"];

        validRequired($comment, 'comment');
    
        if (empty($err_msg)) {
    
          $data_list_item = array(':item_id' => $item['item_id'], ':applicant_id' => $_SESSION['user_id']); //, ':comment' => $comment); 呼び出し先でつかう
          //コメント済みか確認必要
          $result = comment_to_Item($data_list_item, $comment);
    
        }
        break;
    }
  }

  //再表示のためここに。POST送信後に読み込めば、最新データで表示できる
  //出品者
  $exhibitor_info = display_Exhibitor_Name($item['exhibitor_id']);
  //コメントを表示
  $comment_list_width_profile = display_Item_Applicants_Comments($item['item_id']);
?>

<div class="u-Container-Y--large">
  <div class="item-Main-Wrapper">
  <?php if (!empty($err_msg['common'])):?>
    <p><?php echo $err_msg['common']; ?></p>
  <?php endif; ?>
    <!--l-Side-Menu-->
    <div class="item-Main-Wrapper__Item-Detail">
      <!--一覧表示--->
      <div class="item-Detail">
        <div class="item-Detail-wrapper u-clearfix">
          <div class="Item-Img-Wrapper">
            <div class="item-Detail__img">
                <img src="<?php echo $item['item_img1']; ?>" alt="アイテム画像">
                <!--擬似要素でたすき掛け-->
            </div>
            <div class="item-Detail__img">
                <img src="<?php echo $item['item_img2']; ?>" alt="アイテム画像">
                <!--擬似要素でたすき掛け-->
            </div>
            <div class="item-Detail__img">
                <img src="<?php echo $item['item_img3']; ?>" alt="アイテム画像">
                <!--擬似要素でたすき掛け-->
            </div>
          </div>
          <div class="item-Detail__profile">
            <div class="item-Detail__Profile">
              <div class="item-Profile">
                <h3 class="item-Profile__title"><?php echo $item['item_title']; ?></h3>

                <div class="item-Profile__description">
                  <div class="item-Profile__More-Details">
                    <div class="item-More-Details">
                      <p class="item-More-Details__explain"><?php echo $item['item_thumb']; ?></p>
                      <table class="item-More-Details__feature u-has-Top-Margin20">
                        <tr class="border"><th>カテゴリ</th><td><?php echo $item['item_category']; ?></td></tr>
                        <tr><th>商品状態</th><td><?php echo $item['item_status']; ?></td></tr>
                        <tr><th>出品地域</th><td><?php echo $item['item_area']; ?></td></tr>
                      </table>
                    </div>
                  </div>
                  <!--item-Profile__More-Details-->
                </div>
                <!--item-Profile__description 関節クラス-->
                <div class="item_Profile__good">
                <p>イイね！<?php echo $item['item_good_count']; ?></p>
                </div>
              </div>
              <!--item-Profile-->
            </div>
          </div>
        </div>
        <div class="item-Detail__exhibitor-claim">
          <h4 class="Exhibitor-Name">出品者名：<a href="" class=""><?php echo $exhibitor_info['user_name']; ?></a></h4>
          <p><?php echo $item['item_detail']; ?></p>
        </div>
      </div>
    </div>

    <div class="item-Main-Wrapper__Comments-Area">
      <div class="item-Comments-Area u-border-free-auction">
        <div class="item-Comments-Area__list">
          <ul class="item-Comments-List">
              <form class="item-chat-form" action="" method="POST">
              <?php if (!(empty($comment_list_width_profile))): ?>
              <?php foreach($comment_list_width_profile as $comment_with_profile):?>
              <li class="item-Proposal u-clearfix"><!--関節クラス扱い-->
                  <div class="item-Proposal__person">
                      <a href="/Views/item/Public_Profile.php?user_id=<?php echo $comment_with_profile['user_id']; ?>">
                          <div class="item-person__img">
                              <img src="<?php echo $comment_with_profile['user_img']; ?>" alt="アイテム画像">
                              <!--擬似要素でたすき掛け-->
                          </div>
                          <div class="item-person__paragraph u-hideOverflow">
                              <h3 class="c-person-Name u-hideOverflow"><?php echo $comment_with_profile['message']; ?></h3>
                          </div>
                      </a>
                      <input type="hidden" name="post_switch" value="chat">
                      <input type="hidden" name="user_id" value="<?php echo $comment_with_profile['user_id']; ?>">
                      <div class="item-detail-Form-Button">
                        <?php if( sizeof($_SESSION) > 0): ?>
                          <?php if ($_SESSION['user_id'] === $item['exhibitor_id']):?>
                          
                            <!--出品者よう-->
                            <input type="hidden" name="user" value="exhibitor">
                            <input type="hidden" name="chat_board_id" value="<?php echo $comment_with_profile['chat_board_id']; ?>">
                            <input type="submit" value="やりとりする">
                          <?php endif;?>
                          <?php if ( $comment_with_profile['user_id'] == $_SESSION['user_id']): ?>
                            
                              <input type="hidden" name="user" value="user">
                              <input type="hidden" name="chat_board_id" value="<?php echo $comment_with_profile['chat_board_id']; ?>">
                              <?php if (!(empty($comment_with_profile['chat_board_id']))): ?>
                                <input type="submit" value="やりとりする">
                              <?php else: ?>
                                <p class="item-detail-Form-Button--isDisable">
                                 承諾待ち
                                </p>
                              <?php endif; ?>
                            
                          <?php endif;?>
                        <?php endif;?>
                      </div>
                  </div>
                  <div class="item-Proposal__comment">
                    <p class=""><?php echo $comment_with_profile['comment']; ?></p>
                  </div>
              </li>

              <?php endforeach;?>
              <?php endif;?>
              </form>
          </ul>
        </div>
        <!--item-Comments-Area__list-->

        <div class="item-Comments-Area__coment-form">
        <?php if ( !(empty($_SESSION['user_id'])) && ($item['exhibitor_id'] != $_SESSION['user_id'])): ?>
          <form action="" method="POST">
            <textarea class="u-Form-back__textarea--white" name="comment" id="" placeholder="例) こんにちわ"></textarea>
            <div class="c-Form-Button u-delete-Div-Margin-Left">
                <input type="hidden" name="post_switch" value="comment">
                <input type="submit" value="コメントする">
            </div>
            <?php echo display_Err_Msg_Span('comment');?>
          </form>
        <?php endif;?>
        </div>
        <!--item-Comments-Area__coment-form-->
      </div>
      <!--item-Comments-Area-->
    </div>

  </div>
</div>
<!--u-Container-Y--large-->
<?php
//フッター
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/footer.php");

?>
<script src="/js/Image_Live_Preview.js"></script>