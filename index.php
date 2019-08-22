
<?php
  require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
  //require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
  require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

  //head
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
  
//アイテムをとってくる
$itme_list = null;
if (empty($_GET['sort-style'])) {
  $item_list = display_All_Items();

} else {
  $sort_style = $_GET['sort-style'];

  switch ($sort_style) {
      case 'new':
        $item_list = sort_New_Items();
        break;

      case 'good':
        $item_list = sort_Items_by_Good();
        break;

      case 'chat':
        $item_list = sort_Items_by_Chat();
        break;    
    }
}
  
?>

<div class="u-Container-Y--large">
  <div class="l-Main-Wrapper">
    <div class="l-Main-Wrapper__Side-Menu">
        <div class="l-Side-Menu">
          <form action="">
            <label for="">キーワード検索</label>
            <textarea class="u-Form-back__textarea--white" name="" id="" placeholder="例) レディース　パンツ　春物"></textarea>
            <div class="c-Form-Select u-has-Top-Margin20">
              <label class="c-Form-Select__label">カテゴリ</label>
              <div class="c-Form-Select__wrapper c-Form-Select__Arrow-Down u-delete-Div-Margin-Left-Top u-has-Top-Margin20">
                <select class="c-Form-Select-Down" name="" id="">
                    <option value="">ファッション</option><!--５列６行-->
                    <option value="">本</option><!--４列５行-->
                    <option value="">車</option><!--２列５行-->
                </select>
              </div>
            </div>
            <div class="c-Form-Button u-delete-Div-Margin-Left">
                <input type="submit" value="検索する">
            </div>
          </form>
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
                <div class="c-Form-Select">
                  <label>表示順の変更</label>
                  <div class="c-Form-Select__wrapper c-Form-Select__Arrow-Down">
                    <select class="c-Form-Select-Down" name="sort-style" id="">
                      <option value="new">投稿が新しい順</option>
                      <option value="good">盛り上がってる順</option>
                      <option value="chat">評判が高い順</option>
                    </select>
                  </div>
                </div>
              </div>
              <!--ペジネーション switch文で受け取る-->
              <!--
              <div class="c-Item-Function_pagenation">
                  <div class="c-Form-Select">
                    <label>表示件数の変更</label>
                    <div class="c-Form-Select__wrapper c-Form-Select__Arrow-Down">
                      <select class="c-Form-Select-Down" name="" id="">
                          <option value="">30件ずつ表示</option>
                          <option value="">20件ずつ表示</option>
                          <option value="">10件ずつ表示</option>
                      </select>
                    </div>
                  </div>
              </div>
              -->
              <!--ソートだけ。ペジネーションは保留-->
              <div class="top-Sort-Form-Button">
                <input type="submit" value="並び替える">
              </div>
            </div>

          </form>
          <!--c-Item-Function c-Form-Select-->

          <!--一覧表示--->
          <ul class="c-obj-List">            

              <?php  foreach($item_list as $item): ?>
              <li class="c-obj-List__obj-Thumbnail"><!--関節クラス扱い-->
                <div class="c-obj-Thumbnail">
                    <a href="/Views/item/Item-Detail.php?item_id=<?php echo $item['item_id']; ?>">
                        <div class="c-obj-Thumbnail__img">
                            <img src="<?php echo $item['item_img1']; ?>" alt="アイテム画像">
                            <!--擬似要素でたすき掛け-->
                        </div>
                        <div class="c-obj-Thumbnail__paragraph">
                            <h3 class="u-Text-Style5"><?php echo $item['item_title']; ?></h3>
                            <p class="u-Text-Style5 --hideOverflow"><?php echo $item['item_thumb']; ?></p>
                        </div>
                    </a>
                </div>
              </li>
              <?php
                endforeach;
              ?>

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
