/**
 * jQuery datepicker
 */
(function($){
	// jQuery datepicker
	$('#oa_start_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
	$('#oa_end_date').datepicker({
		dateFormat: 'yy-mm-dd',//年月日の並びを変更
	});
})(jQuery);

/**
 * Yahoo!カテゴリエリア Toggle
 */
(function($){
	$('.yahoo_category_area').css('display', 'none');
	
	// 製品検索エリアのToggleEvent
	$('#yahoo_category_toggle').click(function()
	{
		$('.yahoo_category_area').toggle();
		
		if($('.yahoo_category_area').css('display') == 'none')
			$('#yahoo_category_toggle').text('検索エリアを表示する');
		else
			$('#yahoo_category_toggle').text('検索エリアを隠す');
	});
})(jQuery);

/**
 * リアルタイム入力文字数表示
 */
(function($){
	// 番組名
	$("#program_title").keyup(function(){
		checkLength(100, '#program_title', '#program_title_length');
	});
	
	// 番組名 (カナ)
	$("#program_title_kana").keyup(function(){
		checkLength(200, '#program_title_kana', '#program_title_kana_length');
	});
	
	// 番組公式サイトURL
	$("#program_official_url").keyup(function(){
		checkLength(100, '#program_official_url', '#program_official_url_length');
	});
	
	// 番組キャッチ
	$("#program_catch").keyup(function(){
		checkLength(100, '#program_catch', '#program_catch_length');
	});
	
	// 番組詳細
	$("#program_overview").keyup(function(){
		checkLength(150, '#program_overview', '#program_overview_length');
	});
})(jQuery);
