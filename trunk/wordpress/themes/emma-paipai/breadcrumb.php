<?php
/**
 * Shop breadcrumb
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $wp_query;

$delimiter = ' > ';
$home = _x( '首页', 'breadcrumb', 'ez_gallery' );


$prepend      = '';
$ez_gallery_page_id = get_gallery_page_id();
$ez_gallery_page    = get_post( $ez_gallery_page_id );

// If permalinks contain the shop page in the URI prepend the breadcrumb with shop
if ( $ez_gallery_page_id  && get_option( 'page_on_front' ) !== $ez_gallery_page_id ) {
	$prepend = '<a href="' . get_permalink( $ez_gallery_page ) . '">' . $ez_gallery_page->post_title . '</a> ' . $delimiter;
}

if ( ( ! is_home() && ! is_front_page() && ! ( is_post_type_archive() && get_option( 'page_on_front' ) == $ez_gallery_page_id ) ) || is_paged() )
{

	echo '<div class="breadcrumb">';

	if ( ! empty( $home ) ) {
		echo '<a class="home" href="' . home_url() . '">' . $home . '</a>' . $delimiter;
	}

	if ( is_category() ) {

		$cat_obj = $wp_query->get_queried_object();
		$this_category = get_category( $cat_obj->term_id );

		if ( $this_category->parent != 0 ) {
			$parent_category = get_category( $this_category->parent );
			echo get_category_parents($parent_category, TRUE, $delimiter );
		}

		echo single_cat_title( '', false );

	} elseif ( is_tax('gallery_cat') ) {

		echo $prepend;

		$current_term = get_term_by( 'slug', get_query_var( 'gallery_cat' ), 'gallery_cat' );

		$ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );

		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );

			echo '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '">' . esc_html( $ancestor->name ) . '</a>' . $delimiter;
		}

		echo esc_html( $current_term->name );

	} elseif ( is_day() ) {

		echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
		echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter;
		echo get_the_time('d');

	} elseif ( is_month() ) {

		echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
		echo get_the_time('F');

	} elseif ( is_year() ) {

		echo get_the_time('Y');

	} elseif ( is_post_type_archive('ez_gallery') && get_option('page_on_front') !== $ez_gallery_page_id ) {

		$_name = $ez_gallery_page_id ? get_the_title( $ez_gallery_page_id ) : '';

		if ( ! $_name ) {
			$post_type = get_post_type_object( 'ez_gallery' );
			$_name = $post_type->labels->singular_name;
		}

		if ( is_search() ) {

			echo '<a href="' . get_post_type_archive_link('ez_gallery') . '">' . $_name . '</a>' . $delimiter . __( '关键字: &ldquo;', 'ez_gallery' ) . get_search_query() . '&rdquo;';

		} elseif ( is_paged() ) {

			echo '<a href="' . get_post_type_archive_link('ez_gallery') . '">' . $_name . '</a>';

		} else {

			echo $_name;

		}

	} elseif ( is_single() && ! is_attachment() ) {

		if ( get_post_type() == 'ez_gallery' ) {

			echo $prepend;

			if ( $terms = wp_get_post_terms( $post->ID, 'gallery_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {

				$main_term = $terms[0];

				$ancestors = array_reverse( get_ancestors( $main_term->term_id, 'gallery_cat' ) );

				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, 'gallery_cat' );

					echo '<a href="' . get_term_link( $ancestor->slug, 'gallery_cat' ) . '">' . $ancestor->name . '</a>' . $delimiter;
				}

				echo '<a href="' . get_term_link( $main_term->slug, 'gallery_cat' ) . '">' . $main_term->name . '</a>' . $delimiter;

			}

			echo get_the_title();

		} elseif ( get_post_type() != 'post' ) {

			$post_type = get_post_type_object( get_post_type() );
			$slug = $post_type->rewrite;
				echo '<a href="' . get_post_type_archive_link( get_post_type() ) . '">' . $post_type->labels->singular_name . '</a>' . $delimiter;
			echo get_the_title();

		} else {

			$cat = current( get_the_category() );
			echo get_category_parents( $cat, true, $delimiter );
			echo get_the_title();

		}

	} elseif ( is_404() ) {

		echo __( 'Error 404', 'ez_gallery' );

	} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' ) {

		$post_type = get_post_type_object( get_post_type() );

		if ( $post_type )
			echo $post_type->labels->singular_name;

	} elseif ( is_attachment() ) {

		$parent = get_post( $post->post_parent );
		$cat = get_the_category( $parent->ID );
		$cat = $cat[0];
		echo get_category_parents( $cat, true, '' . $delimiter );
		echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>' . $delimiter;
		echo get_the_title();

	} elseif ( is_page() && !$post->post_parent ) {

		echo get_the_title();

	} elseif ( is_page() && $post->post_parent ) {

		$parent_id  = $post->post_parent;
		$breadcrumbs = array();

		while ( $parent_id ) {
			$page = get_page( $parent_id );
			$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title( $page->ID ) . '</a>';
			$parent_id  = $page->post_parent;
		}

		$breadcrumbs = array_reverse( $breadcrumbs );

		foreach ( $breadcrumbs as $crumb )
			echo $crumb . '' . $delimiter;

		echo get_the_title();

	} elseif ( is_search() ) {

		echo __( '关键字: &ldquo;', 'ez_gallery' ) . get_search_query() . '&rdquo;';

	} elseif ( is_tag() ) {

			echo __( '标签: &ldquo;', 'ez_gallery' ) . single_tag_title('', false) . '&rdquo;';

	} elseif ( is_author() ) {

		$userdata = get_userdata($author);
		echo __( '作者:', 'ez_gallery' ) . ' ' . $userdata->display_name;

	}

	if ( get_query_var( 'paged' ) )
		echo ' (' . __( '分页', 'ez_gallery' ) . ' ' . get_query_var( 'paged' ) . ')';

	echo '</div>';

}