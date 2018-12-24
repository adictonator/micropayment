<h1><?php echo static::$title; ?></h1>
<hr />

<div class="mp-wrap">
	<form action="<?php echo mp_form_post(); ?>" method="POST">
		<?php echo mp_form_action_fields(); ?>

		<table class="widefat striped">
			<tbody>
				<tr>
					<th>Enable Debug Mode</th>
					<td>
						<div class="mp-checkbox-wrap">
							<input type="checkbox" value="yes" name="mpDebugMode">
							<div class="mp-checkbox-toggler">
								<label>Enable Debugging</label>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>Use Test Mode</th>
					<td>
						<div class="mp-checkbox-wrap">
							<input type="checkbox" value="yes" name="mpAPIMode">
							<div class="mp-checkbox-toggler">
								<label>Test Mode</label>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<button type="submit" class="button-primary">Update</button>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
