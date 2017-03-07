<?php if(0 !== count($posts)){ ?>
	<?php foreach($posts as $post){ ?>
		<div class="post" id="post_<?php echo $post['id'] ?>">
			<p class="post-text"><?php echo $post['body'] ?></p>
			<div class="row">
				<div class="post-info col-9">
					<p class="text-left">
						<small class="username"><?php echo $post['username'] ?></small>
						&nbsp;|&nbsp;
						<small class="created"><?php echo $post['created_at'] ?></small>
						<?php if(true === array_key_exists($post['id'], $comments)){ ?>
							<?php $comments_count = count($comments[$post['id']]) ?>
							<?php if($comments_count > 0){ ?>
						&nbsp;|&nbsp;
						<small class="comments-count-block">Comments: <span class="comments-count"><?php echo $comments_count ?></span></small>
						<button class="comments_show btn btn-outline-info btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Show comments">
							<i class="fa fa-comments"></i>
						</button>
							<?php } ?>
						<?php } ?>
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
			<div class="row post-comments" id="comments_<?php echo $post['id'] ?>">
			<?php if(true === array_key_exists($post['id'], $comments)){ ?>
				<?php foreach($comments[$post['id']] as $comment){ ?>
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
				<?php } ?>
			<?php } ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>