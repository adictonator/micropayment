<div class="mp-wrap wrap">
	<h1><?php echo static::TITLE; ?></h1>
	<hr />

	<div class="mp-admin-blocks-wrap mp-flex mp-flex-jc-sb">
		<div class="mp-admin-block">
			<h3 class="mp-admin-block__title">General Settings</h3>
			<div class="mp-admin-block__content">
				<a class="mp-admin-block__link" href="<?php echo admin_url( 'admin.php?page=' ) . mp_menu_slug( $menus['general'] ); ?>">Go</a>
			</div>
		</div>
		<div class="mp-admin-block">
			<h3 class="mp-admin-block__title">BillingFox API Settings</h3>
			<div class="mp-admin-block__content">
				<ul>
					<li>API Test Mode <strong title="<?php echo $menuData->api->testMode ?: 'No' ; ?>"><?php echo $menuData->api->testMode ?: 'No' ; ?></strong></li>
					<li>Debugging Mode <strong title="<?php echo $menuData->api->debug ?: 'No' ; ?>"><?php echo $menuData->api->debug ?: 'No' ; ?></strong></li>
					<li>BillingFox API Key <strong title="<?php echo $menuData->api->key ?: 'No' ; ?>"><?php echo $menuData->api->key ?: 'No' ; ?></strong></li>
				</ul>

				<a class="mp-admin-block__link" href="<?php echo admin_url( 'admin.php?page=' ) . mp_menu_slug( $menus['api'] ); ?>">Go</a>
			</div>
		</div>
		<div class="mp-admin-block">
			<h3 class="mp-admin-block__title">WooCommerce Settings</h3>
			<div class="mp-admin-block__content">
				<a class="mp-admin-block__link" href="<?php echo admin_url( 'admin.php?page=' ) . mp_menu_slug( $menus['woo'] ); ?>">Go</a>
			</div>
		</div>
	</div>
</div>