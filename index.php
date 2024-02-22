<?php get_header(); ?>

<main class="content">
    <section class="hero">
        <div class="container">
            <h1>Welcome to Our Site</h1>
            <p>Discover amazing products and services</p>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2>Our Featured Products</h2>
            <div class="product-grid">
                <?php
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'meta_query'     => array(
                        array(
                            'key'   => '_featured',
                            'value' => 'yes'
                        )
                    )
                );
                $featured_query = new WP_Query( $args );

                if ( $featured_query->have_posts() ) :
                    while ( $featured_query->have_posts() ) : $featured_query->the_post();
                        ?>
                        <div class="product">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail(); ?>
                                <h3><?php the_title(); ?></h3>
                            </a>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No featured products found</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <section class="newsletter">
        <div class="container">
            <h2>Subscribe to Our Newsletter</h2>
            <form action="#" method="post">
                <input type="email" name="email" placeholder="Your Email Address">
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </section>
</main>

<?php get_footer(); ?>
