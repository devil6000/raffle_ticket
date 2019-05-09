/**
 * Created by appleimac on 19/5/9.
 */
require.config({
    urlArgs: 'v=201905090001',
    baseUrl: '__STATIC__/js/',
    paths: {
        jquery: 'jquery-3.3.1.min.js',
        bootstrap: 'bootstrap-4.0.0-dist/js/bootstrap.min.js'
    },
    //用来配置不兼容模块
    shim: {
        bootstrap: {
            exports: '$',
            deps: ['jquery']
        }
    }
});