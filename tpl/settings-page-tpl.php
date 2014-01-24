
<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Ravelry Designs Widget</h2>

	<form method="post" action="options.php">
		<?php settings_fields('rdw_settings_group'); ?>
	    <?php do_settings_sections('rdw'); ?>
		<?php submit_button(); ?>
	</form>
</div>
