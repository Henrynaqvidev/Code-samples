<?php
/*
Plugin Name: Custom Recent Posts Widget
Description: Adds a custom widget to display recent posts from a specific category.
Version: 1.0
Author: Henry
License: GPL67
*/

class Custom_Recent_Posts_Widget extends WP_Widget {

    // Constructor
    function __construct() {
        parent::__construct(
            'custom_recent_posts_widget',
            __('Custom Recent Posts', 'text_domain'),
            array( 'description' => __( 'Display recent posts from a specific category', 'text_domain' ), )
        );
    }

    // Widget Frontend
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $category = $instance['category'];

        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $recent_posts = new WP_Query( array(
            'posts_per_page' => 5,
            'category_name' => $category
        ) );

        if ( $recent_posts->have_posts() ) {
            echo '<ul>';
            while ( $recent_posts->have_posts() ) {
                $recent_posts->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        }

        echo $args['after_widget'];
    }

    // Widget Backend
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'text_domain' );
        $category = ! empty( $instance['category'] ) ? $instance['category'] : '';

        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo esc_attr( $category ); ?>">
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';

        return $instance;
    }
}

// Register and load the widget
function custom_load_widget() {
    register_widget( 'Custom_Recent_Posts_Widget' );
}
add_action( 'widgets_init', 'custom_load_widget' );
