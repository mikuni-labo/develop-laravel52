require('es6-promise').polyfill();
var elixir = require('laravel-elixir');
elixir.config.sourcemaps = false;

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
    // SASS
    mix.sass([
            'style.scss',
        ],
        'public/css/style.css'
    );
    
    // SASS
    mix.sass([
              'toggle.scss',
          ],
          'public/css/toggle.css'
      );
    
    // Version
    mix.version([
        'css/style.css',
    ]);
});
