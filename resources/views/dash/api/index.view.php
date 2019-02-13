<div class="wrap mp-wrap">
	<?php $this->getNotice(); ?>

	<h1><?php echo static::TITLE; ?></h1>
	<hr />

	<form>
		<?php mp_form_fields( 'ajax', 'update', $this ); ?>

		<table class="widefat striped">
			<tbody>
				<tr>
					<th>Use Test Mode</th>
					<td>
						<div class="mp-checkbox-wrap">
							<input type="checkbox" value="yes" name="api[testMode]" <?php echo $apiSettings->testMode === 'yes' ? 'checked' : ''; ?>>
							<div class="mp-checkbox-toggler">
								<label>Test Mode</label>
							</div>
						</div>
					</td>
				</tr>
				<tr>
				<th>Enable Debug Mode</th>
					<td>
						<div class="mp-checkbox-wrap">
							<input type="checkbox" value="yes" name="api[debug]" <?php echo $apiSettings->debug === 'yes' ? 'checked' : ''; ?>>
							<div class="mp-checkbox-toggler">
								<label>Enable Debugging</label>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>BillingFox API Key</th>
					<td>
						<input type="text" name="api[key]" value="<?php echo $apiSettings->key; ?>">
					</td>
				</tr>
				<tr data-mp-stripe-keys="test">
					<th>Stripe Test Publisher Key</th>
					<td>
						<input type="text" name="stripe[test][publisher]" value="<?php echo $stripeSettings->test->publisher; ?>">
					</td>
				</tr>
				<tr data-mp-stripe-keys="test">
					<th>Stripe Test Secret Key</th>
					<td>
						<input type="text" name="stripe[test][secret]" value="<?php echo $stripeSettings->test->secret; ?>">
					</td>
				</tr>
				<tr data-mp-stripe-keys="live" style="display: none">
					<th>Stripe Live Publisher Key</th>
					<td>
						<input type="text" name="stripe[live][publisher]" value="<?php echo $stripeSettings->live->publisher; ?>">
					</td>
				</tr>
				<tr data-mp-stripe-keys="live" style="display: none">
					<th>Stripe Live Secret Key</th>
					<td>
						<input type="text" name="stripe[live][secret]" value="<?php echo $stripeSettings->live->secret; ?>">
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
