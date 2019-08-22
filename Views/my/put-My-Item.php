
<?php
//入力分だけ更新する→これは今度


//共通変数・関数ファイルを読込み
require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
require($_SERVER['DOCUMENT_ROOT']."/Controller/Super_User_Controller.php");

//ユーザーの情報をとる
$user_info = select_By_User_Id($_SESSION['user_id']);

//編集やめたとき、サーバー上の画像を消す
/*
if ($_SESSION['data_list']['item_img'] != null) {
  unlink($_SESSION['data_list']['item_img']);
}
*/

//リクエスト受付
if (!(empty($_POST))) {

  //String 最終は画像のパスを入れたい. 現時点はファイル名
  $item_img1 = ($_FILES['item_img1']['name']); //? uploadImg($_FILES['item_img'],'item_img') : '';
  $item_img2 = ($_FILES['item_img2']['name']);
  $item_img3 = ($_FILES['item_img3']['name']);

  $item_title = $_POST["item_title"];
  $item_thumb = $_POST["item_thumb"];
  $item_detail = $_POST["item_detail"];
  $item_category = $_POST["item_category"];
  $item_status = $_POST['item_status'];
  $item_area = $_POST["item_area"];
  $exhibitor_id = $user_info["user_id"];
  

  if(empty($err_msg)) {
    
    //未入力チェック 残りはnullおk
    validRequired_for_Select($item_img1, "item_img1");
    validRequired($item_title, "item_title");
    validRequired($item_thumb, "item_thumb");
    validRequired($item_detail, "item_detail");
    validRequired_for_Select($item_category, "item_category");
    validRequired_for_Select($item_category, "item_status");
    validRequired_for_Select($item_area, "item_area");

    //テキスト入力だし、バリデーションいらんかな？

    if (empty($err_msg)) {
      //imageを保存
      $item_img1 = uploadImg($_FILES['item_img1'],'item_img1');
      if(!(empty($item_img2))) {
        $item_img2 = uploadImg($_FILES['item_img2'],'item_img2');
      }

      if (!(empty($item_img3))) {
        $item_img3 = uploadImg($_FILES['item_img3'], 'item_img3');
      }
    }

    if (empty($err_msg)) {
      
      //プロフィール更新
      $data_list_item = array(
        ':item_img1' => $item_img1,
        ':item_img2' => $item_img2,
        ':item_img3' => $item_img3,
        ':item_title' => $item_title,
        ':item_thumb' => $item_thumb,
        ':item_detail' => $item_detail,
        ':item_category' => $item_category,
        ':item_status' => $item_status,
        ':item_area' => $item_area,
        ":exhibitor_id" => $exhibitor_id
      );
      
      
      
      $_SESSION['data_list_item'] = $data_list_item;
      header("Location:/Views/my/confirm-put-My-Item.php"); //マイページへ
      exit;
      //$result = edit_Profile($user_info['user_id'], $data_list);
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

<div class="u-Container-Y--large">
  <div class="item-Main-Wrapper">
    <!--l-Side-Menu-->
    <div class="put-item-Wrapper-Start item-Main-Wrapper__Item-Detail">
      <!--一覧表示--->
      <div class="put-item-Detail">
        <form class="" action="" method="POST" enctype="multipart/form-data">
          <div class="item-Detail-wrapper u-clearfix">

            <div class="put-Item-Img-Wrapper">
              <div class="put-item-Img js-LivePre__img-drop-area">
                  <img class="js-LivePre__img-wrapper js-LivePre__img-src u-border-free-auction" src="" 
                  alt="
                  <?php if(empty(display_Err_Msg_Text('item_img1'))) {
                         echo 'アイテム画像';
                        } else { 
                          echo $err_msg['item_img1'];
                        } 
                  ?>">
                  <!--擬似要素でたすき掛け-->
                  <input class="put-item-File js-LivePre__input-file" type="file" name="item_img1">
              </div>

              <div class="put-item-Img js-LivePre__img-drop-area ">
                  <img class="js-LivePre__img-wrapper js-LivePre__img-src u-border-free-auction" src="" alt="アイテム画像">
                  <!--擬似要素でたすき掛け-->
                  <input class="put-item-File js-LivePre__input-file" type="file" name="item_img2">
              </div>
              
              <div class="put-item-Img js-LivePre__img-drop-area ">
                  <img class="js-LivePre__img-wrapper js-LivePre__img-src u-border-free-auction" src="" alt="アイテム画像">
                  <!--擬似要素でたすき掛け-->
                  <input class="put-item-File js-LivePre__input-file" type="file" name="item_img3">
              </div>              <!--
              <div class="put-item-Img">
                  <img class="u-border-free-auction" src="" alt="アイテム画像">
                  
              </div>
              <div class="put-item-Img">
                  <img class="u-border-free-auction" src="" alt="アイテム画像">
                 
              </div>
              -->
            </div>

            <div class="put-item-Detail__profile item-Detail__profile">
              <div class="put-item-Detail__Profile">
                <div class="item-Profile">
                  <label for="put-item-Title">
                    <h3 class="item-Profile__title">タイトル<?php display_Err_Msg_Span('item_title'); ?></h3>
                    <input class="put-item-Title__input u-border-free-auction" type="text" id="put-item-title" name="item_title" value="<?php echo $item_title;?>">
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
                          <p>サムネイルを記入してください<?php display_Err_Msg_Span('item_thumb'); ?></p>
                          <div class="put-item-More-Details__thumb">
                            <textarea class="put-item-More-Details__textarea u-border-free-auction" id="put-item-More-Details__textarea" placeholder="最大文字数は20です" name="item_thumb" ><?php echo $item_thumb;?></textarea>
                          </div>
                        </label>
                        <table class="put-item-More-Details__feature u-has-Top-Margin20">
                          <tr class="border">
                            <th>カテゴリ<?php display_Err_Msg_Span('item_category'); ?></th>
                            <!--カテゴリ配列をとってきループで回す-->
                            <td>
                              <div class="put-item-Form-Select">
                                <div class="put-item-Form-Select__Select-Down put-item-Form-Select__Arrow-Down">
                                  <select class="put-item-Select-Down" id="" name="item_category">
                                    <option value="">未選択</option>
                                    <option value="時計">時計</option>
                                    <option value="おもちゃ">おもちゃ</option>
                                    <option value="お菓子">お菓子</option> 
                                  </select>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th>商品状態<?php display_Err_Msg_Span('item_status'); ?></th>
                            <td>
                              <div class="put-item-Form-Select">
                                <div class="put-item-Form-Select__Select-Down put-item-Form-Select__Arrow-Down">
                                  <select class="put-item-Select-Down" name="item_status" id="">
                                    <option value="">未選択</option>
                                    <option value="未使用">未使用</option>
                                    <option value="美品">美品</option>
                                    <option value="訳あり">訳あり</option> 
                                  </select>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th>出品地域<?php display_Err_Msg_Span('item_area'); ?></th>
                            <td>
                              <div class="put-item-Form-Select">
                                <div class="put-item-Form-Select__Select-Down put-item-Form-Select__Arrow-Down">
                                  <select class="put-item-Select-Down" id="" name="item_area">
                                    <option value="">未選択</option>
                                    <option value="北海道">北海道</option>
                                    <option value="東京">東京</option>
                                    <option value="名古屋">名古屋</option>
                                    <option value="大阪">大阪</option> 
                                    <option value="山口">山口</option> 
                                    <option value="徳島">徳島</option> 
                                    <option value="福岡">福岡</option> 
                                    <option value="沖縄">沖縄</option> 
                                  </select>
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
                <h4 class="Exhibitor-Name">出品者名：<a href="" class=""><?php echo $user_info['user_name']; ?></a></h4>
                <label for="put-item-Comment">
                  <p>詳細を記入してください<?php display_Err_Msg_Span('item_detail'); ?></p>
                  <div class="put-item-More-Details__explain">
                    <textarea class="put-item-More-Details__textarea u-border-free-auction" id="put-item-Comment" placeholder="" name="item_detail"><?php echo $item_detail;?></textarea>
                  </div>
                </label>
              </div>
              <div class="c-Form-Button">
                  <input class="c-Form-Button_input" type="submit" value="出品内容の確認画面へ">
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