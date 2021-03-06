<?php get_header() ?>
    <div id="content">

        <div class="wrapper">

            <div class="bio-card">

                <div class="details">

                    <h1><?php the_title() ?> <em><?php the_field('title') ?></em></h1>

                    <h5><a href="tel:<?php the_field('phone') ?>"><?php the_field('phone') ?></a></h5>

                    <nav class="connect-nav">

                        <ul class="menu">
                            <li class="cn-email"><a href="mailto:<?php the_field('email') ?>">Email</a></li>

                            <?php
                            $vcard = get_field('vcard');
                            if(!empty($vcard)){
                                // strip out hostname and such
                                $vcard = str_replace(get_bloginfo('url') . '/wp-content/uploads/', '', $vcard);
                                echo '<li class="cn-vcard"><a href="' . get_bloginfo('template_directory') . '/functions/site/force-download.php?file=' . $vcard . '">Download vCard</a></li>';
                            }
                            
                            if(strlen(get_field('facebook_url')) > 0)
                                echo '<li class="cn-facebook"><a href="' . get_field('facebook_url') . '">Facebook</a></li>';

                            if(strlen(get_field('twitter_url')) > 0)
                                echo '<li class="cn-twitter"><a href="' . get_field('twitter_url') . '">Twitter</a></li>';

                            echo '<li class="cn-youtube"><a href="' . get_field('youtube_url', 'options') . '">YouTube</a></li>';

                            if(strlen(get_field('linkedin_url')) > 0)
                                echo '<li class="cn-linkedin"><a href="' . get_field('linkedin_url') . '">LinkedIn</a></li>';

                            ?>
                        </ul>

                    </nav>

                    <p><?php the_field('short_intro_bio') ?></p>

                </div><!-- .details -->

                <div class="photo">

                    <img src="<?php the_field('photos') ?>" alt="<?php the_title() ?>" />

                </div>

            </div><!-- .bio-card -->

            <div class="primary-content">

                <?php

                // loop through the rows of data
                while ( have_rows('bio_sections') ) : the_row();

                    echo '<h3>' . get_sub_field('section_title') . '</h3>';
                    the_sub_field('content');

                endwhile;

                ?>

                <?php
                $related = new WP_Query( array(
                  'connected_type' => 'post_attorney',
                  'connected_items' => get_queried_object(),
                  'nopaging' => true,
                ) );

                if($related->have_posts()){
                    ?>

                    <h3>Articles</h3>
                    <ul>
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

                if( have_rows('videos') ){

                    echo '<script src="' . get_bloginfo('template_directory') . '/js/vendor/jquery.fancybox.pack.js"></script>';
                    echo '<script src="' . get_bloginfo('template_directory') . '/js/vendor/jquery.fancybox-media.js"></script>';

                    echo '<h3>Videos</h3>';
                    echo '<ul class="vids">';

                    // loop through the rows of data
                    while ( have_rows('videos') ) : the_row();

                        echo '<li><a rel="gallery1" href="http://youtube.com/watch?v=' . get_sub_field('youtube_id') . '">' . get_sub_field('video_title') . '</a>';

                    endwhile;

                    echo '<ul>';
                    ?>
                    <script>
                        $('.vids a').fancybox({
                            openEffect  : 'none',
                            closeEffect : 'none',
                            helpers : {
                                media : {}
                            }
                        });
                    </script>
                    <?php

                }

                ?>

            </div><!-- .primary-content -->

            <div class="secondary-content">

                <div class="accordion-tabs">

                    <h4 class="accordion-toggle open">Practice Areas</h4>

                    <div class="accordion-content open">

                        <?php
                        $related = new WP_Query( array(
                          'connected_type' => 'attorney_practice-area',
                          'connected_items' => get_queried_object(),
                          'nopaging' => true,
                        ) );

                        if($related->have_posts()){
                            
                            // get order by id
                            $ordered = array();
                            while($related->have_posts()){
                                $related->the_post();

                                $ord_meta = p2p_get_meta( get_post()->p2p_id, 'order', true );
                                
                                if(!isset($ordered[$ord_meta]))
                                    $ordered[intval($ord_meta)] = array();

                                $ordered[$ord_meta][] =  $post;
                            }
                            ksort($ordered);


                            ?>

                            <ul class="menu">
                                <?php
                                foreach($ordered as $posts){
                                    foreach($posts as $post){
                                        echo '<li><a href="' . get_permalink($post_id) . '">' . $post->post_title . '</a></li>';
                                    }
                                }
                                ?>
                            </ul>

                            <?php
                            wp_reset_query();
                        }
                        ?>

                    </div>

                    <?php

                    // loop through the rows of data
                    while ( have_rows('sidebar_sections') ) : the_row();

                        echo '<h4 class="accordion-toggle">' . get_sub_field('section_title') . '</h4>';
                        echo '<div class="accordion-content">';
                            the_sub_field('content');
                        echo '</div>';

                    endwhile;

                    ?>

                    <h4 class="accordion-toggle">Newsletter Signup</h4>
                    <div class="accordion-content">
                        <?php gravity_form( 'Newsletter Signup', false, false, false, null, true, 1); ?>
                    </div>


                </div><!-- .accordian-tabs -->

                <?php the_field('sidebar_wysiwyg'); ?>

                <ul class="menu">
                    <?php
                    if(strlen(get_field('downloadable_bio')) > 0)
                        echo '<li><a href="' . get_field('downloadable_bio') . '">Download Attorney Bio</a></li>';
                    ?>
                </ul>

            </div><!-- .secondary-content -->

        </div><!-- .wrapper -->

    </div><!-- #content -->
<?php get_footer() ?>