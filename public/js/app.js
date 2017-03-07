;(function($, actions_data){
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
            'cmp_p': '.post-comments',
            'cmp_ex': '#expand_all_comment',
            'cmp_cl': '#collapse_all_comment',
            'expand': false,
            'cmp_b': '.comment_post',
            'cmp_cm': '.comment_comment',
            'cmp_c': '#cancel_comment_btn',
            'cmp_a': '#add_comment_btn',
            'cmp_eb': '#edit_comment_btn',
            'cmp_s': '.comments_show',
            'cmp_e': '.edit_comment',
            'cmp_d': '.delete_comment',
            'cm_count': '.comments-count',
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
            var _this=this,o=_this.option;
            _this.hide_shadow();
            _this.bind_posts();
            _this.bind_comments();
            _this.bind_scroll();
            $(o.posts).find(o.cmp_p).toggle(o.expand);
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
                $(this).closest('.post').find(o.cmp_p).toggle(400);
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
                    _this.delete_comment($(this).data('post-id'), $(this).data('comment-id'));
                }
            });
            //expand all comments
            $('#buttons').on('click', o.cmp_ex, function(){
                o.expand = true;
                $(o.posts).find(o.cmp_p).toggle(o.expand);
                $(this).find('.fa').removeClass('fa-expand').addClass('fa-compress');
                $(this).find('span').text('Collapse all comments');
                $(this).removeClass('btn-outline-primary').addClass('btn-outline-warning');
                $(this).attr('id', o.cmp_cl.substring(1));
            });
            //collapse all comments
            $('#buttons').on('click', o.cmp_cl, function(){
                o.expand = false;
                $(o.posts).find(o.cmp_p).toggle(o.expand);
                $(this).find('.fa').removeClass('fa-compress').addClass('fa-expand');
                $(this).find('span').text('Expand all comments');
                $(this).removeClass('btn-outline-warning').addClass('btn-outline-primary');
                $(this).attr('id', o.cmp_ex.substring(1));
            });
        },
        /**
         * infinity scroll
         */
        bind_scroll: function(){
            var _this=this,o=_this.option;
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
        //INFINITY SCROLL FUNCTONS
        /**
         * load posts by page
         * @param page
         */
        get_page: debounce(function(page){
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
            $('#post_'+post_id).find(o.cmp_p).toggle(true);
        },
        /**
         * publish new comment
         */
        add_new_comment: function(){
            var _this=this,o=_this.option,action, b, post_id, parent_id, level;
            b = $(o.wrap).find('#comment_body').val();
            post_id = $(o.wrap).find('input[name="post_id"]').val();
            parent_id = $(o.wrap).find('input[name="parent_id"]').val();
            level = Number($(o.wrap).find('input[name="level"]').val(), 10);
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
                            var post_block = '#post_' + post_id;
                            //if level 1 comment
                            if(parent_id==0){
                                $('#comments_'+post_id).append(json['html']);
                            //if lower level comments
                            } else {
                                var parent = $('#comment_' + parent_id),
                                    child = parent.nextUntil('.comment-level-1'),
                                    cl = child.length;
                                //if parent comment has children - search last on this level
                                if(cl){
                                    var last = parent;
                                    child.each(function(){
                                        var curr_level = Number($(this).find('.comment_comment').data('level') , 10);
                                        if(curr_level > (level - 1)){
                                            last = $(this);
                                        } else {
                                            return false;
                                        }
                                    });
                                    last.after(json['html']);
                                //if no children
                                } else {
                                    parent.after(json['html']);
                                }
                                $(window).scrollTop($('#comment_'+json['message']).position().top);
                            }
                            //update comments counter
                            if($(post_block).find('.comments-count-block').length){
                                var c = Number( $(post_block).find(o.cm_count).text() , 10);
                                $(post_block).find(o.cm_count).text(c+1);
                            //if no counter - create it
                            } else {
                                var h = '&nbsp;|&nbsp;'
                                    +'<small class="comments-count-block">Comments: <span class="comments-count">1</span></small> '
                                    +' <button class="comments_show btn btn-outline-info btn-sm" data-post-id="' + post_id + '" title="Show comments">'
                                    +'<i class="fa fa-comments"></i>'
                                    +'</button>';
                                $(post_block).find('.created').after(h);
                            }
                        } else {
                            //return form validation error
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
                    err = 'Comment body is required!';
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
         * @param $post_id
         * @param id
         */
        delete_comment: function($post_id, id){
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
                        var post_block = '#post_' + $post_id;
                        if($(post_block).find('.comments-count-block').length){
                            var c = Number( $(post_block).find(o.cm_count).text() , 10);
                            if(c == 1){
                                $(post_block).find('.comments-count-block').remove();
                                $(post_block).find('.comments_show').remove();
                            } else {
                                $(post_block).find(o.cm_count).text(c-1);
                            }
                        }
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
                            $(o.posts).prepend(json['html']);
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
            var _this=this,o=_this.option,action, b, id;
            id = $(o.wrap).find('#post_id').val();
            b = $(o.wrap).find('#post_body').val();
            if(id > 0  && b != ''){
                action = $.ajax({
                    url: o.url,
                    dataType: 'json',
                    type: 'post',
                    data: {'type':'posts','action':'update','post_id':id,'body':b}
                });
                action
                    .done(function(json){
                        if(json['status']){
                            _this.show_alerts(o.txt_pe, 'alert-success');
                            _this.hide_form();
                            $('#post_'+id).find('.post-text').html(b);
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
                        $('#post_'+id).remove();
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


