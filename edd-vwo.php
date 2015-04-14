<?php

/**
 * Plugin Name: Easy Digital Downloads - VWO Revenue Tracking
 * Plugin URI: https://fatcatapps.com/
 * Description: VWO revenue tracking for Easy Digital Downloads
 * Version: 1.0
 * Author: Fatcat Apps
 * Author URI: https://fatcatapps.com/
 */


function edd_vwo_payment_receipt_after_table( $payment, $edd_receipt_args ) {
	if ( empty( $edd_receipt_args['payment_id'] ) || empty( $payment->ID ) ) {
		return;
	}
	$payment_id = $payment->ID;

	$session = EDD()->session;
	$track_key = 'edd_vwo_tracked_payment_ids';

	$tracked_payment_ids = $session->get( $track_key );
	if ( empty( $tracked_payment_ids ) ) {
		$tracked_payment_ids = array();
	}

	if ( ! empty( $tracked_payment_ids[ $payment_id ] ) ) {
		return;
	}

	$tracked_payment_ids[ $payment_id ] = true;
	$session->set( $track_key, $tracked_payment_ids );

	?>
	<script type="text/javascript">
		jQuery( function() {
			var _vis_opt_revenue = "<?php echo edd_get_payment_amount( $payment->ID ) ?>";
			window._vis_opt_queue = window._vis_opt_queue || [];
			window._vis_opt_queue.push(function() {_vis_opt_revenue_conversion(_vis_opt_revenue);});
		} );
	</script>
	<?php
}

add_action( 'edd_payment_receipt_after_table', 'edd_vwo_payment_receipt_after_table', 10, 2 );
