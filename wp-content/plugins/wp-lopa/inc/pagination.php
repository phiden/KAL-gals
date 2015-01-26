<?php

if ( ! defined( 'WPLOPA_TOKEN' ) )
	die( 'Do not call this file directly.' );

// Check if pagination shoud be shown before the loop.
function wp_lopa_before() {
	if ( 'Y' == get_option( 'lopa_show_before' ) ) {
		wp_lopa_output( get_option( 'lopa_before_id' ) );
	}
}

// Check if pagination shoud be shown after the loop.
function wp_lopa_after() {
	if ( 'Y' == get_option( 'lopa_show_after' ) )
	{
		wp_lopa_output( get_option( 'lopa_after_id' ) );
	}
}

// The template tag
function the_wp_lopa( $nav = '' ) {
	wp_lopa_output( $nav );
}

// This is where the magic happens
function wp_lopa_output( $nav_id = '' ) {
	global $wp_query, $post, $page;

	$total_pages = $wp_query->max_num_pages;
	$curr_page = get_query_var('paged');
	$curr_page = ($curr_page < 1) ? 1 : $curr_page;
	$show_links = get_option('lopa_show_links');

	if ( get_option( 'lopa_show_prevnext' ) == 'Y' )
		$show_prevnext = true;

	$before_curr = ($curr_page > 1) ? $curr_page - 1 : 0;
	$after_curr = $total_pages - $curr_page;

	$show_before = ceil(($before_curr / $total_pages * 100) * ($show_links / 100));
	$show_after = $show_links - $show_before;

	$pages = array($curr_page);

	if ( $curr_page > 1 ) {
		array_push($pages, 1);
	}

	if ( $curr_page < $total_pages ) {
		array_push( $pages, $total_pages );
	}

	// Calculate pages to be shown before the current one.
	for ( $i = 0; $i < $show_before; $i++ ) {
		$page_number = ( $curr_page - ( pow( 2, $i ) ) );
		if ( $page_number > 1 ) {
			array_push( $pages, $page_number );
		}
	}

	// Calculate pages to be shown after the current one.
	for ( $i = 0; $i < $show_after; $i++ ) {
		$page_number = ( $curr_page + ( pow( 2, $i ) ) );
		if ( $page_number <= $total_pages ) {
			array_push( $pages, $page_number );
		}
	}

	$pages = array_unique( $pages );
	sort( $pages );

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="site-navigation paging-navigation">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'wp-lopa' ); ?></h1>
		<ul>
	<?php
	if ( $show_prevnext && $curr_page > 1 )
	{
	?>
		<li><a href="<?php echo get_pagenum_link( $curr_page - 1 ); ?>" rel="prev" class="prev"><span class="paginate_link"><?php echo get_option( 'lopa_prev_text' ); ?></span></a></li>
	<?php
	}
	foreach ( $pages as $page_id ) {
		$rel = '';
		if ( $page_id != $curr_page ) {
			if ( $curr_page + 1 == $page_id ) {
				$rel = ' rel="next"';
			} elseif ( $curr_page - 1 == $page_id ) {
				$rel = ' rel="prev"';
			}
			$html = '<a' . $rel . ' href="' . get_pagenum_link( $page_id ) . '"><span class="paginate_link">' . $page_id . '</span></a>';
		}
		else
		{
			$html = '<span class="current">' . $page_id . '</span>';
		}
		$gap = abs( $old_id - $page_id );
		if ( $old_id < ($page_id - 1) ) {
	?>
		<li><span class="seperator"><?php if ( $gap < 10 ) { echo '..'; } else { echo '...'; } ?></span></li>
	<?php
		}
	?>
		<li><?php echo $html; ?></li>
	<?php
		$old_id = $page_id;
	}
	if ( $show_prevnext && $curr_page < $total_pages ) {
	?>
		<li><a href="<?php echo get_pagenum_link( $curr_page + 1 ); ?>" rel="next" class="next"><span class="paginate_link"><?php echo get_option( 'lopa_next_text' ); ?></span></a></li>
	<?php
	}
	?></ul>
	</nav>
<?php
}

function wp_lopa_load_stylesheet() {

	echo '<style type="text/css">.assistive-text { display: none; }.paging-navigation ul { float: left; list-style: none; margin: auto; padding: 0; }.paging-navigation li { float: left; }.paging-navigation a { text-decoration: underline; }.paging-navigation span { padding: 0 10px; }.paging-navigation .paginate_link { font-weight: bold; }</style>
';

}

?>