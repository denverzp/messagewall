define('scroll',['jquery','base'],function($, base) {

    /**
     * Returns a function that will not fire, while continuing to be called.
     * It only works once after 'wait' = N milliseconds after last call.
     * If the passed argument 'immediate', it will be called once after the first run.
     * @param func
     * @param wait
     * @param immediate
     * @returns {Function}
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    
    return {
        /**
         * infinity scroll
         */
        bind: function(){
            var _this=this,o=base.option;
            $(o.pc).scroll(function () {
                var ph = Math.round(Number($(o.posts).outerHeight(), 10)),
                    h = Math.round(Number($(this).height(), 10)),
                    scroll = Math.round(Number($(this).scrollTop(), 10));
                //got bottom
                if(ph - scroll == h || ph - scroll == h + 1){
                    _this.is_loader('show');
                    _this.get_page();
                } else {
                    _this.is_loader('hide');
                }
            });
        },
        /**
         * load posts by page
         * @param page
         */
        get_page: debounce(function(page){
            var _this=this,o=base.option, action;
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'posts','action':'list','page': o.page + 1}
            });
            action
                .done(function(json){
                    if(json['html']!=''){
                        $(o.posts).append(json['html']);
                        o.page++;
                        $(o.posts).find(o.cmp_p).toggle(o.expand);
                    }
                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
            _this.is_loader('hide');
        }, 500),
        /**
         * show or hide loaders
         * @param action
         */
        is_loader:function(action){
            var o=base.option, elm = $(o.ld);
            if(action == 'show'){
                elm.css({'opacity':1,'visibility':'visible'});
            } else if(action == 'hide'){
                elm.css({'opacity':0,'visibility':'hidden'});
            }
        }
    };
});
