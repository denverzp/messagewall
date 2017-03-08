define('base',function() {
    return  {
        option: {
            'url': actions_data.base_url + '?wall',

            //posts
            'shadow': '.shadow',
            'wrap': '#form',
            'spb': '#show_post_btn',
            'apb': '#add_post_btn',
            'epb': '#edit_post_btn',
            'cpb': '#cancel_post_btn',
            'apf': '#add_post',
            'posts': '.posts',
            'pc': '.posts-content',
            'ld': '.loader',
            'epbtn': '.edit_post',
            'dpbtn': '.delete_post',
            'alerts': '#alerts',
            'werr': '.wall-errors',
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
            'cm_count_b': '.comments-count-block',

            //text fields
            'txt_ps': actions_data.txt_post.success,
            'txt_pe': actions_data.txt_post.edit,
            'txt_pd': actions_data.txt_post.delete,
            'txt_cs': actions_data.txt_comment.success,
            'txt_ce': actions_data.txt_comment.edit,
            'txt_cd': actions_data.txt_comment.delete,
            'txt_p_br' : 'Post body is required!',
            'txt_c_br' : 'Comment body is required!'
        }
    };
});
