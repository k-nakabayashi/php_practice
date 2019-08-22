
<?php
//入力分だけ更新する→これは今度


//共通変数・関数ファイルを読込み
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

//ユーザーの情報をとる
$user_info = select_By_User_Id($_SESSION['user_id']);
$data_list_item = $_SESSION['data_list_item'];
//編集やめたとき、サーバー上の画像を消す
/*
if ($_SESSION['data_list']['item_img'] != null) {
  unlink($_SESSION['data_list']['item_img']);
}
*/

//直アクセスを禁止する
if ($_SESSION['data_list_item'] == null) {
  header("Location:/Views/my/MyProfile.php"); //マイページへ
  exit;
}


//リクエスト受付
if (!(empty($_POST))) {
  
  //前画面からのデータが取れてなかった場合
  if (empty($data_list_item)) {
    header("Location:/Views/my/MyProfile.php"); //マイページへ
    exit;
  }
  
  if ($user_info['user_id'] == $data_list_item[':exhibitor_id']) {
    $result = put_Item($data_list_item);
  }

  if ($result ==  true) {
    //header("Location:/Views/my/confirm-My-Profile.php"); //マイページへ
    $_SESSION['data_list_item'] = null;
    header("Location:/Views/my/MyPage.php"); //マイページへ
    exit;
  }
  $_SESSION['data_list_item'] = null;
}




//ソート

?>

<?php
  //html
  include($_SERVER['DOCUMENT_ROOT']."/views/component/head.php");
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/header_for_member.php");

?>

<div class="u-Container-Y--large">
  <div class="item-Main-Wrapper">
    <!--l-Side-Menu-->
    <div class="put-item-Wrapper-Start item-Main-Wrapper__Item-Detail">
      <!--一覧表示--->
      <div class="put-item-Detail">
      <form class="myprofile-Info__form" action="" method="POST" enctype="multipart/form-data">

          <div class="item-Detail-wrapper u-clearfix">

            <div class="put-Item-Img-Wrapper">
              <div class="put-item-Img js-LivePre__img-drop-area ">
                  <img class="js-LivePre__img-wrapper js-LivePre__img-src u-border-free-auction" src="<?php echo $data_list_item[':item_img1'];?>" alt="アイテム画像">
                  <!--擬似要素でたすき掛け-->
              </div>

              <div class="put-item-Img js-LivePre__img-drop-area ">
                  <img class="js-LivePre__img-wrapper js-LivePre__img-src u-border-free-auction" src="<?php echo $data_list_item[':item_img2'];?>" alt="アイテム画像">
                  <!--擬似要素でたすき掛け-->
              </div>
              
              <div class="put-item-Img js-LivePre__img-drop-area ">
                  <img class="js-LivePre__img-wrapper js-LivePre__img-src u-border-free-auction" src="<?php echo $data_list_item[':item_img3'];?>" alt="アイテム画像">
                  <!--擬似要素でたすき掛け-->
              </div>             
            </div>

            <div class="put-item-Detail__profile item-Detail__profile">
              <div class="put-item-Detail__Profile">
                <div class="item-Profile">
                  <label for="put-item-Title">
                    <h3 class="item-Profile__title">タイトル</h3>
                    <input class="put-item-Title__input u-border-free-auction" type="text" id="put-item-title" name="item_title" value="<?php echo $data_list_item[':item_title'];?>" readonly>
                  </label>

                  <div class="item-Profile__description u-has-Top-Margin15">
                    <div class="item-Profile__More-Details">
                      <div class="item-More-Details">
                        <!--
                        <p class="put-item-More-Details__explain">
                          恋人や旦那へのプレゼントとしてはどうでしょうか？
                        </p> 
                        -->
                        <label for="put-item-More-Details__textarea">
                          <p>サムネイル</p>
                          <div class="put-item-More-Details__thumb">
                            <textarea class="put-item-More-Details__textarea u-border-free-auction" id="put-item-More-Details__textarea" placeholder="最大文字数は20です" name="item_thumb" readonly><?php echo $data_list_item[':item_thumb'];?></textarea>
                          </div>
                        </label>
                        <table class="put-item-More-Details__feature u-has-Top-Margin20">
                          <tr class="border">
                            <th>カテゴリ</th>
                            <!--カテゴリ配列をとってきループで回す-->
                            <td>
                              <div class="put-item-Form-Select">
                                <div class="put-item-Form-Select__Select-Down put-item-Form-Select__Arrow-Down">
                                  <div class="put-item-Select-Down"><?php echo $data_list_item[':item_category'];?></div>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th>商品状態</th>
                            <td>
                              <div class="put-item-Form-Select">
                                <div class="put-item-Form-Select__Select-Down put-item-Form-Select__Arrow-Down">
                                  <div class="put-item-Select-Down"><?php echo $data_list_item[':item_status'];?></div>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th>出品地域</th>
                            <td>
                              <div class="put-item-Form-Select">
                                <div class="put-item-Form-Select__Select-Down put-item-Form-Select__Arrow-Down">
                                  <div class="put-item-Select-Down"><?php echo $data_list_item[':item_area'];?></div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <!--item-Profile__More-Details-->
                  </div>
                  <!--item-Profile__description 関節クラス-->
                </div>
                <!--item-Profile-->
              </div>

              <div class="put-item-Claim">
                <h4 class="Exhibitor-Name">出品者名：<a href="" class=""><?php echo $user_info['user_name'];?></a></h4>
                <label for="put-item-Comment">
                  <p>詳細</p>
                  <div class="put-item-More-Details__explain">
                    <textarea class="put-item-More-Details__textarea u-border-free-auction" id="put-item-Comment" placeholder="" name="item_detail" readonly><?php echo $data_list_item[':item_detail'];?></textarea>
                  </div>
                </label>
              </div>
              <div class="c-Form-Button">
                  <input class="c-Form-Button_input" type="submit" value="これで出品する">
              </div>
            </div>


          </div>
          <!--item-Detail-wrapper-->
      </form>
      </div>
    </div>



  </div>
</div>

<?php
//フッター
  include($_SERVER['DOCUMENT_ROOT']."/Views/component/footer.php");

?>
<script src="/js/Image_Live_Preview.js"></script>