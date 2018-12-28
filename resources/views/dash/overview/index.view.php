<h1><?php echo static::TITLE; ?></h1>
<hr />

<div class="mp-admin-blocks-wrap mp-flex mp-flex-jc-sb">

	<?php foreach ( $menus as $key => $menuTitle ) : ?>

		<div class="mp-admin-block">
			<h3 class="mp-admin-block__title"><?php echo $menuTitle; ?></h3>
			<div class="mp-admin-block__content">

				<?php if ( isset( $menuData[ $key ] ) && is_array( $menuData[ $key ] ) ) : ?>
				<ul>
					<?php foreach ( $menuData[ $key ] as $dataKey => $data ) :
						if ( is_array( $data ) ): ?>
						<li><?php echo $data['label']; ?> <strong><?php echo $data['value'] ; ?></strong></li>
						<?php endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>

				<a class="mp-admin-block__link" href="<?php echo admin_url( 'admin.php?page=' ) . mp_menu_slug( $menuTitle ); ?>">Go</a>
			</div>
		</div>

	<?php endforeach; ?>

</div>
