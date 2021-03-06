<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;


if ( ! function_exists( 'machete_powertools_save_options' ) ) :


function machete_powertools_save_options() {

	if (isset($_POST['optionEnabled'])){

		$settings = $_POST['optionEnabled'];

		for($i = 0; $i < count($settings); $i++){
			$settings[$i] = sanitize_text_field($settings[$i]);
		}
		
		if ($old_options = get_option('machete_powertools_settings')){
			if(
				(0 == count(array_diff($settings, $old_options))) &&
				(0 == count(array_diff($old_options, $settings)))
				){
				// no removes && no adds
				new Machete_Notice(__( 'No changes were needed.', 'machete' ), 'info');
				return false;
			}
		}
		

		if (update_option('machete_powertools_settings',$settings)){
			return true;
		}else{
			new Machete_Notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
			return false;
		}

	}else{
		if (delete_option('machete_powertools_settings')){
			return true;
		}else{
			new Machete_Notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
			return false;
		}
	}
	return false;
}

function machete_powertools_do_action(){

	switch ($_POST['action']){
		case __('Purge Transients','machete') :
			return machete_powertools_purge_transients();
			break;
		case __('Purge Post Revisions','machete') :
			return machete_powertools_purge_post_revisions();
			break;
		case __('Flush Rewrite Rules','machete') :
			return machete_powertools_flush_rewrite_rules();
			break;
		default:
			return false;

	}

	return false;
}

function machete_powertools_purge_transients(){
	//echo '<h1 style="text-align: right">'.__('Purge Transients','machete').'</h1>';
	
	global $wpdb;

	/*
	 * Deletes all expired transients. The multi-table delete syntax is used.
	 * to delete the transient record from table a, and the corresponding.
	 * transient_timeout record from table b.
	 *
	 * Based on code inside core's upgrade_network() function.
	 */
	$sql = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
		WHERE a.option_name LIKE %s
		AND a.option_name NOT LIKE %s
		AND b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
		AND b.option_value < %d";
	$rows = $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( '_transient_' ) . '%', $wpdb->esc_like( '_transient_timeout_' ) . '%', time() ) );

	$sql = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
		WHERE a.option_name LIKE %s
		AND a.option_name NOT LIKE %s
		AND b.option_name = CONCAT( '_site_transient_timeout_', SUBSTRING( a.option_name, 17 ) )
		AND b.option_value < %d";
	$rows2 = $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( '_site_transient_' ) . '%', $wpdb->esc_like( '_site_transient_timeout_' ) . '%', time() ) );

	echo '<div class="updated inline"><p>' . sprintf( __( '%d Transients Rows Cleared', 'woocommerce' ), $rows + $rows2 ) . '</p></div>';

	return true;
}

function machete_powertools_purge_post_revisions(){
	//echo '<h1 style="text-align: right">'.__('Purge Post Revisions','machete').'</h1>';
	
	global $wpdb;

	// DELETE ALL UNUSED POST REVISIONS
	$sql = "
	DELETE a,b,c
		FROM wp_posts a
		WHERE a.post_type = 'revision'
		LEFT JOIN wp_term_relationships b
		ON (a.ID = b.object_id)
		LEFT JOIN wp_postmeta c ON (a.ID = c.post_id);";
	$rows = $wpdb->query($sql);
	echo '<div class="updated inline"><p>' . sprintf( __( '%d Post revisions deleted', 'woocommerce' ), $rows) . '</p></div>';
	
	new Machete_Notice(
	  sprintf( _n( 'Success! %s Post revision deleted.', 'Success! %s Post revisions deleted.', $rows, 'machete' ), $rows ),
	  'success'
	);

	return true;
}

function machete_powertools_flush_rewrite_rules(){
	//echo '<h1 style="text-align: right">'.__('Flush Rewrite Rules','machete').'</h1>';
	
	flush_rewrite_rules();


	return true;
}







endif; // machete_powertools_save_options()
