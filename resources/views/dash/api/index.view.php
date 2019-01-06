<h1><?php echo static::TITLE; ?></h1>
<hr />

<div class="mp-wrap">
	<form action="<?php echo mp_form_post(); ?>" method="POST">
		<?php echo mp_form_fields('ajax', 'update', $this); ?>

		<table class="widefat striped">
			<tbody>
				<tr>
					<th>BillingFox API Key</th>
					<td>
						<input type="text" name="key" value="<?php echo $apiSettings->key->value; ?>">
					</td>
				</tr>
				<tr>
					<th>Enable Debug Mode</th>
					<td>
						<div class="mp-checkbox-wrap">
							<input type="checkbox" value="yes" name="debug" <?php echo $apiSettings->debug->value === 'yes' ? 'checked' : ''; ?>>
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
							<input type="checkbox" value="yes" name="mode" <?php echo $apiSettings->mode->value === 'yes' ? 'checked' : ''; ?>>
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
						<button type="button" class="button-primary" data-mp-form-btn>Update</button>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
