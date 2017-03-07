<div class="album text-muted">
	<div class="container wall-wrap">
		<div id="alerts"></div>
		<?php if($auth){ ?>
		<div id="form"></div>
		<?php } ?>
		<div class="row content">
			<div class="col-12 actions">
				<?php if($auth){ ?>
				<div id="buttons">
					<button id="expand_all_comment" class="btn btn-outline-primary">
						<i class="fa fa-expand"></i> <span>Show all comments</span>
					</button>
					<button id="show_post_btn" class="btn btn-outline-success pull-right">
						<i class="fa fa-plus"></i> Add post
					</button>
				</div>
				<?php } else { ?>
					<div class="alert alert-info">For comments and posts - <a href="<?php echo HTTP_SERVER ?>">please login</a></div>
				<?php } ?>
			</div>
			<div class="col-12 posts-wrap">
				<div class="posts-content">
					<div class="row posts"><?php echo $posts; ?></div>
				</div>
				<div class="loader"></div>
			</div>
		</div>

	</div>
	<script>
		var actions_data = {
			base_url: '<?php echo HTTP_SERVER ?>',
			txt_post: {
				'success': 'Post successfully added!',
				'edit': 'Post successfully change!',
				'delete': 'Post successfully delete!'
			},
			txt_comment: {
				'success': 'Comment successfully added!',
				'edit': 'Comment successfully change!',
				'delete': 'Comment successfully delete!'
			}
		};
	</script>
	<div class="shadow"></div>
</div>
