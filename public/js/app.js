require.config({
    baseUrl: location.protocol + '//' + location.hostname + '/js/',
    paths: {
        'base': 'base',
        'posts': 'posts',
        'comments': 'comments',
        'scroll': 'scroll',
        'jquery': 'jquery-3.1.1.min'

    }
});

require(['jquery','posts','comments','scroll'],function($, posts, comments, scroll){

    $(document).ready(function(){

        if( window.location.search.indexOf('wall') != -1){
            posts.bind();
            comments.bind();
            scroll.bind();
        }

    });
});