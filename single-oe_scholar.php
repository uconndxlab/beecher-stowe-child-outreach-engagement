<?php
get_header(); ?>

<div class="container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="scholar-single">
            <h1><?php the_title(); ?></h1>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="scholar-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            <div class="scholar-meta">
                <?php
                // Assuming you have ACF fields, you can display them here
                if ( function_exists('the_field') ) {
                    // first_name, last_name, email, job_title, areas_of_scholarship (array), campus, schoolcollege, and featured_image are ACF fields
                    $first_name = get_field('first_name');
                    $last_name = get_field('last_name');
                    $email = get_field('email');
                    $job_title = get_field('job_title');
                    $areas_of_scholarship = get_field('areas_of_scholarship');
                    $campus = get_field('campus');
                    $schoolcollege = get_field('schoolcollege');

                    if ( $first_name ) {
                        echo '<p><strong>First Name:</strong> ' . $first_name . '</p>';
                    }

                    if ( $last_name ) {
                        echo '<p><strong>Last Name:</strong> ' . $last_name . '</p>';
                    }

                    if ( $email ) {
                        echo '<p><strong>Email:</strong> ' . $email . '</p>';
                    }

                    if ( $job_title ) {
                        echo '<p><strong>Job Title:</strong> ' . $job_title . '</p>';
                    }

                    if ( $areas_of_scholarship ) {
                        echo '<p><strong>Areas of Scholarship:</strong> ' . implode(', ', $areas_of_scholarship) . '</p>';
                    }

                    if ( $campus ) {
                        echo '<p><strong>Campus:</strong> ' . $campus . '</p>';
                    }

                    if ( $schoolcollege ) {
                        echo '<p><strong>School/College:</strong> ' . $schoolcollege . '</p>';
                    }

                    // Display the wordpress featured thumbnail
                    if ( has_post_thumbnail() ) {
                        echo '<p><strong>Featured Image:</strong> ';
                        the_post_thumbnail('thumbnail');
                        echo '</p>';
                    }


                }
                ?>
            </div>
        </div>
    <?php endwhile; else : ?>
        <p>No scholar found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
