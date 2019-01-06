<h1><?php echo static::TITLE; ?></h1>
<hr />

<div class="mp-admin-blocks-wrap mp-flex mp-flex-jc-sb">
	<?php foreach ( $menus as $key => $menuTitle ) : ?>
		<div class="mp-admin-block">
			<h3 class="mp-admin-block__title"><?php echo $menuTitle; ?></h3>
			<div class="mp-admin-block__content">

				<?php if ( isset( $menuData->$key ) && ! empty( $menuData->$key ) && is_object( $menuData->$key ) ) : ?>
				<ul>
				<?php foreach ( $menuData->$key as $dataKey => $data ) : ?>
					<li><?php echo $data->label; ?> <strong title="<?php echo $data->value ?: 'No' ; ?>"><?php echo $data->value ?: 'No' ; ?></strong></li>
				<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<a class="mp-admin-block__link" href="<?php echo admin_url( 'admin.php?page=' ) . mp_menu_slug( $menuTitle ); ?>">Go</a>
			</div>
		</div>
	<?php endforeach; ?>
</div>
