<div class="container">
	<form id="add_post">
		<h3>Add new post</h3>
		<div>
			<div class="form-group">
				<label for="post_title">Title:</label>
				<input type="text" class="form-control" id="post_title" name="post_title" placeholder="Enter title">
			</div>
			<div class="form-group">
				<label for="post_body">Post:</label>
				<textarea class="form-control" id="post_body" name="post_body" placeholder="Enter text here..."></textarea>
			</div>
			<div class="form-group">
				<input type="hidden" name="post_id" value="<?php echo $post_id ?>">
				<input type="hidden" name="user_id" value="<?php echo $user_id ?>">
				<button class="btn" type="button" id="cancel_post_btn">Cancel</button>
				<button class="btn btn-primary" type="button" id="add_post_btn">Publish</button>
			</div>
		</div>
	</form>
</div>