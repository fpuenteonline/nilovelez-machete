<?php if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit; ?>

<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php _e('Machete PowerTools','machete') ?></h1>

	<p class="tab-description"><?php _e('You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).','machete') ?></p>
	<?php machete_admin_tabs('machete-powertools'); ?>
	<!--<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one aditional database request.','machete') ?></span></p>-->

<?php
if(!$machete_powertools_settings = get_option('machete_powertools_settings')){
	$machete_powertools_settings = array();
};
?>


<form id="mache-powertools-actions" action="" method="POST">

	<?php wp_nonce_field( 'machete_powertools_action' ); ?>
	<input type="hidden" name="machete-powertools-action" value="true">

	<table class="form-table">
	<tbody><tr>

	<tr>
	<th scope="row"><label for="tracking_id"><?php _e('Delete Expired Transients','machete') ?></label></th>
	<td><input type="submit" name="action" value="<?php _e('Purge Transients','machete') ?>" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php _e('Format:','machete') ?></p></td>
	</tr>
	<!--
	<tr>
	<th scope="row"><label for="tracking_id"><?php _e('Delete Unused Post Revisions','machete') ?></label></th>
	<td><input type="submit" name="action" value="<?php _e('Purge Post Revisions','machete') ?>" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php _e('Format:','machete') ?></p></td>
	</tr>
	-->

	<tr>
	<th scope="row"><label for="tracking_id"><?php _e('Delete Permalink Cache','machete') ?></label></th>
	<td><input type="submit" name="action" value="<?php _e('Flush Rewrite Rules','machete') ?>" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php _e('Format:','machete') ?></p></td>
	</tr>


	</tbody></table>	
</form>


<?php

if(!$machete_powertools_settings = get_option('machete_powertools_settings')){
	$machete_powertools_settings = array();
};

$machete_powertools_array = array(
	'post_cloner' => array(
		'title' => __('Post/Page clone','machete'),
		'description' => __('Enables the "clone post" feature for every post type except "product".','machete')
	),
	'widget_shortcodes' => array(
		'title' => __('Shortcodes in Widgets','machete'),
		'description' => __('Enables the use of shortcodes in text/html widgets. It may slightly impact performance','machete')
	),
	'widget_oembed' => array(
		'title' => __('OEmbed in Widgets','machete'),
		'description' => __('Enables OEMbed in text/html widgets.','machete')
	),
	'rss_thumbnails' => array(
		'title' => __('Thumbnails in RSS','machete'),
		'description' => __('Add the featured image or the first attached image as the thumbnail of each post in the RSS feed','machete')
	),
	'page_excerpts' => array(
		'title' => __('Excerpts in Pages','machete'),
		'description' => __('','machete')
	),
	
);

$machete_all_powertools_checked = (count($machete_powertools_settings) == count($machete_powertools_array)) ? true : false;
?>



	<form id="machete-powertools-options" action="" method="POST">

		<?php wp_nonce_field( 'machete_save_powertools' ); ?>

		<input type="hidden" name="machete-powertools-saved" value="true">

		<h3><?php _e('Machete Toolbox','machete') ?></h3>

		


		<table class="wp-list-table widefat fixed striped posts machete-powertools-table machete-optimize-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_powertools_checkall_fld" <?php if ($machete_all_powertools_checked) echo 'checked' ?>></td>
				<th class="column-title"><?php _e('Tool','machete') ?></th>
				<th><?php _e('Description','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($machete_powertools_array as $option_slug => $option){ ?>
			<tr>
				<td><input type="checkbox" name="optionEnabled[]" value="<?php echo $option_slug ?>" id="<?php echo $option_slug ?>_fld" <?php if (in_array($option_slug, $machete_powertools_settings)) echo 'checked' ?>></td>
				<td class="column-title column-primary"><strong><?php echo $option['title'] ?></strong></td>
				<td><?php echo $option['description'] ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>
		<?php submit_button(); ?>
	</form>









</div>


<script>


(function($){
	$('#machete-powertools-options .machete-powertools-table :checkbox').change(function() {
	    // this will contain a reference to the checkbox
	    console.log(this.id); 
	    var checkBoxes = $("#machete-powertools-options .machete-powertools-table input[name=optionEnabled\\[\\]]");

	    if (this.id == 'machete_powertools_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
	    }else{
	    	var checkBoxes_checked = $("#machete-powertools-options .machete-powertools-table input[name=optionEnabled\\[\\]]:checked");
	    	if(checkBoxes_checked.length == checkBoxes.length){
	    		$('#machete_powertools_checkall_fld').prop("checked", true);
	    	}else{
	    		$('#machete_powertools_checkall_fld').prop("checked", false);
	    	}
	    }
	});
})(jQuery);


</script>