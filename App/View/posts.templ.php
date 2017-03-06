<?php if(0 !== count($posts)){ ?>
	<?php foreach($posts as $post){ ?>
		<div class="post">
			<p class="post-text"><?php echo $post['body'] ?></p>
			<div class="row">
				<div class="post-info col-9">
					<p class="text-left">
						<small><?php echo $post['username'] ?></small>
						&nbsp;|&nbsp;
						<small><?php echo $post['created_at'] ?></small>
						<?php if(true === array_key_exists($post['id'], $comments)){ ?>
							<?php $comments_count = count($comments[$post['id']]) ?>
							<?php if($comments_count > 0){ ?>
						&nbsp;|&nbsp;
						<small>Comments: <?php echo $comments_count ?></small>
						<button class="comments_show btn btn-outline-info btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Show comments">
							<i class="fa fa-comments"></i>
						</button>
							<?php } ?>
						<?php } ?>
					</p>
				</div>
				<div class="post-edit col-3">
					<div class="post-edit-buttons">
						<?php if((int)$curr_user){ ?>
						<button class="comment_post btn btn-outline-success btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Comment">
							<i class="fa fa-comment"></i>
						</button>
						<?php } ?>
					<?php if((int)$curr_user === (int)$post['user_id']){ ?>
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
			<div class="row post-comments"></div>
		</div>
	<?php } ?>
<?php } ?>