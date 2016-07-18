	
	/**
	 * リアルタイム入力文字数カウント
	 * 
	 * @param  integer max_len 最大文字数
	 * @param  string target 文字数取得要素名(ID)
	 * @param  string field 結果出力場所の要素名(ID)
	 * @return void
	 * @author Kuniyasu Wada
	 */
	function checkLength(max_len, target, field)
	{
		$(field).text('');
		var target_len = $(target).val().length;
		
		// 有効範囲時も随時お知らせを表示する場合は以下を使用
		$(field).text('○ 登録可能です。');
		$(field).css('color','#62c5dd');
		
		if(target_len > max_len){
			var over = target_len - max_len;
			$(field).text('× ' + over + '文字超過しています。');
			$(field).css('color','#feb258');
		}
	}
	
	/**
	 * E-Mail Duplication Check on Ajax
	 * フォーム入力値を取得して、E-Mail重複チェックを行うAjaxを実行
	 *
	 * @param string target 文字数取得要素名(ID)
	 * @param string field 結果出力場所の要素名(ID)
	 * @param string method メソッド
	 * @param string type データタイプ
	 * @param string url Ajax送信先
	 * @return void
	 * @author Kuniyasu Wada
	 */
	function chkDuplicateEmail(target, field, method, type, url)
	{
		// フォーム内容取得
		var email = $(target).val();
		
		// 入力された値があるならマッチ開始
		if(email != "")
		{
			// 形式チェックを通ればAjax通信
			if(email.match(/^[A-Za-z0-9.]+[\w-]+@[\w\.-]+\.\w{2,}$/))
			{
				$(field).text('');
				var token = document.getElementsByName('_token')[0].value;
				
				$.ajax({
					type: method,
					url:  url,
					dataType: type,
					data: {email: email, _token: token},//json形式
					
					// 成功時のコールバック
					success: function (cnt)
					{
						if(cnt == 0){
							success();
							$(field).text('○ 登録可能です。');
							$(field).css('color','#62c5dd');
						} else {
							error();
							$(field).text('× 既に登録されています。');
							$(field).css('color','#feb258');
						}
					},
					error: function( data ) {
							console.log('通信エラー');
					}//,
					//complete: function( data ) {
					//	alert("complete!");
					//}
				});
			} else {
				warning();
				$(field).text('× E-Mail形式外');
				$(field).css('color','#feb258');
			}
		} else {
			remove();
			$(field).text('入力してください');
			$(field).css('color','#feb258');
		}
	}
	
	/**
	 * データの変更履歴チェック
	 * 
	 * @param string formId  フォームID
	 * @param string table テーブル名
	 * @param integer id   主キー
	 * @param integer version 世代番号
	 * @author Kuniyasu Wada
	 */
	function isModifiedData(formId, table, id, version)
	{
		var token = document.getElementsByName('_token')[0].value;
		
		$.ajax({
			type: "POST",
			url:  "/ajax/isModifiedData",
			dataType: 'JSON',
			data: {table: table, id: id, version: version, _token: token},//json形式
			
			// 成功時のコールバック
			success: function (response)
			{
				var form = document.getElementById(formId);
				
				// 更新履歴なし、又は続行選択でフォーム送信
				if(response === false
					|| true === confirm(response['updated_at'] + ' に ' + response['modified_user'] + ' によってデータが更新されていますが、続行しますか？'))
				{
					form.submit();
				}
			},
			error: function( response ) {
				console.log(response);
			}
		});
	}
	
	/*
	// video file size validation
	// programImage
	var programImage_max_size = $('#programImage').val();
	var programImage_area = $('#programImage_file').parent();
	
	$('#programImage_file').on('change', function()
	{
		$('#err_programImage').remove();//一旦消去
		if(this.files[0].size > programImage_max_size) {
			programImage_area.append('<p id="err_programImage" class="text-warning"><strong>* ファイルサイズ制限は' +  Math.round(programImage_max_size/1024/1024) + 'MBです</strong></p>')
			$('#submit').prop('disabled',true);
		} else {
			$('#submit').prop('disabled',false);
		}
	});
	
	// logoImage
	var logoImage_max_size = $('#logoImage').val();
	var logoImage_area = $('#logoImage_file').parent();
	
	$('#logoImage_file').on('change', function()
	{
		$('#err_logoImage').remove();//一旦消去
		if(this.files[0].size > logoImage_max_size) {
			logoImage_area.append('<p id="err_logoImage" class="text-warning"><strong>* ファイルサイズ制限は' +  Math.round(logoImage_max_size/1024/1024) + 'MBです</strong></p>')
			$('#submit').prop('disabled',true);
		} else {
			$('#submit').prop('disabled',false);
		}
	});
	
	// vodImage
	var vodImage_max_size = $('#vodImage').val();
	var vodImage_area = $('#vodImage_file').parent();
	
	$('#vodImage_file').on('change', function()
	{
		$('#err_vodImage').remove();//一旦消去
		if(this.files[0].size > vodImage_max_size) {
			vodImage_area.append('<p id="err_vodImage" class="text-warning"><strong>* ファイルサイズ制限は' +  Math.round(vodImage_max_size/1024/1024) + 'MBです</strong></p>')
			$('#submit').prop('disabled',true);
		} else {
			$('#submit').prop('disabled',false);
		}
	});
*/