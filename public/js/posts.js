define('posts',['jquery','base'],function($, base){

    return {
        /**
         * post actions
         */
        bind: function(){
            var _this=this, o = base.option;

            //some default action
            _this.hide_shadow();
            $(o.posts).find(o.cmp_p).toggle(o.expand);
            
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
         * show new post form
         */
        add_new_post_form: function(){
            var _this=this,o=base.option,action;
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
                        $(o.wrap).find('#post_body').focus();
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
            var _this=this,o=base.option,action, b;
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
                    err = o.txt_p_br;
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * show edit post form
         * @param id
         */
        add_edit_post_form: function(id){
            var _this=this,o=base.option,action;
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
                        $(o.wrap).find('#post_body').focus();
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
            var _this=this,o=base.option,action, b, id;
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
                    err = o.txt_p_br;
                    _this.show_errors(elm, err);
                }
            }
        },
        /**
         * delete post
         * @param id
         */
        delete_post: function(id){
            var _this=this,o=base.option, action;
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
            var _this=this,o=base.option;
            $(o.wrap).css({'opacity':0,'visibility':'hidden'}).html('');
            _this.hide_shadow();
        },
        /**
         * show error for one field
         * @param elm
         * @param error
         */
        show_errors: function(elm, error){
            elm.closest('.form-group').addClass('has-danger');
            elm.html('<div class="text-danger">'+ error +'</div>');
        },
        /**
         * hide all form errors
         */
        hide_errors: function(){
            var o=base.option;
            $('.has-danger').removeClass('has-danger');
            $(o.wrap).find('div[class^="error_"]').html('');
        },
        /**
         * hide background shadow
         */
        hide_shadow: function(){
            var o=base.option;
            $(o.shadow).css({'opacity':0,'visibility':'hidden'});
        },
        /**
         * show background shadow
         */
        show_shadow: function(){
            var o=base.option;
            $(o.shadow).css({'opacity':1,'visibility':'visible'});
        },
        /**
         * show alert messages
         * @param text
         * @param type
         */
        show_alerts: function(text, type){
            var o=base.option;
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

});
