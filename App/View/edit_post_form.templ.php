<div class="container">
	<form id="add_post">
		<h3>Edit post</h3>
		<div>
			<div class="form-group">
				<label for="post_title">Title:</label>
				<input type="text" class="form-control" id="post_title" name="post_title" placeholder="Enter title" value="<?php echo $title ?>" required>
				<div class="error_title"></div>
			</div>
			<div class="form-group">
				<label for="post_body">Post:</label>
				<textarea class="form-control" id="post_body" name="post_body" placeholder="Enter text here..." required><?php echo $body ?></textarea>
				<div class="error_body"></div>
			</div>
			<div class="form-group">
				<input type="hidden" name="post_id" id="post_id"  value="<?php echo $post_id ?>">
				<button class="btn" type="button" id="cancel_post_btn">Cancel</button>
				<button class="btn btn-primary" type="button" id="edit_post_btn">Change</button>
			</div>
		</div>
	</form>
</div>