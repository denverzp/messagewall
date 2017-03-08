define('comments',['jquery','base','posts'],function($, base, posts){
    
    return {
        /**
         * comments actons
         */
        bind: function(){
            var _this=this,o=base.option;
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
         * show new comment form
         */
        add_new_comment_form: function(post_id, parent_id, level){
            if(typeof(parent_id) == 'undefined' || parent_id == ''){ parent_id =0; }
            if(typeof(level) == 'undefined' || level == ''){ level = 0; }
            var o=base.option,action;
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
                        $(window).scrollTop($(o.wrap).offset().top);
                        posts.show_shadow();
                        $(o.wrap).find('#comment_body').focus();
                    } else {
                        posts.show_alerts(json['message'], 'alert-danger');
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
            var o=base.option,action, b, post_id, parent_id, level;
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
                            posts.show_alerts(o.txt_cs, 'alert-success');
                            posts.hide_form();
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
                                $(window).scrollTop($('#comment_'+json['message']).offset().top);
                            }
                            //update comments counter
                            if($(post_block).find(o.cm_count_b).length){
                                var c = Number( $(post_block).find(o.cm_count).text() , 10);
                                $(post_block).find(o.cm_count).text(c+1);
                                //if no counter - create it
                            } else {
                                var h = '&nbsp;|&nbsp;'
                                    +'<small class="' + o.cm_count_b.substring(1) + '">Comments: <span class="' + o.cm_count.substring(1) + '">1</span></small> '
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
                                posts.show_errors(elm, json['error'][i]);
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
                    err = o.txt_c_br;
                    posts.show_errors(elm, err);
                }
            }
        },
        /**
         * show edit comment form
         * @param id
         */
        add_edit_comment_form: function(id){
            var o=base.option,action;
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
                        posts.show_shadow();
                        $(o.wrap).find('#comment_body').focus();
                    } else {
                        posts.show_alerts(json['message'], 'alert-danger');
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
            var o=base.option,action, b, c;
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
                            posts.show_alerts(o.txt_ce, 'alert-success');
                            posts.hide_form();
                            $('#comment_'+c).find('.comment-text').html(b);
                        } else {
                            console.error(json['message']);
                            for(var i in json['error']){
                                if (!json['error'].hasOwnProperty(i)){ continue;}
                                var elm = $(o.wrap).find('.error_'+i);
                                posts.show_errors(elm, json['error'][i]);
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
                    err = o.txt_c_br;
                    posts.show_errors(elm, err);
                }
            }
        },
        /**
         * delete comment
         * @param $post_id
         * @param id
         */
        delete_comment: function($post_id, id){
            var o=base.option, action;
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
                        posts.show_alerts(o.txt_cd, 'alert-success');
                        $('#comment_'+id).remove();
                        var post_block = '#post_' + $post_id;
                        if($(post_block).find(o.cm_count_b).length){
                            var c = Number( $(post_block).find(o.cm_count).text() , 10);
                            if(c == 1){
                                $(post_block).find(o.cm_count_b).remove();
                                $(post_block).find('.comments_show').remove();
                            } else {
                                $(post_block).find(o.cm_count).text(c-1);
                            }
                        }
                    } else {
                        posts.show_alerts(json['message'], 'alert-danger');
                    }

                })
                .fail(function( jqXHR, textStatus){
                    console.error(textStatus);
                });
        }
    };
});
