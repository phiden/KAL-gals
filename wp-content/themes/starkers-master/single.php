<?php
/**
 * The Template for displaying all single posts
 *
 * Please see /external/starkers-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<article id='main-content'>

	<h2><?php the_title(); ?></h2>
	<div class='date_time_author'><time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?> <?php the_time(); ?></time> | posted by: <?php the_author(); ?> </div>
	<?php the_content(); ?>
	
	<div id="single-page-tags">			
	<?php the_tags(); ?>
	</div>
	
	<?php if ( get_the_author_meta( 'description' ) ) : ?>
	<?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
	<h3>About <?php echo get_the_author() ; ?></h3>
	<?php the_author_meta( 'description' ); ?>
	<?php endif; ?>

	<?php comments_template( '', true ); ?>

</article>

<?php get_sidebar(); ?>

<?php endwhile; ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>