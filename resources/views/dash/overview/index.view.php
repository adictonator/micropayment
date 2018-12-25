<h1><?php echo static::$title; ?></h1>
<hr />

<div class="mp-admin-blocks-wrap mp-flex mp-flex-jc-sb">

	<?php foreach ( $menus as $menu ) :
		$key = mp_menu_slug($menu::$title);

		if ( ! isset( $menu::$isMainMenu ) || ! $menu::$isMainMenu ) : ?>

		<div class="mp-admin-block">
			<h3 class="mp-admin-block__title"><?php echo $menu::$title; ?></h3>
			<div class="mp-admin-block__content">

				<?php if ( isset( $menuData[ $key ] ) ) : ?>
				<ul>
					<?php foreach ( $menuData[ $key ] as $dataKey => $data ) : ?>
					<li><?php echo $data['label']; ?> <strong><?php echo $data['value'] ; ?></strong></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<a class="mp-admin-block__link" href="<?php echo admin_url( 'admin.php?page=' ) . mp_menu_slug( $menu::$title ); ?>">Go</a>
			</div>
		</div>

		<?php endif;
	endforeach; ?>

</div>
