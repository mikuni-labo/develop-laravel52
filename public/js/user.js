// E-Mail Duplication Check on Ajax
(function($){
	$("#email").keyup(function(){
		chkDuplicateEmail('#email', '#email_judge', 'POST', 'JSON', '/ajax/chkDuplicateEmail');
	});
})(jQuery);

// E-Mail Duplication Check on Ajax
(function($){
	$("#email").focusout(function(){
		chkDuplicateEmail('#email', '#email_judge', 'POST', 'JSON', '/ajax/chkDuplicateEmail');
	});
})(jQuery);