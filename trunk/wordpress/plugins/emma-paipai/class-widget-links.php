<?php
/**
 * Shopping Cart Widget
 *
 * Displays shopping cart widget
 *
 * @author 		WooThemes
 * @category 	Widgets
 * @package 	Odsea/Widgets
 * @version 	2.0.0
 * @extends 	WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class EMMAPI_Widget_LINKS extends WP_Widget {

	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	/**
	 * constructor
	 *
	 * @access public
	 * @return void
	 */
	function EMMAPI_Widget_LINKS() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'emma-paipai widget_links', 'description' => 'Show links at site!' );

		/* Create the widget. */
		$this->WP_Widget( 'emmapi_links', 'Links', $widget_ops );
	}


	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		extract($args);

		$title = $instance['title'];
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title;

		echo '<ul>';
		foreach(emmapaipai_get_links() as $url_title => $url)
		{
			echo '<li><a href="'.$url.'" target="_blank">'.$url_title.'</a>';
		}
		echo '</ul>';
		
		echo $after_widget;
	}


	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	function form( $instance ) {
		global $wpdb;
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
		<?php
	}

}