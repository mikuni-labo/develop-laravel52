//(function($){// 即時関数にする場合
	
	function success()
	{
		// status
		$('#email_response').removeClass('has-warning');
		$('#email_response').removeClass('has-error');
		$('#email_response').addClass('has-success');
	
		// icon
		$('#email-inputicon').removeClass('glyphicon-remove');
		$('#email-inputicon').removeClass('glyphicon-warning-sign');
		$('#email-inputicon').addClass('glyphicon-ok');
	}
	
	function warning()
	{
		// status
		$('#email_response').removeClass('has-success');
		$('#email_response').removeClass('has-error');
		$('#email_response').addClass('has-warning');
		
		// icon
		$('#email-inputicon').removeClass('glyphicon-remove');
		$('#email-inputicon').removeClass('glyphicon-ok');
		$('#email-inputicon').addClass('glyphicon-warning-sign');
	}
	
	function error()
	{
		// status
		$('#email_response').removeClass('has-success');
		$('#email_response').removeClass('has-warning');
		$('#email_response').addClass('has-error');
		
		// icon
		$('#email-inputicon').removeClass('glyphicon-ok');
		$('#email-inputicon').removeClass('glyphicon-warning-sign');
		$('#email-inputicon').addClass('glyphicon-remove');
	}
	
	function remove()
	{
		// status
		$('#email_response').removeClass('has-success');
		$('#email_response').removeClass('has-warning');
		$('#email_response').removeClass('has-error');
		
		// icon
		$('#email-inputicon').removeClass('glyphicon-ok');
		$('#email-inputicon').removeClass('glyphicon-warning-sign');
		$('#email-inputicon').removeClass('glyphicon-remove');
	}
	
//})(jQuery);
