
<?php
  //共通変数・関数ファイルを読込み
  require($_SERVER['DOCUMENT_ROOT'].'/Utility/common.php');
  require($_SERVER['DOCUMENT_ROOT'].'/Utility/auth.php');
  require($_SERVER['DOCUMENT_ROOT']."/Controller/Login_User_Controller.php");

  //ユーザーの情報をとる
  $use_info = select_By_User_Id($_SESSION['user_id']);
  
  //リクエスト受付

  //ソート

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
            <p class="mypqge-profle__name">やくみつる<span>さん</span></p>
          </div>
          <ul class="mypage-Link-List">
            <li class="u-has-Top-Margin20" ><a href="/Views/my/MyProfile.php">マイプロフィール</a></li>
            <li class="u-has-Top-Margin20" ><a href="/Views/my/edit-Profile.php">プロフィール編集</a></li>
            <li class="u-has-Top-Margin20" ><a href="/Views/my/put-My-Item.php">出品</a></li>
          </ul>
        </div>
    </div>
        <!--l-Side-Menu-->
    <div class="l-Main-Wrapper__Main-Menu">
        <div class="l-Main-Menu c-Form-Select-Parent"><!--幅と余白は確定-->

          <!--機能　ソートとペジネーション-->
          <div class="c-Item-Function c-Form-Select">

              <!---ソート-->
            <div class="c-Item-Function__srort">              
              <div class="c-Form-Drop c-Form-Select">
                <label>どれを検索ますか？</label>
                <div class="c-Form-Drop__select c-Form-Select__wrapper u-has-No-Left-Margin c-Form-Select__Arrow-Down">
                  <select class="c-Form-Select-Down" name="" id="">
                    <option value="">欲しいモノ</option>
                    <option value="">やりとり中</option>
                    <option value="">出品中</option>
                  </select>
                </div>
              </div>
            </div>
            <!--ペジネーション switch文で受け取る-->
            <div class="c-Item-Function_pagenation">
                <div class="c-Form-Drop  c-Form-Select">
                  <label>表示件数の変更</label>
                  <div class="c-Form-Drop__select c-Form-Select__wrapper u-has-No-Left-Margin c-Form-Select__Arrow-Down">
                    <select class="c-Form-Select-Down" name="" id="">
                        <option value="">30件ずつ表示</option><!--５列６行-->
                        <option value="">20件ずつ表示</option><!--４列５行-->
                        <option value="">10件ずつ表示</option><!--２列５行-->
                    </select>
                  </div>
                </div>
            </div>

          </div>

          <!--一覧表示--->
          <ul class="c-obj-List">
            <li class="mypage-item-List__obj-Thumbnail c-obj-List__obj-Thumbnail"><!--関節クラス扱い-->
              <div class="c-obj-Thumbnail">
                <div class="c-obj-Thumbnail__img">
                    <img src="/images/watch.jpg" alt="アイテム画像">
                    <!--擬似要素でたすき掛け-->
                </div>
                <div class="mypage-item-Thumbnail__paragraph c-obj-Thumbnail__paragraph">
                  <h3 class="u-Text-Style5">タイトル</h3>
                </div>
                <ul class="c-obj-Thumbnail__link-list">
                  <li><a href="/item/Item-Detail.html">商品詳細へ</a></li>
                  <li><a href="/my/edit-My-Item.html">出品内容詳細へ</a></li>
                  <li><a href="/auction/Chat-Board.html">やりとり掲示板へ</a></li>
                </ul>
              </div>
            </li>            

          </ul>


        </div>
        <!--l-Main-Menu-->
    </div>
  </div>
</div>
<!--u-Container-Y--large-->
</main>
<footer class="l-Footer">
    <div class="l-Footer-container">
        <div class="l-Footer-Wrapper">
            <div class="l-Footer-Wrapper__profile">
                <ul class="l-Footer-Profile__list">
                    <li><a href="#"></a></li>
                    <li><p class="c-Text-Style">iYell株式会社</p></li>
                    <li><p class="c-Text-Style5">〒150-0043</p></li>
                    <li><p class="c-Text-Style5">東京都渋谷区道玄坂2-16-8 道玄坂坂本ビル7階</p></li>
                    <li><p class="c-Text-Style10">Tel: 03-6455-1005 (代表) </p></li>
                    <li><p class="c-Text-Style5">Fax: 03-6455-1004</p></li>
                </ul>
                <p class="l-Footer-Profile__link">
                    <a href="#">
                        採用サイト
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <a href="https://ja-jp.facebook.com/iYell.corp/" target="_blank"><img class="c-Facebook-Link" src="https://iyell.co.jp/wp-content/themes/iyell-corp/img/sns_01.png" alt=""></a>
                    <a href="https://twitter.com/iyell_happyell" target="_blank"><img class="c-Twitter-Link" src="https://iyell.co.jp/wp-content/themes/iyell-corp/img/sns_03.png" alt=""></a>
                </p>
            </div>
            <div class="l-Footer-Wrapper__links">
                <ul class="l-Footer-Map">
                    <li class="l-Footer-Map__text"><a href="">ホーム</a></li>
                    <li class="l-Footer-Map__text c-Text-Style10"><a href="">経営方針</a></li>
                    <li class="l-Footer-Map__list">
                        <ul>
                            <li class="c-Text-Style5"><a href="">経営理念</a></li>
                            <li class="c-Text-Style5"><a href="">代表者メッセージ</a></li>
                            <li class="c-Text-Style5"><a href="">ビジョン・ミッション</a></li>
                            <li class="c-Text-Style5"><a href="">バリュー・行動規範</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="l-Footer-Map">
                    <li class="l-Footer-Map__text"><a href="">プラットフォーム</a></li>
                    <li class="l-Footer-Map__text c-Text-Style10"><a href="">サービス</a></li>
                    <li class="l-Footer-Map__list">
                        <ul>
                            <li class="c-Text-Style5"><a href="">住宅事業者向けローンテック</a></li>
                            <li class="c-Text-Style5"><a href="">住宅ローンの窓口</a></li>
                            <li class="c-Text-Style5"><a href="">金融期間向け住宅ローンテック</a></li>
                            <li class="c-Text-Style5"><a href="">住宅ローンの窓口ONLINE</a></li>
                            <li class="c-Text-Style5"><a href="">いえーるコンシェル</a></li>
                            <li class="c-Text-Style5"><a href="">いえーる コンシェル 不動産投資版</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="l-Footer-Map">
                    <li class="l-Footer-Map__text"><a href="">メンバー</a></li>
                    <li class="l-Footer-Map__text c-Text-Style10"><a href="">アソビゴコロ</a></li>
                    <li class="l-Footer-Map__list">
                        <ul>
                            <li class="c-Text-Style5"><a href="">オフィシャルキャラクター</a></li>
                            <li class="c-Text-Style5"><a href="">スポーツ観戦</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="l-Footer-Map">
                    <li class="l-Footer-Map__text"><a href="">会社情報</a></li>
                    <li class="l-Footer-Map__list">
                        <ul>
                            <li class="c-Text-Style5"><a href="">会社概要</a></li>
                            <li class="c-Text-Style5"><a href="">個人情報保護方針</a></li>
                            <li class="c-Text-Style5"><a href="">お問い合わせ</a></li>
                        </ul>
                    </li>
                </ul>
            </div>            
        </div>
        <p class="l-Footer-Copyright">Copyright © 2018 The iYell Co,. Ltd All Rights Reserved.</p>
</footer>

</body>
</html>