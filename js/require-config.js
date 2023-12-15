// require-config.js
requirejs.config({
    baseUrl: 'js',
    paths: {
        'jquery': 'jquery/jquery-3.2.1.min',
    },
    shim: {
        'jquery': {
            exports: '$'
        }
    }
});
