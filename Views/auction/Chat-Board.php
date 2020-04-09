<?php
//sessionをとりたい
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

//html
include($_SERVER['DOCUMENT_ROOT']."/views/component/head.php");
include($_SERVER['DOCUMENT_ROOT']."/Views/component/header_for_member.php");

//データ取得
//他のページで使いたくないため消したい。いつ消す？せんでいいかも。他のチャット掲示板もみれてるし。
$item = $_SESSION['item'];
//$_SESSION['item'] = null;
$chat_board_id = $_SESSION['chat_board_id'];
//$_SESSION['chat_board_id'] = null;

//希望者のコメントとプロフィル取得
$Profile_and_Comment = display_Applicant_Profile($chat_board_id);//item_idをchat_boardsからとってきて、ほんでitemsから詳細をとってくる

//出品者情報
$exhibitor_info = display_Exhibitor_Name($item['exhibitor_id']);

//出品者か希望者か判定
$cliant_flg = null;
if ($_SESSION['user_id'] == $item['exhibitor_id']) {
  $cliant_flg = true;//出品者なら
} else {
  $cliant_flg = false;//希望者なら
}

//チャットコメントのinsert
if (!(empty($_POST))) {
  $chat_comment = $_POST['chat_comment'];
  validRequired($chat_comment, 'chat_comment');

  if (empty($err_msg)) {

    if ($cliant_flg == true) {

      //クライアントが出品者の場合
      $data_list = array(
        ':chat_board_id' => $chat_board_id,
        ':chat_comment' => $chat_comment,
        ':applicant_exhibitor_flg' => 1
      );
      create_Chat_Comment ($data_list); //第２引数はapplicant_exhibitor_flgというカラムに付与させるため。デフォは0でapplicant用

    } else {
      //クライアントが希望者の場合
      $data_list = array(
        ':chat_board_id' => $chat_board_id,
        ':chat_comment' => $chat_comment,
        ':applicant_exhibitor_flg' => 0
      );
      create_Chat_Comment ($data_list);//
    }

    unset($data_list);
  }
  // $_SESSION['item'] = $item;
  // $_SESSION['chat_board_id'] = $chat_board_id;
}
$data_list[':chat_board_id'] = $chat_board_id;
$chat_comments = diplay_Chat_Comments($data_list);

/*
//やりとりを開始する
if (!(empty($_POST))) {
  $data_list = array(
    ':item_id' => $_POST['item_id'],
    'applicant_id',
    'exhibitor_id'
  );

  create_Chat_Comments_by_Applicant();
  create_Chat_Comments_by_Exhibitor();
  
}
*/
?>


<div class="u-Container-Y--large">
  <div class="chat-Main-Wrapper">
    <!--l-Side-Menu-->
    <div class="chat-Main-Wrapper__Item-Detail">
      <!--一覧表示--->
      <div class="item-Detail">
      <a href="/Views/item/Item-Detail.php?item_id=<?php echo $item['item_id'] ?>">商品詳細へ</a>

        <div class="item-Detail-wrapper u-clearfix">
          <div class="item-Detail__img --removeFloat">
              <img src="<?php echo $item['item_img1']; ?>" alt="アイテム画像">
              <!--擬似要素でたすき掛け-->
          </div>
          <div class="item-Detail__profile --removeFloat">
            <div class="item-Detail__Profile">
              <div class="item-Profile">
                <h3 class="item-Profile__title"><?php echo $item['item_title']; ?></h3>

                <div class="item-Profile__description u-has-Top-Margin10">
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
              </div>
              <!--item-Profile-->
            </div>
          </div>
        </div>
        <div class="item-Detail__exhibitor-claim">
          <h4 class="Exhibitor-Name">出品者名：<a href="" class=""><?php echo $exhibitor_info['user_name']; ?></a></h4>
          <p>
          <?php echo $item['item_detail']; ?>
          </p>
        </div>
      </div>
    </div>

    <div class="chat-Main-Wrapper__Chat-Board">
      <div class="chat-Chat-Board">
        <div class="chat-Chat-Board__pair">

          <div class="chat-Person-Img-Name">
              <a href="/item/Public_Profile.html">
                  <div class="chat-person-img">
                      <img src="<?php echo $Profile_and_Comment['user_img']; ?>" alt="アイテム画像">
                      <!--擬似要素でたすき掛け-->
                  </div>
                  <div class="item-person-paragraph u-hideOverflow">
                      <h3 class="c-person-Name u-hideOverflow"><?php echo $Profile_and_Comment['user_name']; ?></h3>
                  </div>
              </a>
          </div>
          <div class="chat-Person-Comment">
            <p><?php echo $Profile_and_Comment['comment']; ?></p>
          </div>
        </div>
      </div>

      <div class="item-Comments-Area">
        <div class="item-Comments-Area__list">
          <ul class="item-Comments-List">

              <!--
              <li class="chat u-clearfix">
                  <div class="chat__comment--floatRight">
                    <p class="">aaaaaaa</p>
                  </div>
              </li>
              <li class="chat u-clearfix">
                <div class="chat__comment--floatLeft">
                  <p class="">aaaaaaa</p>
                </div>
              </li>
              -->

              <?php if (!(empty($chat_comments))):?>

                <?php if ($cliant_flg == 0): ?>
                  <?php foreach ($chat_comments as $chat_comment): ?>
                          <?php if ($chat_comment['applicant_exhibitor_flg'] == 1): ?>
                            <li class="chat u-clearfix"><!--関節クラス扱い-->
                              <div class="chat__comment--floatLeft">
                                <p class=""><?php echo $chat_comment['chat_comment'] ?></p>
                              </div>
                            </li>
                          <?php endif;?>

                          <?php if ($chat_comment['applicant_exhibitor_flg'] == 0): ?>
                            <li class="chat u-clearfix"><!--関節クラス扱い-->
                              <div class="chat__comment--floatRight">
                                <p class=""><?php echo $chat_comment['chat_comment'] ?></p>
                              </div>
                            </li>
                          <?php endif;?>
                    <?php endforeach; ?>
                <?php endif;?>
                <!--$cliant_flg -->

                <?php if ($cliant_flg == 1): ?>
                  <?php foreach ($chat_comments as $chat_comment): ?>
                          <?php if ($chat_comment['applicant_exhibitor_flg'] == 0): ?>
                            <li class="chat u-clearfix"><!--関節クラス扱い-->
                              <div class="chat__comment--floatLeft">
                                <p class=""><?php echo $chat_comment['chat_comment'] ?></p>
                              </div>
                            </li>
                          <?php endif;?>

                          <?php if ($chat_comment['applicant_exhibitor_flg'] == 1): ?>
                            <li class="chat u-clearfix"><!--関節クラス扱い-->
                              <div class="chat__comment--floatRight">
                                <p class=""><?php echo $chat_comment['chat_comment'] ?></p>
                              </div>
                            </li>
                          <?php endif;?>
                    <?php endforeach; ?>
                <?php endif;?>
                <!--$cliant_flg -->

              <?php endif;?>

          </ul>
        </div>
        <!--item-Comments-Area__list-->

        <div class="item-Comments-Area__coment-form">
          <form action="" method="POST">
            <textarea class="u-Form-back__textarea--white" name="chat_comment" id="" placeholder="例) こんにちわ"></textarea>
            <div class="c-Form-Button u-delete-Div-Margin-Left">
              <input type="submit" value="コメントする" >
            </div>
            <?php echo display_Err_Msg_Span('chat_comment');?>
          </form>
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