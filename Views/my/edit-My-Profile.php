
<?php
//入力分だけ更新する→これは今度
//unlinkを使う
//ただし、ページイベントを扱う
//戻る、閉じる、違うページにいった場合


//共通変数・関数ファイルを読込み
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

//ユーザーの情報をとる
$user_info = select_By_User_Id($_SESSION['user_id']);

//編集やめたとき、サーバー上の画像を消す
/*
if ($_SESSION['data_list_user']['user_img'] != null) {
  unlink($_SESSION['data_list_user']['user_img']);
}
*/

//リクエスト受付
if (!(empty($_POST))) {
  
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  $new_pass = $_POST['new_pass'];
  $old_pass = $_POST['old_pass'];
  $pass = null;//データ更新用

  //画像処理
  //$user_img_file = $_FILES['user_img'];
  //$user_img_name = $_FILES['user_img']['name'];

  //String 最終は画像のパスを入れたい. 現時点はファイル名
  $user_img = ($_FILES['user_img']['name']); //? uploadImg($_FILES['user_img'],'user_img') : '';

  //パスワードがあうのが、更新の前提条件
  validRequired($old_pass, 'old_pass');
  //$pass_check = password_verify($pass, array_shift($result));//boolean

  if(empty($err_msg)) {
    
    //現在のパスワード確認
    $pass_check = password_verify($old_pass, $user_info['pass']);//boolean

    if ($pass_check != true) {
      global $err_msg;
      $err_msg['old_pass'] = MSG12;
    }
    
    if (empty($err_msg)) {

      //未入力チェック 未入力なら以前の値を入れる
      if(empty($user_img)) {
        $user_img = $user_info['user_img'];
      } else {

        //画像保存　成功すると、パスの保存先が返り値
       $user_img = uploadImg($_FILES['user_img'],'user_img');
      }

      if(empty($user_name)) {
        $user_name = $user_info['user_name'];
      }

      if(empty($email)) {
        $email = $user_info['email'];
      }

      if(empty($message)) {
        $message = $user_info['message'];
      }

      if(empty($new_pass)) {
        $new_pass = $old_pass;
      }

    }

    //バリデーション　名前だけ注意。全角OKだから
    if (empty($err_msg)) {

      validate_Email_Set ($email);
      validate_New_Pass_Set ($new_pass);
      $pass = $new_pass;
    }

    if (empty($err_msg)) {
      

      //プロフィール更新
      $data_list_user = array(
        ':user_id' => $user_info['user_id'],
        ':user_img' => $user_img,
        ':user_name' => $user_name,
        ':email' => $email,
        ':pass' => password_hash($pass, PASSWORD_DEFAULT),
        ':message' => $message
      );
      
      
      
      $_SESSION['data_list_user'] = $data_list_user;
      header("Location:/Views/my/confirm-My-Profile.php"); //マイページへ
      exit;
      //$result = edit_Profile($user_info['user_id'], $data_list_user);
      /*
      if ($result ==  true) {
        header("Location:/Views/my/confirm-My-Profile.php"); //マイページへ
        //header("Location:/Views/my/MyProfile.php"); //マイページへ
        exit;
      }
      */


    }
  }
}




//ソート

?>

<?php
  //html
  include($_SERVER['DOCUMENT_ROOT']."/views/component/head.php");
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/header_for_member.php");

?>
    <div class="u-Container-X--veryshort">
      <div class="u-Container-Y-P--normal">
  
        <div class="myprofile-Form-Wrapper login-reg-Form-Wrapper">
          <h1 class="u-Container-Y--short --centerText">プロフィール</h1>
          <div class="myprofile-Form-Wrapper__Info login-reg-Form-Wrapper__Reg">
              <div class="myprofile-Info u-Container-Y--short"><!--関節クラス-->
                <h2 class="myprofile-Info__text u-Container-Y--very-short">
                  <span class="u-text-child-short">お名前</span>：<br class="u-sp-only"><span>
                  <?php 
                     if (!(empty($user_info['user_name']))) {
                        echo $user_info['user_name'];
                     }                   
                    ?>
                  </span></p>
                </h2>
                <form class="myprofile-Info__form" action="" method="POST" enctype="multipart/form-data">

                  <label for="upload_user_img"　class="">
                      <div class="My-Img-LivePre js-LivePre__img-drop-area">
                        <div class="My-Img js-LivePre__img-wrapper">
                          <img class="My-Img__display js-LivePre__img-src" alt="" src="<?php echo $user_info['user_img'];?>">
                        </div>
                        <p class="My-Img-LivePre__note"> プロフィール画像更新</p>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">

                        <input class="My-Img-LivePre__input-file js-LivePre__input-file" type="file" id="upload_user_img" name="user_img">
                      
                      </div>
                  </label>

                  <label for="new_user_name"><span>新ニックネーム</span>
                    <?php
                      display_Err_Msg('user_name');
                    ?>
                    <input class="u-Form-back--large" type="text" id="new_user_name" name="user_name">
                  </label>

                  <p class="u-has-Top-Margin45">現在の自己紹介</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $user_info['message']; ?></p>
                  <?php
                      display_Err_Msg('message');
                    ?>
                  <label class="u-has-Bottom-Margin60" for="new_email"><span>新しい自己紹介</span>
                    <input class="u-Form-back--large" type="eamil" id="new_email" name="message">
                  </label>

                  <p class="u-has-Top-Margin45">現在のメールアドレス</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $user_info['email']; ?></p>
                  <?php
                      display_Err_Msg('email');
                    ?>
                  <label class="u-has-Bottom-Margin60" for="new_email"><span>新メールアドレス</span>
                    <input class="u-Form-back--large" type="eamil" id="new_email" name="email">
                  </label>
                  
                  <label for="current_pass"><span class="u-has-Top-Margin30">現在のパスワード</span>
                    <?php
                      display_Err_Msg('old_pass');
                    ?>
                    <input class="u-Form-back--large" type="password" placeholder="現在のパスを入力してください" id="current_pass" name="old_pass">
                  </label>
                  <label for="new_pass"><span>新パスワード</span>
                  <?php
                      display_Err_Msg('new_pass');
                    ?>
                    <input class="u-Form-back--large" type="password" id="new_pass" name="new_pass">
                  </label>

                  <div class="c-Form-Button">
                      <input type="submit" value="確認画面へ">
                  </div>
                </form>
                <a href="/my/confirm-Profile.html">確認画面へ(仮)</a>
              </div>
            </div>
        </div>
        <!--login-reg-Form-Wrapper-->
      </div>
      <!--u-Container-Y-P--normal-->
    </div>
    <!--U-Container-X--verylarge-->
  </main>
  <?php
//フッター
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/footer.php");

?>
<script src="/js/Image_Live_Preview.js"></script>