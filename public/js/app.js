;(function($, actions_data){
    /**
     *
     *
     */
    var wall = {
        /**
         * Options
         */
        option: {
            'url': actions_data.base_url + '?wall',
            //posts
            'shadow':'.shadow',
            'wrap':'#form',
            'spb':'#show_post_btn',
            'apb':'#add_post_btn',
            'epb':'#edit_post_btn',
            'cpb':'#cancel_post_btn',
            'apf':'#add_post',
            'posts': '.posts',
            'pc': '.posts-content',
            'ld': '.loader',
            'epbtn':'.edit_post',
            'dpbtn':'.delete_post',
            'alerts':'#alerts',
            'werr':'.wall-errors',
            'page': 1,
            //comments
            'cmp_b': '.comment_post',
            'cmp_cm': '.comment_comment',
            'cmp_c': '#cancel_comment_btn',
            'cmp_a': '#add_comment_btn',
            'cmp_eb': '#edit_comment_btn',
            'cmp_s': '.comments_show',
            'cmp_e': '.edit_comment',
            'cmp_d': '.delete_comment',
            //text fields
            'txt_ps' : actions_data.txt_post.success,
            'txt_pe' : actions_data.txt_post.edit,
            'txt_pd' : actions_data.txt_post.delete,
            'txt_cs' : actions_data.txt_comment.success,
            'txt_ce' : actions_data.txt_comment.edit,
            'txt_cd' : actions_data.txt_comment.delete
        },
        /**
         * Init
         */
        init: function(){
            var _this=this;
            _this.hide_shadow();
            _this.bind_posts();
            _this.bind_comments();
            _this.bind_scroll();
        },
        /**
         * post actions
         */
        bind_posts: function(){
            var _this=this,o=_this.option;
            //show new post form
            $(o.spb).on('click', function(){
                _this.add_new_post_form();
            });
            //hide form without save
            $(o.wrap).on('click', o.cpb + ',' + o.cmp_c, function(){
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
        /**
         * comments actons
         */
        bind_comments: function(){
            var _this=this,o=_this.option;
            //show new comment form
            $(o.posts).on('click', o.cmp_b + ',' + o.cmp_cm, function(){
                _this.add_new_comment_form($(this).data('post-id'), $(this).data('parent-id'), $(this).data('level'));
            });
            //publish new comment
            $(o.wrap).on('click', o.cmp_a, function(){
                _this.add_new_comment();
            });
            //toggle post comments
            $(o.posts).on('click', o.cmp_s, function(){
                $(this).closest('.post').find('.post-comments').toggle(400);
            });
            //show comment edit form
            $(o.posts).on('click', o.cmp_e, function(){
                _this.add_edit_comment_form($(this).data('comment-id'));
            });
            //edit comment
            $(o.wrap).on('click', o.cmp_eb, function(){
                _this.edit_comment();
            });
            //delete comment
            $(o.posts).on('click', o.cmp_d, function(){
                if(confirm('This action cannot be undone!\nContinue ?')){
                    _this.delete_comment($(this).data('comment-id'));
                }
            });
        },
        /**
         * infinity scroll
         */
        bind_scroll: function(){
            var _this=this,o=_this.option;
            $(o.pc).scroll(function () {
                var ph = $(o.posts).height(), h = $(this).height(), scroll = $(this).scrollTop();
                //got top
                if(ph - scroll == h){
                    _this.is_loader('show');
                    _this.get_page();
                } else {
                    _this.is_loader('hide');
                }
            });
        },
        //INFINITY SCROLL FUNCTONS
        /**
         * load posts by page
         * @param page
         */
        get_page: function(page){
            var _this=this,o=_this.option, action;
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
                    }
                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
            _this.is_loader('hide');
        },
        /**
         * show or hide loaders
         * @param action
         */
        is_loader:function(action){
            var _this=this,o=_this.option, elm = $(o.ld);
            if(action == 'show'){
                elm.css({'opacity':1,'visibility':'visible'});
            } else if(action == 'hide'){
                elm.css({'opacity':0,'visibility':'hidden'});
            }
        },
        //COMMENTS FUNCTIONS
        /**
         * show new comment form
         */
        add_new_comment_form: function(post_id, parent_id, level){
            if(typeof(parent_id) == 'undefined' || parent_id == ''){ parent_id =0; }
            if(typeof(level) == 'undefined' || level == ''){ level = 0; }
            var _this=this,o=_this.option,action;
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'comments','action':'create', 'post_id':post_id, 'parent_id':parent_id, 'level': level + 1 }
            });
            action
                .done(function(json){
                    if(json['status']){
                        $(o.wrap).css({'opacity':1,'visibility':'visible'}).html(json['html']);
                        $(window).scrollTop($(o.wrap).position().top);
                        _this.show_shadow();
                    } else {
                        _this.show_alerts(json['message'], 'alert-danger');
                    }
                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
            $('#post_'+post_id).find('.post-comments').toggle(true);
        },
        /**
         * publish new comment
         */
        add_new_comment: function(){
            var _this=this,o=_this.option,action, b, post_id, parent_id, level;
            b = $(o.wrap).find('#comment_body').val();
            post_id = $(o.wrap).find('input[name="post_id"]').val();
            parent_id = $(o.wrap).find('input[name="parent_id"]').val();
            level = $(o.wrap).find('input[name="level"]').val();
            if(b != '' && post_id != 0 && parent_id >= 0 && level > 0){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'comments','action':'store','body':b, 'post_id':post_id, 'parent_id':parent_id, 'level':level}
                });
                action
                    .done(function(json){
                        if(json['status']){
                            _this.show_alerts(o.txt_cs, 'alert-success');
                            _this.hide_form();
                            $('#comments_'+post_id).append(json['html']);
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
                if(b ==''){
                    elm = $(o.wrap).find('.error_body');
                    err = 'Post body is required!';
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * show edit comment form
         * @param id
         */
        add_edit_comment_form: function(id){
            var _this=this,o=_this.option,action;
            if(typeof(id) == 'undefined' || id ==''){
                console.error('cannot edit - not isset post id');
                return;
            }
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'comments','action':'edit','id':id}
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
        edit_comment: function () {
            var _this=this,o=_this.option,action, b, c;
            c = $(o.wrap).find('input[name="comment_id"]').val();
            b = $(o.wrap).find('#comment_body').val();
            if(c > 0  && b != ''){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'comments','action':'update','id':c,'body':b}
                });
                action
                    .done(function(json){
                        if(json['status']){
                            _this.show_alerts(o.txt_ce, 'alert-success');
                            _this.hide_form();
                            $('#comment_'+c).find('.comment-text').html(b);
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
                if(b ==''){
                    elm = $(o.wrap).find('.error_body');
                    err = 'Post body is required!';
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * delete comment
         * @param id
         */
        delete_comment: function(id){
            var _this=this,o=_this.option, action;
            if(typeof(id) == 'undefined' || id ==''){
                console.error('cannot delete - not isset post id');
                return;
            }
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'comments','action':'destroy','id':id}
            });
            action
                .done(function(json){
                    if(json['status']){
                        _this.show_alerts(o.txt_cd, 'alert-success');
                        $('#comment_'+id).remove();
                    } else {
                        _this.show_alerts(json['message'], 'alert-danger');
                    }

                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
        },
        //POSTS FUNCTIONS
        /**
         * reload posts after some action with posts
         */
        reload_posts_list: function(){
            var _this=this,o=_this.option, action;
            o.page = 1;
            action = $.ajax({
                url: o.url,
                dataType: 'json',
                type: 'post',
                data: {'type':'posts','action':'list', 'page': o.page}
            });
            action
                .done(function(json){
                    $(o.posts).html(json['html']);
                })
                .fail(function(jqXHR, textStatus){
                    console.error(textStatus);
                });
            $(o.pc).scrollTop(0);
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
            var _this=this,o=_this.option,action, b;
            b = $(o.wrap).find('#post_body').val();
            if(b != ''){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'posts','action':'store','body':b}
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
                if(b ==''){
                    elm = $(o.wrap).find('.error_body');
                    err = 'Post body is required!';
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * show edit post form
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
            var _this=this,o=_this.option,action, b, p;
            p = $(o.wrap).find('#post_id').val();
            b = $(o.wrap).find('#post_body').val();
            if(p > 0  && b != ''){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'posts','action':'update','post_id':p,'body':b}
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
         * hide form and destroy it
         */
        hide_form: function(){
            var _this=this,o=_this.option;
            $(o.wrap).css({'opacity':0,'visibility':'hidden'}).html('');
            _this.hide_shadow();
        },
        /**
         * show error for one field
         * @param elm
         * @param error
         */
        show_errors: function(elm, error){
            var _this=this,o=_this.option;
            elm.closest('.form-group').addClass('has-danger');
            elm.html('<div class="text-danger">'+ error +'</div>');
        },
        /**
         * hide all form errors
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
        /**
         * show alert messages
         * @param text
         * @param type
         */
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

    /**
     * Init script
     */
    $(document).ready(function(){

        if( window.location.search.indexOf('wall') != -1){
            wall.init();
        }

    });

})(jQuery, actions_data);


