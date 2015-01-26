<?php

if ( ! defined( 'WPLOPA_TOKEN' ) )
	die( 'Do not call this file directly.' );

// Add settings link on plugin page
function wp_lopa_settings_link( $links ) { 
  $settings_link = '<a href="options-general.php?page=wp_lopa">' . __( 'Settings', 'wp-lopa' ) . '</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}

// Hook Settings link
add_filter( "plugin_action_links_$plugin" , 'wp_lopa_settings_link' );

// Hook Admin Menu Item
add_action( 'admin_menu', 'wp_lopa_menu' );

// Admin Menu Item
function wp_lopa_menu() {
	global $lopa_plugin_options_hook;

	$lopa_plugin_options_hook = add_options_page( __( 'Logarithmic pagination', 'wp-lopa' ), __( 'Log. Pagination', 'wp-lopa' ), 'manage_options', 'wp_lopa', 'wp_lopa_options' );

	add_action( 'load-' . $lopa_plugin_options_hook, 'lopa_plugin_help' );
}

// Admin page contextual help
function lopa_plugin_help() {

	global $lopa_plugin_options_hook;
	$screen = get_current_screen();
	if ( $screen->id == $lopa_plugin_options_hook ) {
		$screen->add_help_tab(
			array(
				'id'      => 'general',
				'title'   => __( 'General', 'wp-lopa' ),
				'content' => '<p>' . __( 'In the future i will try to provide you with more useful stuff here. Check out the other tabs until then.', 'wp-lopa' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
				'id'      => 'css-help',
				'title'   => __( 'CSS', 'wp-lopa' ),
				'content' => '<p>' . __( 'The default CSS is only ment to be a temporary solution. Take the following code as a starting point and add it to your theme. Then adapt it to fit your visual needs.', 'wp-lopa' ) . '</p>
<pre>
.assistive-text {
	display:     none;
}
.paging-navigation ul {
	float:       left;
	list-style:  none;
	margin:      auto;
	padding:     0;
}
.paging-navigation li {
	float:       left;
}
.paging-navigation a {
	text-decoration: underline;
}
.paging-navigation span {
	padding:     0 10px;
}
.paging-navigation .paginate_link {
	font-weight: bold;
}
.paging-navigation .current {}
</pre>',
			)
		);
		$screen->set_help_sidebar( '<p><strong>' . __( 'Helpful Links', 'wp-lopa' ) . '</strong><p><a href="http://www.k308.de/labs/logarithmic-pagination/">' . __( 'Plugins website blog', 'wp-lopa' ) . '</a></p><p><a href="http://blog.k308.de/">' . __( 'Authors blog', 'wp-lopa' ) . '</a></p>' );
	}
}

// Admin page
function wp_lopa_options() {
	if ( ! current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$hidden_submit = 'wp_lopa_submit';
	$opts = array(
		'lopa_show_css'        => '',
		'lopa_show_links'      => '',
		'lopa_show_before'     => '',
		'lopa_before_id'       => '',
		'lopa_show_after'      => '',
		'lopa_after_id'        => '',
		'lopa_show_prevnext'   => '',
		'lopa_prev_text'       => '',
		'lopa_next_text'       => '',
		'lopa_switch_author'   => '',
		'lopa_switch_category' => '',
		'lopa_switch_date'     => '',
		'lopa_switch_home'     => '',
		'lopa_switch_search'   => '',
		'lopa_switch_tag'      => '',
	);

	if ( wp_verify_nonce( $_POST[ 'lopa_settings_page' ], 'lopa_settings_page' ) ) {
		if ( isset( $_POST[ $hidden_submit ] ) && $_POST[ $hidden_submit ] == 'Y' ) {
			foreach ( $opts as $key => $value ) {
				if ( 'lopa_show_links' == $key ) {
					$_POST[ $key ] = absint( $_POST[ $key ] );
				} else {
					$_POST[ $key ] = esc_sql( $_POST[ $key ] );
				}
				update_option( $key, $_POST[ $key ] );
			}

			?><div class="updated"><p><strong><?php _e( 'Settings saved.', 'wp-lopa' ); ?></strong></p></div><?php

		}
	}

	if ( wp_verify_nonce( $_POST[ 'lopa_settings_page_delete' ], 'lopa_settings_page_delete' ) ) {
		foreach ( $opts as $key => $value ) {
			delete_option( $key );
		}
		?><div class="updated"><p><strong><?php _e( 'Settings deleted.', 'wp-lopa' ); ?></strong></p></div><?php
	}

	foreach ( $opts as $key => $value ) {
		$opts[ $key ] = get_option( $key );
	}

	?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e( 'Logarithmic Pagination', 'wp-lopa' ); ?></h2>
	<p><?php _e( 'To gain a more balanced link structure for paginated pages this plugins uses logerithmic logic to calculate which page to link to.', 'wp-lopa' ); ?></p>
	<form name="form1" method="post" action="">
		<?php wp_nonce_field( 'lopa_settings_page', 'lopa_settings_page' ); ?>
		<input type="hidden" name="<?php echo $hidden_submit; ?>" value="Y">
		<h3 class="title"><?php _e( 'Global Options', 'wp-lopa' ); ?></h3>
		<p><?php _e( 'These options relate to <b>all</b> displayed pagination elements.', 'wp-lopa' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Max. # of pages:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_show_links"><input name="lopa_show_links" type="number" min="3" step="1" id="lopa_show_links" value="<?php echo $opts[ 'lopa_show_links' ]; ?>" class="small-text"></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( '"Default CSS:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_show_css"><input type="checkbox" id="lopa_show_css" name="lopa_show_css" value="Y"<?php if ( 'Y' == $opts[ 'lopa_show_css' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Use the default style.', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( '"Previous" &amp; "Next" links:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_show_prevnext"><input type="checkbox" id="lopa_show_prevnext" name="lopa_show_prevnext" value="Y"<?php if ( 'Y' == $opts[ 'lopa_show_prevnext' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show "previous" &amp; "next" links', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Text for "Previous" Link:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_prev_text"><input name="lopa_prev_text" type="text" id="lopa_prev_text" value="<?php echo $opts[ 'lopa_prev_text' ]; ?>" class="regular-text"></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Text for "Next" Link:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_next_text"><input name="lopa_next_text" type="text" id="lopa_next_text" value="<?php echo $opts[ 'lopa_next_text' ]; ?>" class="regular-text"></label></td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e( 'Where to show it', 'wp-lopa' ); ?></h3>
		<p><?php _e( 'Choose below on which pages you want the plugin to display pagination.', 'wp-lopa' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'On the homepage:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_switch_home"><input type="checkbox" id="lopa_switch_home" name="lopa_switch_home" value="Y"<?php if ( 'Y' == $opts[ 'lopa_switch_home' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination on the homepage', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'On category archives:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_switch_category"><input type="checkbox" id="lopa_switch_category" name="lopa_switch_category" value="Y"<?php if ( 'Y' == $opts[ 'lopa_switch_category' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination on category archives', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'On date archives:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_switch_date"><input type="checkbox" id="lopa_switch_date" name="lopa_switch_date" value="Y"<?php if ( 'Y' == $opts[ 'lopa_switch_date' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination on date archives', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'On tag archives:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_switch_tag"><input type="checkbox" id="lopa_switch_tag" name="lopa_switch_tag" value="Y"<?php if ( 'Y' == $opts[ 'lopa_switch_tag' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination on tag archives', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'On search-results:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_switch_search"><input type="checkbox" id="lopa_switch_search" name="lopa_switch_search" value="Y"<?php if ( 'Y' == $opts[ 'lopa_switch_search' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination on search-results', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'On author archives:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_switch_author"><input type="checkbox" id="lopa_switch_author" name="lopa_switch_author" value="Y"<?php if ( 'Y' == $opts[ 'lopa_switch_author' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination on author archives', 'wp-lopa' ); ?></label></td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e( 'Before Options', 'wp-lopa' ); ?></h3>
		<p><?php _e('These options only relate to pagination displayed <b>before</b> the content (loop_start).', 'wp-lopa' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Before loop content:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_show_before"><input type="checkbox" id="lopa_show_before" name="lopa_show_before" value="Y"<?php if ( 'Y' == $opts[ 'lopa_show_before' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Id attribute:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_before_id"><input name="lopa_before_id" type="text" id="lopa_before_id" value="<?php echo $opts[ 'lopa_before_id' ]; ?>" class="regular-text"></label></td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e( 'After Options', 'wp-lopa' ); ?></h3>
		<p><?php _e( 'These options only relate to pagination displayed <b>after</b> the content (loop_end).', 'wp-lopa' ); ?></p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'After loop content:', 'wp-lopa' ); ?></th>
					<td><label for="lopa_show_after"><input type="checkbox" id="lopa_show_after" name="lopa_show_after" value="Y"<?php if ( 'Y' == $opts[ 'lopa_show_after' ] ) { ?> checked="checked"<?php } ?>> <?php _e( 'Show pagination', 'wp-lopa' ); ?></label></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e("Id attribute:", 'wp-lopa' ); ?></th>
					<td><label for="lopa_after_id"><input name="lopa_after_id" type="text" id="lopa_after_id" value="<?php echo $opts[ 'lopa_after_id' ]; ?>" class="regular-text"></label></td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ) ?>" /></p>
	</form>
	<form name="form2" method="post" action="">
		<?php wp_nonce_field( 'lopa_settings_page_delete', 'lopa_settings_page_delete' ); ?>
		<h3 class="title"><?php _e( 'Delete settings', 'wp-lopa' ); ?></h3>
		<p><?php _e( 'This will clear all options.', 'wp-lopa' ); ?></p>
		<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Delete Settings' ) ?>" /></p>
	</form>
</div>
<?php
}

?>