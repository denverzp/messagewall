<div class="container no-gutters">
	<form id="edit_comment">
		<h3>Edit comment</h3>
		<div>
			<div class="form-group">
				<label for="post_body">Comment:</label>
				<textarea class="form-control" id="comment_body" name="comment_body" placeholder="Enter message here..." required><?php echo $body ?></textarea>
				<div class="error_body"></div>
			</div>
			<div class="form-group">
				<input type="hidden" name="comment_id" value="<?php echo $comment_id ?>">
				<input type="hidden" name="post_id" value="<?php echo $post_id ?>">
				<input type="hidden" name="parent_id" value="<?php echo $parent_id ?>">
				<button class="btn" type="button" id="cancel_comment_btn">Cancel</button>
				<button class="btn btn-primary" type="button" id="edit_comment_btn">Publish</button>
			</div>
		</div>
	</form>
</div>