/**
 * jQuery datepicker
 */
(function($){
	// 一覧ページ
	$('#search_oa_start_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	$('#search_oa_end_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	
	// 登録・編集ページ
	// TX
	$('#tx_oa_start_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	$('#tx_oa_end_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	
	// GYAO!
	$('#gyao_oa_start_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	$('#gyao_oa_end_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	
	// ニコニコ
	$('#nico_oa_start_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	$('#nico_oa_end_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	
	// TVER
	$('#tver_oa_start_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	$('#tver_oa_end_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
})(jQuery);
	
/**
 * 配信先の公開ステータスがONの場合、API連動ステータスをOFFにするとアラート表示
 */
(function($){
	$('#output_to_tx').change(function(){
		if ( !($(this).is(':checked')) ) {
			var isChecked = $('#tx_provide_status_on').prop('checked');
			if(isChecked === true)
				alert('TX配信ステータスを非公開に設定して下さい。');
		}
	});
	$('#output_to_gyao').change(function(){
		if ( !($(this).is(':checked')) ) {
			var isChecked = $('#gyao_provide_status_on').prop('checked');
			if(isChecked === true)
				alert('GYAO!配信ステータスを非公開に設定して下さい。');
		}
	});
	$('#output_to_nico').change(function(){
		if ( !($(this).is(':checked')) ) {
			var isChecked = $('#nico_provide_status_on').prop('checked');
			if(isChecked === true)
				alert('ニコニコ動画配信ステータスを非公開に設定して下さい。');
		}
	});
	$('#output_to_tver').change(function(){
		if ( !($(this).is(':checked')) ) {
			var isChecked = $('#tver_provide_status_on').prop('checked');
			if(isChecked === true)
				alert('TVer配信ステータスを非公開に設定して下さい。');
		}
	});
})(jQuery);

/**
 * APIステータスによって配信先のタブを入力不可にする
 */
(function($){
	// TX オンロードイベント
	$(document).ready(function(){
		$('#tab-list1').addClass('active');// オンロード時は必ずアクティブ
		
		if ( !($('#output_to_tx').is(':checked')) ) {
			$('#tab-list1').addClass('disabled');// 切り替わった時に備えて付加しておく
			$('#tab1_submit').addClass('disabled');
			$('#tab1_submit').prop('disabled', true);
		}
	});
	// TX チェックボックスイベント
	$('#output_to_tx').change(function(){
		if ($(this).is(':checked')) {
			$('#tab-list1').removeClass('disabled');
			$('#tab1_submit').removeClass('disabled');
			$('#tab1_submit').prop('disabled', false);
		} else {
			$('#tab-list1').addClass('disabled');
			$('#tab1_submit').addClass('disabled');
			$('#tab1_submit').prop('disabled', true);
		}
	});
	// GYAO! オンロードイベント
	$(document).ready(function(){
		if ( !($('#output_to_gyao').is(':checked')) ) {
			$('#tab-list2').addClass('disabled');
			$('#tab2_submit').addClass('disabled');
			$('#tab2_submit').prop('disabled', true);
		}
	});
	// GYAO! チェックボックスイベント
	$('#output_to_gyao').change(function(){
		if ($(this).is(':checked')) {
			$('#tab-list2').removeClass('disabled');
			$('#tab2_submit').removeClass('disabled');
			$('#tab2_submit').prop('disabled', false);
		} else {
			$('#tab-list2').addClass('disabled');
			$('#tab2_submit').addClass('disabled');
			$('#tab2_submit').prop('disabled', true);
		}
	});
	// ニコニコ オンロードイベント
	$(document).ready(function(){
		if ( !($('#output_to_nico').is(':checked')) ) {
			$('#tab-list3').addClass('disabled');
			$('#tab3_submit').addClass('disabled');
			$('#tab3_submit').prop('disabled', true);
		}
	});
	// ニコニコ チェックボックスイベント
	$('#output_to_nico').change(function(){
		if ($(this).is(':checked')) {
			$('#tab-list3').removeClass('disabled');
			$('#tab3_submit').removeClass('disabled');
			$('#tab3_submit').prop('disabled', false);
		} else {
			$('#tab-list3').addClass('disabled');
			$('#tab3_submit').addClass('disabled');
			$('#tab3_submit').prop('disabled', true);
		}
	});
})(jQuery);

/*
 * 画像をモーダルで表示
 */
function chkExistsImage(id)
{
	var url  = document.getElementById(id).value;
	var name = document.getElementById(id + '_label').innerHTML;
	
	if(url == ""){
		var head_msg = '';
		var body_msg = 'URLを入力してください。';
	}
	else {
		var head_msg = '<p>' + name + '</p><p>' + url + '</p>';
		var body_msg = '<div class="text-center"><img src="' + url + '" data-dismiss="modal" alt="" width="" height="" /></div>';
	}
	
	document.getElementById('modal-header').innerHTML = head_msg;
	document.getElementById('modal-body').innerHTML = body_msg;
	
	/*
	// 以下、Ajaxでチェック
	if(url == "")
		alert('URLを入力してください。');
	//elseif('ここで正規表現'){}
		
	else {
		var token = document.getElementsByName('_token')[0].value;
		
		$.ajax({
			type: "POST",
			url:  "/ajax/chkExistsImage",
			dataType: 'JSON',
			data: {url: url, _token: token},//json形式
											
			// 成功時のコールバック
			success: function (response)
			{
				console.log(response);
				
				if(response !== false)
					console.log('存在');
				else
					alert('画像が存在しません。');
			},
			error: function( response ) {
				console.log(response);
				return false;
			}
		});
	}
	*/
}
