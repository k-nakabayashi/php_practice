
<?php
//入力分だけ更新する→これは今度


//共通変数・関数ファイルを読込み
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
//require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

//ユーザーの情報をとる
$user_info = select_By_User_Id($_GET['user_id']);

//ソート

?>

<?php
  //html
  include($_SERVER['DOCUMENT_ROOT']."/views/component/head.php");

  if (empty($_SESSION)) {
    include($_SERVER['DOCUMENT_ROOT']."/Views/component/header.php");
  } else {
    include($_SERVER['DOCUMENT_ROOT']."/Views/component/header_for_member.php");

  }

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
               
                  
                  <div class="My-Img">
                    <img class="My-Img__display js-LivePre__img-src" alt="" src="<?php echo $user_info['user_img'];?>">
                   
                  </div>
                  <p class="u-has-Top-Margin45">自己紹介</p>
                  <p class="u-text--has-Padding-Left15"><?php echo $user_info['message']; ?></p>
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