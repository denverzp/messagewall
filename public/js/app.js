;(function($){
    /**
     *
     *
     */
    var wall = {
        /**
         *
         */
        option: {
            'shadow':'.shadow',
            'wrap':'#form',
            'spb':'#show_post_btn',
            'apb':'#add_post_btn',
            'cpb':'#cancel_post_btn',
            'apf':'#add_post',
        },
        /**
         *
         */
        init: function(){
            var _this=this;
            _this.hide_shadow();
            _this.bind();
        },
        /**
         *
         */
        bind: function(){
            var _this=this,o=_this.option;
            //show form add new post
            $(o.spb).on('click', function(){
                $(o.wrap).css({'opacity':1,'visibility':'visible'});
                _this.show_shadow();
            });
            //hide form without save
            $(o.cpb).on('click', function(){
                $(o.wrap).css({'opacity':0,'visibility':'hidden'});
                _this.hide_shadow();
            });
            //publish new post
            $()
            //show post edit form
            //edit post
        },
        /**
         * hide background shadow
         */
        hide_shadow: function(){
            var _this=this,o=_this.option;
            $(o.shadow).css({'opacity':0,'visibility':'hidden'});
        },
        /**
         * show background shadow
         */
        show_shadow: function(){
            var _this=this,o=_this.option;
            $(o.shadow).css({'opacity':1,'visibility':'visible'});
        },
    };

    $(document).ready(function(){

        if( window.location.search.indexOf('wall') != -1){
            wall.init();
        }

    });

})(jQuery);


