    
    $(function(){
    //背景の判定 読み込み時点で
    var $img_src = $(".js-LivePre__img-src");
    var $img_wrapper = $(".js-LivePre__img-wrapper");
    //srcのうむで判定
    //console.log($img_src.attr('src'));
    if ($img_src.attr('src') != "") {
      $img_wrapper.css('background', 'none');
    }

    // 画像ライブプレビュー
    var $dropArea = $('.js-LivePre__img-drop-area');//area-drop
    var $fileInput = $('.js-LivePre__input-file');//input-file

    $dropArea.on('dragover', function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '3px #ccc dashed');
    });

    $dropArea.on('dragleave', function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });
    
    
    $fileInput.on('change', function(e){
      $dropArea.css('border', 'none');

     // 2. files配列にファイルが入っています
     var file = this.files[0];
      $img_src = $(".js-LivePre__img-src");//.siblings('.prev-img'), // 3. jQueryのsiblingsメソッドで兄弟のimgを取得
      $img_wrapper = $(".js-LivePre__img-wrapper");
      fileReader = new FileReader();   // 4. ファイルを読み込むFileReaderオブジェクト

      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img_src.attr('src', event.target.result).show();
        $img_wrapper.css('background', 'none');
      };

      // 6. 画像読み込み
      fileReader.readAsDataURL(file);

    });
    
/* 商品出品用
html構造が違うため
    $fileInput.on('change', function(e){
      $dropArea.css('border', 'none');
      console.log("test");
      console.log($(this).prev()); →これを使う
     // 2. files配列にファイルが入っています
      $img_src = $(".js-LivePre__img-src");//.siblings('.prev-img'), // 3. jQueryのsiblingsメソッドで兄弟のimgを取得
      $img_wrapper = $(".js-LivePre__img-wrapper");
      fileReader = new FileReader();   // 4. ファイルを読み込むFileReaderオブジェクト

      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img_src.attr('src', event.target.result).show();
        $img_wrapper.css('background', 'none');
      };

      // 6. 画像読み込み
      fileReader.readAsDataURL(file);

    });
*/
  });