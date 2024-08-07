<?php
get_header(); ?>

<div class="container">
    <h1><?php post_type_archive_title(); ?></h1>
    
    <form method="GET" action="">
        <div class="filters">
            <div class="filter">
                <label for="area_of_scholarship">Area of Scholarship:</label>
                <select name="area_of_scholarship" id="area_of_scholarship">
                    <option value="">All</option>
                    <?php
                    // Fetch unique values of 'area_of_scholarship' custom field
                    $areas = get_posts(array(
                        'post_type' => 'oe_scholar',
                        'posts_per_page' => -1,
                        'meta_key' => 'area_of_scholarship',
                        'fields' => 'ids',
                        'meta_query' => array(
                            array(
                                'key' => 'area_of_scholarship',
                                'compare' => 'EXISTS'
                            )
                        )
                    ));
                    $areas = array_unique(array_map(function($post_id) {
                        return get_post_meta($post_id, 'area_of_scholarship', true);
                    }, $areas));
                    foreach ($areas as $area) {
                        echo '<option value="' . esc_attr($area) . '">' . esc_html($area) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="filter">
                <label for="school_college">School/College:</label>
                <select name="school_college" id="school_college">
                    <option value="">All</option>
                    <?php
                    // Fetch unique values of 'school_college' custom field
                    $schools = get_posts(array(
                        'post_type' => 'oe_scholar',
                        'posts_per_page' => -1,
                        'meta_key' => 'school_college',
                        'fields' => 'ids',
                        'meta_query' => array(
                            array(
                                'key' => 'school_college',
                                'compare' => 'EXISTS'
                            )
                        )
                    ));
                    $schools = array_unique(array_map(function($post_id) {
                        return get_post_meta($post_id, 'school_college', true);
                    }, $schools));
                    foreach ($schools as $school) {
                        echo '<option value="' . esc_attr($school) . '">' . esc_html($school) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="filter">
                <button type="submit">Filter</button>
            </div>
        </div>
    </form>

    <?php
    $meta_query = array('relation' => 'AND');

    if (!empty($_GET['area_of_scholarship'])) {
        $meta_query[] = array(
            'key' => 'area_of_scholarship',
            'value' => sanitize_text_field($_GET['area_of_scholarship']),
            'compare' => 'LIKE'
        );
    }

    if (!empty($_GET['school_college'])) {
        $meta_query[] = array(
            'key' => 'school_college',
            'value' => sanitize_text_field($_GET['school_college']),
            'compare' => 'LIKE'
        );
    }

    $args = array(
        'post_type' => 'oe_scholar',
        'meta_query' => $meta_query,
        'posts_per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="scholars-archive">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="scholar-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="scholar-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="pagination">
            <?php echo paginate_links(array(
                'total' => $query->max_num_pages
            )); ?>
        </div>
    <?php else : ?>
        <p>No scholars found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
