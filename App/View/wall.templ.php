<div class="album text-muted">
	<div class="container wall-wrap">
		<div id="alerts"></div>
		<?php if($auth){ ?>
		<div id="form"></div>
		<?php } ?>
		<div class="row">
			<div class="col-9 content">
				<div class="row posts"><?php echo $posts; ?></div>
			</div>
			<div class="col-3 sidebar">
				<div id="buttons" class="text-center">
					<button id="show_post_btn" class="btn btn-success">
						<i class="fa fa-plus"></i> Add post
					</button>
				</div>
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
			}
		};
	</script>
	<div class="shadow"></div>
</div>
