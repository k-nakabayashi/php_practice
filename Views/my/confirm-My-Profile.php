
<?php
//入力分だけ更新する→これは今度


//共通変数・関数ファイルを読込み
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

//ユーザーの情報をとる
$user_info = select_By_User_Id($_SESSION['user_id']);
$data_list_user = $_SESSION['data_list_user'];

//直アクセスを禁止する
if ($_SESSION['data_list_user'] == null) {
  header("Location:/Views/my/MyProfile.php"); //マイページへ
  exit;
}



if (!(empty($_POST))) {

  //前画面からのデータが取れてなかった場合
  if (empty($data_list_user)) {
    header("Location:/Views/my/MyProfile.php"); //マイページへ
    exit;
  }
  $result = null;

  if ($user_info['user_id'] == $data_list_user[':user_id']) {
  $result = edit_Profile($user_info['user_id'], $data_list_user);
  }
  
  if ($result ==  true) {
    //header("Location:/Views/my/confirm-My-Profile.php"); //マイページへ
    $_SESSION['data_list_user'] = null;
    header("Location:/Views/my/MyProfile.php"); //マイページへ
    exit;
  }

}


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
                  <div class="My-Img">
                    <img class="My-Img__display js-LivePre__img-src" alt="" src="<?php echo $data_list_user[':user_img']; ?>">
                  </div>
                  <a href="/Views/my/edit-My-Profile.php">編集画面へ戻る</a>

                  <p class="u-has-Top-Margin45">変更前のニックネーム</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $user_info['user_name']; ?></p>
                  <p class="u-has-Top-Margin15">変更後のニックネーム</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $data_list_user[':user_name']; ?></p>

                  <p class="u-has-Top-Margin45">変更前の自己紹介</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $user_info['message']; ?></p>
                  <p class="u-has-Top-Margin15">変更後の自己紹介</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $data_list_user[':message']; ?></p>

                  <p class="u-has-Top-Margin45">変更前のメールアドレス</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $user_info['email']; ?></p>
                  <p class="u-has-Top-Margin15">変更後のメールアドレス</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $data_list_user[':email']; ?></p>

                  
                  <form action="" method="POST" class="u-has-Top-Margin45">
                    <div class="c-Form-Button">
                        <input type="submit" value="変更確定します">
                        <input type="hidden" name="post" value="post">
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
  </main>

<?php
//フッター
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/footer.php");

?>
<script src="/js/Image_Live_Preview.js"></script>