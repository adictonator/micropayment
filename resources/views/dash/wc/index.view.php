<div class="wrap mp-wrap">
	<h1><?php echo static::TITLE; ?></h1>
	<hr />

	<div class="mp-section">
		<h3>BillingFox Payment Gateway Settings</h3>
		<form>
			<?php mp_form_fields( 'ajax', 'update', $this ); ?>
			<table class="widefat striped">
				<tbody>
					<tr>
						<th>Payment Gateway Status</th>
						<td>
							<div class="mp-checkbox-wrap">
								<input type="checkbox" value="yes" name="enabled" <?php echo $settings['enabled'] === 'yes' ? 'checked' : ''; ?>>
								<div class="mp-checkbox-toggler">
									<label>Enable</label>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th>Title</th>
						<td><input type="text" name="title" placeholder="Title for the payment gateway" value="<?php echo $settings['title']; ?>"></td>
					</tr>
					<tr>
						<th>Description</th>
						<td>
							<textarea cols="20" rows="3" name="description"><?php echo $settings['description']; ?></textarea>
						</td>
					</tr>
					<tr>
						<th colspan="2">
							<button type="button" data-mp-form-btn class="button-primary">Update</button>
							<a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section='. MP_PLUGIN_SLUG .'_gateway' ); ?>">View Advance Settings &rarr;</a>
						</th>
					</tr>
				</tbody>
			</table>
		</form>
	</div>

	<div class="mp-section">
		<h3>BillingFox Products</h3>
		<?php foreach ( $products as $product ) : ?>
		<div>
			<h4><?php echo $product->title; ?></h4>
			<figure><?php echo wp_get_attachment_image( $product->image ); ?></figure>
			<p>
				<span>Price: <?php echo $product->meta->price; ?></span>
				<span>Created At: <?php echo $product->meta->created_at->date_i18n(); ?></span>
			</p>
			<p>
				<span><a href="<?php echo $product->link; ?>">View Product</a></span>
				<span><?php echo edit_post_link( 'Edit Product', '', '', $product->id ); ?></span>
			</p>
		</div>
		<a href="<?php echo $product->link; ?>" title="<?php echo $product->title; ?>">
		</a>
		<?php endforeach; ?>
	</div>
</div>