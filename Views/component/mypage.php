<main class="l-Main-Wrapper U-Container-X--verylarge">

  <div class="l-main-Wrapper__Side-Menu">
    <div class="l-Side-Menu">
      <form action="">
        <input type="text" placeholder="キーワードを入力してください" class="u-Text-Style15">
        <select name="" id="" class="u-Text-Style15">
          <option value="">ファッション</option>
          <option value="">本</option>
          <option value="">車</option>
          <div class="u-Form-Button">
              <input type="submit" value="検索する">
          </div>
        </select>
      </form>
    </div>
    <!--l-Side-Menu-->
  </div>
  <!---main-Wrapper__Side-Menu-->


  <div class="l-Main-Wrapper__Main-Menu">
    <div class="l-Main-Menu">

      <!--機能　ソートとペジネーション-->
      <div class="Item-Function">
          <!---ソート-->
          <div class="Item-Function__srort">
              <label for="">表示順の変更</label>
              <form action="" method="POST">
                  <select name="" id="">
                      <option value="">投稿が新しい順</option>
                      <option value="">盛り上がってる順</option>
                      <option value="">評判が高い順</option>
                  </select>
              </form>
          </div>
          <!--ペジネーション switch文で受け取る-->
          <div class="Item-Function_pagenation">
              <label for="">表示件数の変更</label>
              <form action="" method="GET">
                  <select name="" id="">
                      <option value="">30件</option><!--５列６行-->
                      <option value="">20件</option><!--４列５行-->
                      <option value="">10件</option><!--２列５行-->
                  </select>
              </form>
          </div>
      </div>
      <!--一覧表示--->
      <div class="Item-Diplay">
          <ul class="Item-Display__list">
              <li class="Item-Diplay__Item-Thumbnail">
                  <div class="u-Item-Thumbnail">
                      <a href="">
                          <div class="u-Item-Thumbnail__img">
                              <img src="" alt="アイテム画像">
                              <!--擬似要素でたすき掛け-->
                          </div>
                          <div class="u-Item-Thumbnail__paragraph">
                              <h3 class="u-Text-Style5">タイトル</h3>
                              <p class="u-Text-Style5 --hideOverflow">サムネイルサムネイル</p>
                          </div>
                      </a>
                  </div>
              </li>
          </ul>
      </div>
    </div>
    <!--l-Main-Menu-->
  </div>
  <!--l-Main-Wrapper__Main-Menu-->
  
</main>