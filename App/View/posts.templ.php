<?php if(0 !== count($posts)){ ?>
	<?php foreach($posts as $post){ ?>
		<div class="post">
			<h4><?php echo $post['title'] ?></h4>
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
					<div class="post-edit-buttons">
					<?php if((int)$curr_user === (int)$post['user_id']){ ?>
						<button class="edit_post btn btn-outline-info btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Edit post">
							<i class="fa fa-pencil-square-o"></i>
						</button>
						<button class="delete_post btn btn-outline-danger btn-sm" data-post-id="<?php echo $post['id'] ?>" title="Delete post">
							<i class="fa fa-times"></i>
						</button>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>