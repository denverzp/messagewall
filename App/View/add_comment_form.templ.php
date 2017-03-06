<div class="container no-gutters">
	<form id="add_comment">
		<h3>Add comment</h3>
		<div>
			<div class="form-group">
				<label for="post_body">Comment:</label>
				<textarea class="form-control" id="comment_body" name="comment_body" placeholder="Enter message here..." required></textarea>
				<div class="error_body"></div>
			</div>
			<div class="form-group">
				<input type="hidden" name="comment_id" value="0">
				<input type="hidden" name="post_id" value="<?php echo $post_id ?>">
				<input type="hidden" name="parent_id" value="<?php echo $parent_id ?>">
				<input type="hidden" name="level" value="<?php echo $level ?>">
				<button class="btn" type="button" id="cancel_comment_btn">Cancel</button>
				<button class="btn btn-primary" type="button" id="add_comment_btn">Publish</button>
			</div>
		</div>
	</form>
</div>