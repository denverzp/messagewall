<div class="post-comment comment-level-<?php echo $comment['level'] ?>" id="comment_<?php echo $comment['id'] ?>">
	<p class="comment-text"><?php echo $comment['body'] ?></p>
	<div class="row">
		<div class="post-comment-info col-6">
			<p class="text-left">
				<small><?php echo $comment['username'] ?></small>
				&nbsp;|&nbsp;
				<small><?php echo $comment['created_at'] ?></small>
			</p>
		</div>
		<div class="post-comment-edit col-6">
			<div class="post-comment-edit-buttons text-right">
				<?php if((int) $curr_user){ ?>
					<button class="comment_comment btn btn-outline-success btn-sm" data-post-id="<?php echo $comment['post_id'] ?>" data-parent-id="<?php echo $comment['id'] ?>" data-level="<?php echo $comment['level'] ?>" title="Comment">
						<i class="fa fa-comment"></i>
					</button>
				<?php } ?>
				<?php if((int) $curr_user === (int) $comment['user_id']){ ?>
					<button class="edit_comment btn btn-outline-primary btn-sm" data-comment-id="<?php echo $comment['id'] ?>" title="Edit comment">
						<i class="fa fa-pencil-square-o"></i>
					</button>
					<button class="delete_comment btn btn-outline-danger btn-sm" data-comment-id="<?php echo $comment['id'] ?>" data-post-id="<?php echo $comment['post_id'] ?>" title="Delete comment">
						<i class="fa fa-times"></i>
					</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>