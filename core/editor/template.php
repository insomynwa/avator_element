<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-avator-element-template-library-activate-license-button">
	<a class="elementor-template-library-template-action elementor-button elementor-button-go-pro" href="<?php echo \AvatorElement\License\Admin::get_url(); ?>" target="_blank">
		<i class="eicon-external-link-square"></i>
		<span class="elementor-button-title"><?php _e( 'Activate License', 'avator-element' ); ?></span>
	</a>
</script>
