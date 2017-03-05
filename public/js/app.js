;(function($, actions_data){
    /**
     *
     *
     */
    var wall = {
        /**
         *
         */
        option: {
            'url': actions_data.base_url + '?wall',
            'shadow':'.shadow',
            'wrap':'#form',
            'spb':'#show_post_btn',
            'apb':'#add_post_btn',
            'epb':'#edit_post_btn',
            'cpb':'#cancel_post_btn',
            'apf':'#add_post',
            'posts': '.posts',
            'epbtn':'.edit_post',
            'dpbtn':'.delete_post',
            'alerts':'#alerts',
            'werr':'.wall-errors',
            'txt_ps' : actions_data.txt_post.success,
            'txt_pe' : actions_data.txt_post.edit,
            'txt_pd' : actions_data.txt_post.delete
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
                _this.add_new_post_form();
            });
            //hide form without save
            $(o.wrap).on('click', o.cpb, function(){
                _this.hide_form()
            });
            //publish new post
            $(o.wrap).on('click', o.apb, function(){
                _this.add_new_post();
            });
            //show post edit form
            $(o.posts).on('click', o.epbtn, function(){
                _this.add_edit_post_form($(this).data('post-id'));
            });
            //edit post
            $(o.wrap).on('click', o.epb, function(){
                _this.edit_post();
            });
            //delete post
            $(o.posts).on('click', o.dpbtn, function(){
                if(confirm('This action cannot be undone!\nContinue ?')){
                    _this.delete_post($(this).data('post-id'));
                }
            });
            //hide errors
            $(o.wrap).on('focus','input, textarea', function(){
                _this.hide_errors();
            });
            //close form on click by shadow
            $(o.shadow).on('click', function(){
                _this.hide_form();
            });
            //hide error message
            $(o.wrap).on('click', o.werr + ' .close', function(){
                _this.hide_form();
            });
        },
        //POSTS
        reload_posts_list: function(){
            var _this=this,o=_this.option, action, page= 1, cp = Number($.cookie('post_page'), 10);
            if(cp != 0 && cp != null){
                page = cp;
            }
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'posts','action':'list', 'page': page}
            });
            action
                .done(function(json){
                    $(o.posts).html(json['html']);
                })
                .fail(function(jqXHR, textStatus){
                    console.error(textStatus);
                });
        },
        /**
         * show new post form
         */
        add_new_post_form: function(){
            var _this=this,o=_this.option,action;
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'posts','action':'create'}
            });
            action
                .done(function(json){
                    if(json['status']){
                        $(o.wrap).css({'opacity':1,'visibility':'visible'}).html(json['html']);
                        _this.show_shadow();
                    } else {
                        _this.show_alerts(json['message'], 'alert-danger');
                    }
                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
        },
        /**
         * publish new post
         */
        add_new_post: function(){
            var _this=this,o=_this.option,action, t, b;
            t = $(o.wrap).find('#post_title').val();
            b = $(o.wrap).find('#post_body').val();
            if(t !='' && b != ''){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'posts','action':'store','title':t,'body':b}
                });
                action
                    .done(function(json){
                        if(json['status']){
                            _this.show_alerts(o.txt_ps, 'alert-success');
                            _this.hide_form();
                            _this.reload_posts_list();
                        } else {
                            console.error(json['message']);
                            for(var i in json['error']){
                                if (!json['error'].hasOwnProperty(i)){ continue;}
                                var elm = $(o.wrap).find('.error_'+i);
                                _this.show_errors(elm, json['error'][i]);
                            }
                        }
                    })
                    .fail(function( jqXHR, textStatus){
                        console.error(textStatus);
                    });
            } else {
                var elm, err;
                if(t ==''){
                    elm = $(o.wrap).find('.error_title');
                    err = 'Title is required!';
                    _this.show_errors(elm, err);
                }
                if(b ==''){
                    elm = $(o.wrap).find('.error_body');
                    err = 'Post body is required!';
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * show edit form
         * @param id
         */
        add_edit_post_form: function(id){
            var _this=this,o=_this.option,action;
            if(typeof(id) == 'undefined' || id ==''){
                console.error('cannot edit - not isset post id');
                return;
            }
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'posts','action':'edit','id':id}
            });
            action
                .done(function(json){
                    if(json['status']){
                        $(o.wrap).css({'opacity':1,'visibility':'visible'}).html(json['html']);
                        _this.show_shadow();
                    } else {
                        _this.show_alerts(json['message'], 'alert-danger');
                    }
                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
        },
        /**
         * edit post
         */
        edit_post: function () {
            var _this=this,o=_this.option,action, t, b, p;
            p = $(o.wrap).find('#post_id').val();
            t = $(o.wrap).find('#post_title').val();
            b = $(o.wrap).find('#post_body').val();
            if(p > 0  && t !='' && b != ''){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'posts','action':'update','post_id':p,'title':t,'body':b}
                });
                action
                    .done(function(json){
                        if(json['status']){
                            _this.show_alerts(o.txt_pe, 'alert-success');
                            _this.hide_form();
                            _this.reload_posts_list();
                        } else {
                            console.error(json['message']);
                            for(var i in json['error']){
                                if (!json['error'].hasOwnProperty(i)){ continue;}
                                var elm = $(o.wrap).find('.error_'+i);
                                _this.show_errors(elm, json['error'][i]);
                            }
                        }
                    })
                    .fail(function( jqXHR, textStatus){
                        console.error(textStatus);
                    });
            } else {
                var elm, err;
                if(t ==''){
                    elm = $(o.wrap).find('.error_title');
                    err = 'Title is required!';
                    _this.show_errors(elm, err);
                }
                if(b ==''){
                    elm = $(o.wrap).find('.error_body');
                    err = 'Post body is required!';
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * delete post
         * @param id
         */
        delete_post: function(id){
            var _this=this,o=_this.option, action;
            if(typeof(id) == 'undefined' || id ==''){
                console.error('cannot delete - not isset post id');
                return;
            }
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'posts','action':'destroy','id':id}
            });
            action
                .done(function(json){
                    if(json['status']){
                        _this.show_alerts(o.txt_pd, 'alert-success');
                        _this.reload_posts_list();
                    } else {
                        _this.show_alerts(json['message'], 'alert-danger');
                    }

                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
        },
        /**
         *
         */
        hide_form: function(){
            var _this=this,o=_this.option;
            $(o.wrap).css({'opacity':0,'visibility':'hidden'}).html('');
            _this.hide_shadow();
        },
        /**
         *
         * @param elm
         * @param error
         */
        show_errors: function(elm, error){
            var _this=this,o=_this.option;
            elm.closest('.form-group').addClass('has-danger');
            elm.html('<div class="text-danger">'+ error +'</div>');
        },
        /**
         *
         */
        hide_errors: function(){
            var _this=this,o=_this.option;
            $('.has-danger').removeClass('has-danger');
            $(o.wrap).find('div[class^="error_"]').html('');
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
        show_alerts: function(text, type){
            var _this=this,o=_this.option;
            if(typeof(text) == 'undefined' || text == ''){
                return;
            }
            if(typeof(type) == 'undefined' || type == ''){
                type = 'alert-danger';
            }
            $(o.alerts).html(text).addClass('alert '+ type).css({'opacity':1,'visibility':'visible'});
            setTimeout(function(){
                $(o.alerts).css({'opacity':0,'visibility':'hidden'}).html('').removeClass();
            }, 4000);
        }
    };

    $(document).ready(function(){

        if( window.location.search.indexOf('wall') != -1){
            wall.init();
        }

    });

})(jQuery, actions_data);


