<?php get_header() ?>
    <div id="content">

        <div class="wrapper">

           <div class="banner-photo">

            <?php
            $banner_src = get_field('header_image');
            if(strlen($banner_src) == 0)
                $banner_src = get_field('practice_areas_header_image', 'options');
            if(strlen($banner_src) == 0)
                $banner_src = get_field('default_page_header_image', 'options');
            if(!empty($banner_src))
                echo '<img src="' . $banner_src . '" alt="" />';
            ?>

            </div><!-- .banner-photo -->

            <h1>
                <?php
                $icon_src = get_field('icon');
                if(!empty($icon_src))
                    echo '<img src="' . $icon_src . '" alt="' . get_the_title() . '" />';
                ?>
                
                <?php the_title(); ?>
            </h1>

            <?php
            $youtube_url = get_field('youtube_id');

            if(strlen($youtube_url) > 0 && strpos($youtube_url, 'http://') === false && strpos($youtube_url, 'https://') === false )
                $youtube_url = 'http://youtube.com/watch?v=' . $youtube_url;

            echo apply_filters('the_content', $youtube_url);
            ?>

            <?php the_field('description') ?>

            <?php
            
            ///////////////////////
            // SUB PRACTICE AREAS
            ///////////////////////

            $child_pas = new WP_Query(array(
                'post_type' => 'practice-area',
                'post_parent' => $post->ID
            ));

            if($child_pas->have_posts()){
                ?>
                <h2>Related Practice Areas</h2>
                <ul class="menu"><?php

                wp_list_pages( array(
                    'post_type' => 'practice-area',
                    'child_of' => $post->ID,
                    'title_li' => ''
                ) );


                echo '</ul>';

                wp_reset_query();

            }
                
            ?>
            <!-- Spaces removed for inline-block -->

            <?php
            
            ///////////////////////
            // FAQS
            ///////////////////////

            if( have_rows('faqs') ){

                echo '<h2>FAQs</h2>';
                echo '<ul class="faqs menu">';

                // loop through the rows of data
                while ( have_rows('faqs') ) : the_row();

                    echo '<li>';
                        echo '<h4>' . get_sub_field('question') . '</h4>';
                        echo get_sub_field('answer');
                    echo '</li>';

                endwhile;

                echo '<ul>';

            }

            ?>

            <?php
            
            ///////////////////////
            // CONSUMER GUIDES
            ///////////////////////

            if( have_rows('consumer_guides') ){

                echo '<h2>Consumer Guides</h2>';
                echo '<ul class="consumer-guides menu">';

                // loop through the rows of data
                while ( have_rows('consumer_guides') ) : the_row();

                    echo '<li><a href="' . get_sub_field('downloadable') . '">' . get_sub_field('title') . '</a></li>';

                endwhile;

                echo '<ul>';

            }

            ?>

            <?php

            ///////////////////////
            // RELATED ATTORNEYS
            ///////////////////////
            
            $related = new WP_Query( array(
              'connected_type' => 'attorney_practice-area',
              'connected_items' => get_queried_object(),
              'nopaging' => false,
            ) );

            if($related->have_posts()){
                ?>
                <h2><?php the_title() ?> Attorneys</h2>
                <ul class="menu">
                    <?php
                    while($related->have_posts()){
                        $related->the_post();
                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    }
                    ?>
                </ul>

                <?php
                wp_reset_query();
            }
            ?>

            <?php

            ///////////////////////
            // RELATED CASE RESULTS
            ///////////////////////
            
            $related = new WP_Query( array(
              'connected_type' => 'case-result_practice-area',
              'connected_items' => get_queried_object(),
              'nopaging' => false,
            ) );

            if($related->have_posts()){
                ?>
                <h2><?php the_title() ?> Case Results</h2>
                <ul class="menu">
                    <?php
                    while($related->have_posts()){
                        $related->the_post();
                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    }
                    ?>
                </ul>

                <?php
                wp_reset_query();
            }
            ?>

            <?php

            ///////////////////////
            // RELATED ARTICLES
            ///////////////////////
            
            $related = new WP_Query( array(
              'connected_type' => 'post_practice-area',
              'connected_items' => get_queried_object(),
              'nopaging' => false,
            ) );

            if($related->have_posts()){
                ?>
                <h2>Related Articles</h2>
                <ul class="menu">
                    <?php
                    while($related->have_posts()){
                        $related->the_post();
                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    }
                    ?>
                </ul>

                <?php
                wp_reset_query();
            }
            ?>

        </div><!-- .wrapper -->

    </div><!-- #content -->
<?php get_footer() ?>