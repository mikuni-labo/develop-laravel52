require('es6-promise').polyfill();
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	// LESS
	//mix.less('app.less').version('css/app.css');
	
	// SASS
	mix.sass('style.scss').version('css/style.css');
	//mix.sass('admin.style.scss').version('css/admin.style.css');
	
	// JS
//	mix.scripts([
//		'jquery-2.1.4.min.js'
//	],
//		'jquery.js'
//	)
//	mix.scripts([
//		'jquery.ui.core.min.js',
//		'jquery.ui.datepicker-ja.min.js',
//		'jquery.ui.datepicker.min.js',
//		'jquery.ui.ympicker.js',
//	],
//		'public/js/datepicker.js'//datepicker
//	)
//	.scripts([
//		'common.js',
//		'change.input.color.js',
//		'log.js',
//	],
//		'public/js/main.js'
//	);
	
});
