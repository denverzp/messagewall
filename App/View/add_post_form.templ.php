<div class="container no-gutters">
	<form id="add_post">
		<h3>Add new post</h3>
		<div>
			<div class="form-group">
				<label for="post_body">Post:</label>
				<textarea class="form-control" id="post_body" name="post_body" placeholder="Enter message here..." required></textarea>
				<div class="error_body"></div>
			</div>
			<div class="form-group">
				<input type="hidden" name="post_id" value="<?php echo $post_id ?>">
				<button class="btn" type="button" id="cancel_post_btn">Cancel</button>
				<button class="btn btn-primary" type="button" id="add_post_btn">Publish</button>
			</div>
		</div>
	</form>
</div>