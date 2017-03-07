<div class="post" id="post_<?php echo $post['id'] ?>">
	<p class="post-text"><?php echo $post['body'] ?></p>
	<div class="row">
		<div class="post-info col-9">
			<p class="text-left">
				<small><?php echo $post['username'] ?></small>
				&nbsp;|&nbsp;
				<small><?php echo $post['created_at'] ?></small>
			</p>
		</div>
		<div class="post-edit col-3">
			<div class="post-edit-buttons text-right">
				<?php if((int) $curr_user){ ?>
					<button class="comment_post btn btn-outline-success btn-sm" data-post-id="<?php echo $post['id'] ?>" data-parent-id="0" data-level="0" title="Comment post">
						<i class="fa fa-comment"></i>
					</button>
				<?php } ?>
				<?php if((int) $curr_user === (int) $post['user_id']){ ?>
					<button class="edit_post btn btn-outline-primary btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Edit post">
						<i class="fa fa-pencil-square-o"></i>
					</button>
					<button class="delete_post btn btn-outline-danger btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Delete post">
						<i class="fa fa-times"></i>
					</button>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row post-comments" id="comments_<?php echo $post['id'] ?>"></div>
</div>