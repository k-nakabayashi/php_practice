<?php
/*
各フォーム値は個別にバリデーションかけるべきか？
今はもとめてる。

*/

//共通変数・関数ファイルを読込み
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

/*
$email = null;
$pass = null;
$pass_re = null;
*/
$form_flg_For_Log_Reg_Page = null;


//================================
// 画面処理
//================================
// post送信されていた場合
if(!( empty($_POST) )) {

  $form_flg_For_Log_Reg_Page = $_POST["Log_Reg_Type"];//フォーム送信内容の振り分け

  //パスワードリセット依頼の場合
  if ($form_flg_For_Log_Reg_Page === "2") {

      $email = $_POST['email'];
    
      //未入力チェック
      validRequired($email, 'email');



    //登録済みのメールアドレスか確認
    //$result = User_Model\User.searchById("users", $email);

    if (empty($result)) {
    //メールを送る
    //send_Reset_Mail();
    //header("Location:/freemarket/");
    //exit;
    }

  } else {

    //ログインの場合
    if ($form_flg_For_Log_Reg_Page === "1") {

      $email = $_POST['email'];
      $pass = $_POST['pass'];

      //未入力チェック
      validRequired($email, 'email');
      validRequired($pass, 'pass');

      if (empty($err_msg)) {
        //バリデーション
        validate_Email_Set ($email);
        validate_Pass_Set ($pass);
      
        if (empty($err_msg)) {
          //ログインする
          $result = login_User($email, $pass);//boolean
          if($result === true) {
            header("Location:/Views/my/Mypage.php");
            exit;
          } 
        }
      }
    }
    //新規登録の場合
    if ($form_flg_For_Log_Reg_Page === "3") {

      $email = $_POST['email'];
      $pass = $_POST['pass'];
      $pass_re = $_POST['pass_re'];

      //未入力チェック
      validRequired($email, 'email');

      /*
      if (empty($err_msg["email"])) {
        validate_Email_Set ($email);
      }
      */
      validRequired($pass, 'pass');
      validRequired($pass_re, 'pass_re');

      if (empty($err_msg)) {
 
        //バリデーション
        validate_Email_Set ($email);
        validate_Pass_Set ($pass);
        validate_Pass_Re_Set ($pass_re, $pass);


        if (empty($err_msg)) {
          //ユーザー登録する。内部でメール重複判定はしてる
          $stmt = create_User($email, $pass);//成功したらstmt
          /*
          $result_stmt = create_User($email, $pass);//成功したらstmt

          $stmt = $result_stmt_and_error["stmt"];
          $err_msg = $result_stmt_and_error["err_msg"];
          */
          if ($stmt && empty($err_msg)) {
          header("Location:/Views/my/Mypage.php"); //マイページへ
          exit;
          }
        }
      }
      /*
      //ユーザー登録する。内部でメール重複判定はしてる
      $result_stmt_and_error = create_User($email, $pass);//成功したらstmt

      $stmt = $result_stmt_and_error["stmt"];
      $err_msg = $result_stmt_and_error["err_msg"];
      
      if (empty($err_msg)) {
        header("Location:/Views/my/Mypage.php"); //マイページへ
        exit;
      }
      */

    }
  }
}




//DB 接続

//クエリ実行


//遷移


?>

<?php
  //head
  include($_SERVER['DOCUMENT_ROOT']."/views/component/head.php");
?>

<header class="l-Header">
    <div class="l-Header-Wrapper">
        <div class="l-Header-Wrapper__logo">
            <a href="">イメージロゴ</a>
        </div>
        <ul class="l-Header-Wrapper__list l-Header-List">
            <li><a href="/Views/item/Item-List.php" class="c-Arrow-Down">商品一覧</a></li>
            <li><a href="/" class="c-Arrow-Down">Top</a></li>
        </ul>
    </div>
</header>
<main>
  <div class="u-Container-X--large ">
    <div class="u-Container-Y-P--normal">

      <div class="login-reg-Form-Wrapper">
        <h1 class="u-Container-Y--short --centerText">いらっしゃいませ</h1>
        <?php
          if (!(empty($err_msg['common'] ))) {
            echo "<p class='Error-Msg'>{$err_msg['common']}</p>";
          }
        ?>
        
        <!--ログインか新規登録かswitchで判定必要-->
        <div class="login-reg-Form-Wrapper__Login">
          <div class="login-reg-Login u-Container-Y--short"><!--関節クラス-->
            <h2 class="login-reg-Login__text u-Container-Y--very-short">
              ログイン
            </h2>
            <?php
              if($form_flg_For_Log_Reg_Page === "1"){
                display_Err_Msg('email_pass');
              }
            ?>
            <form action="" method="POST">
              <label for=""><span class="u-Form-text">メールアドレス</span>
                <?php
                  if($form_flg_For_Log_Reg_Page === "1"){
                    display_Err_Msg('email');
                  }
                ?>
                <input class="u-Form-back" type="text" name="email" value="<?php if($form_flg_For_Log_Reg_Page === "1") { display_Form_Value("email");} ?>">
              </label>
              <label for=""><span class="u-Form-text">パスワード</span>
                <?php
                  if($form_flg_For_Log_Reg_Page === "1"){
                    display_Err_Msg('pass');
                  }
                ?>              
                <input class="u-Form-back" type="pass" name="pass">
              </label>
              <label for=""><span>次回ログインを省略する</span>
                <input type="checkbox" name="" id="">
              </label>
              <input type="hidden" name="Log_Reg_Type" value="1">
              <div class="c-Form-Button">
                  <input class="c-Form-Button_input" type="submit" value="ログイン">
              </div>
              <a href="/my/MyPage.html">マイページへ(仮)</a>
            </form>

            <div class="login-reg-Login__reset u-has-Top-Margin20">
              <p class="u-has-Top-Margin10">パスワードを忘れた方へ</p>
              <p class="u-Text-Explain u-has-Top-Margin10">ご指定のメールアドレス宛に<br>パスワード再発行用のURLと<br><span class="u-Text-Primary">認証キー</span>をお送り致します。</p>
              <form class="u-has-Top-Margin10" action="" method="POST">
                  <label for=""><span class="u-Form-text">メールアドレス</span>
                    <input class="u-Form-back" type="text" name="email" value="<?php if($form_flg_For_Log_Reg_Page === "2") { display_Form_Value("email");} ?>">
                  </label>

                  <!--成功時　/login/check-Re-Pwへ遷移-->
                  <input type="hidden" name="Log_Reg_Type" value="2">
                  <div class="c-Form-Button">
                      <input class="c-Form-Button_input" type="submit" value="送信する">
                  </div>
                  <a href="/login/check-Re-Pw.html" class="">PW再発行認証へ（仮）</a>
              </form>
            </div>
          </div>
        </div>
        
        <div class="login-reg-Form-Wrapper__Reg">
            <div class="login-reg-reg u-Container-Y--short"><!--関節クラス-->
              <h2 class="login-reg-reg__text u-Container-Y--very-short">
                新規会員登録
              </h2>

              <form action="" method="POST">
                <label for=""><span>メールアドレス</span>
                  <?php
                    if($form_flg_For_Log_Reg_Page === "3"){
                      display_Err_Msg('email');
                      display_Err_Msg('duplication');
                    }
                  ?>
                  <input class="u-Form-back" type="text" name="email" value="<?php if($form_flg_For_Log_Reg_Page === "3") {display_Form_Value("email"); }?>">
                
                </label>
                <label for=""><span>パスワード</span>
                  <?php
                    if($form_flg_For_Log_Reg_Page === "3"){
                        display_Err_Msg('pass');
                    }
                  ?>
                  <input class="u-Form-back" type="pass" name="pass" value="<?php if($form_flg_For_Log_Reg_Page === "3")  {display_Form_Value("pass"); }?>">
                  
                </label>
                <label for=""><span>パスワード(再入力)</span>
                  <?php
                    if($form_flg_For_Log_Reg_Page === "3") {
                      display_Err_Msg('pass_re');
                    }
                  ?>
                  <input class="u-Form-back" type="pass" name="pass_re">
                </label>
                <input type="hidden" name="Log_Reg_Type" value="3">
                <div class="c-Form-Button">
                    <input type="submit" value="新規登録">
                </div>
              </form>
            </div>
          </div>
      </div>
      <!--login-reg-Form-Wrapper-->
    </div>
    <!--u-Container-Y-P--normal-->
  </div>
  <!--U-Container-X--verylarge-->

<?php
//フッター
  include($_SERVER['DOCUMENT_ROOT']."/views/component/footer.php");

?>
