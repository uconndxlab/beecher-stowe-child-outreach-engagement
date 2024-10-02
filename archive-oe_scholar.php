<?php
get_header();
// enqueue archive-oe_scholar.css
wp_enqueue_style('archive-oe_scholar', get_stylesheet_directory_uri() . '/archive-scholar.css');
?>

<div class="scholars-directory">
    <h1>Community Engaged Scholar Directory</h1>
    <div id="overview">
        <!-- load the content of the page with the slug 'scholar-directory-overview' -->
        <?php
            $page = get_page_by_path('scholar-directory-overview');
            if ($page) {
                echo apply_filters('the_content', $page->post_content);
            }
        ?>
    </div>

    <div id="filter-section">
        <form method="GET" action="">
            <div class="filters">
                <div class="filter">
                    <label for="area_of_scholarship">Area of Scholarship:</label>
                    <select name="area_of_scholarship" id="area_of_scholarship">
                        <option value="">All</option>
                        <?php
                        // Fetch unique values of 'areas_of_scholarship' custom field
                        $areas = get_posts(array(
                            'post_type' => 'oe_scholar',
                            'posts_per_page' => -1,
                            
                            'meta_key' => 'areas_of_scholarship',
                            'fields' => 'ids',
                            'meta_query' => array(
                                array(
                                    'key' => 'areas_of_scholarship',
                                    'compare' => 'EXISTS'
                                )
                            )
                        ));
                        $areas = array_unique(array_reduce($areas, function ($carry, $post_id) {
                            $values = get_post_meta($post_id, 'areas_of_scholarship', true);
                            if (is_array($values)) {
                                return array_merge($carry, $values);
                            }
                            return $carry;
                        }, []));

                        //order the areas alphabetically
                        sort($areas);
                        foreach ($areas as $area) {
                            $selected = (isset($_GET['area_of_scholarship']) && $_GET['area_of_scholarship'] === $area) ? 'selected' : '';
                            echo '<option value="' . esc_attr($area) . '" ' . $selected . '>' . esc_html($area) . '</option>';
                        }
                        ?>
                    </select>
                    <!-- export current results to csv -->

                </div>
                <div class="filter">
                    <label for="school_college">School/College:</label>
                    <select name="school_college" id="school_college">
                        <option value="">All</option>
                        <?php
                        // Fetch unique values of 'schoolcollege' custom field
                        $schools = get_posts(array(
                            'post_type' => 'oe_scholar',
                            'posts_per_page' => -1,
                            'meta_key' => 'schoolcollege',
                            'fields' => 'ids',
                            'meta_query' => array(
                                array(
                                    'key' => 'schoolcollege',
                                    'compare' => 'EXISTS'
                                )
                            )
                        ));
                        $schools = array_unique(array_map(function ($post_id) {
                            return get_post_meta($post_id, 'schoolcollege', true);
                        }, $schools));

                        //order the schools alphabetically
                        sort($schools);

                        foreach ($schools as $school) {
                            // Get the user input value and encode the ampersand
                            $user_input = isset($_GET['school_college']) ? $_GET['school_college'] : '';

                            // if the %26 is in the $_GET['school_college'] value, replace it with the encoded value
                            $encoded_input = str_replace('&', 'and', $user_input);
                            if ($encoded_input == "College of Liberal Arts and Sciences") {
                                $user_input = "College of Liberal Arts &amp; Sciences";
                            }
                            
                        
                        
                            // Check if either the raw or encoded values match
                            $selected = ($user_input === $school || $encoded_input === $school) ? 'selected' : '';
                        
                            echo '<option value="' . esc_attr($school) . '" ' . $selected . '>' . esc_html($school) . '</option>';
                        }
                        
                        ?>
                    </select>
                </div>
                <div class="filter">
                    <button type="submit">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php

$meta_query = array('relation' => 'AND');

if (!empty($_GET['area_of_scholarship'])) {
    $meta_query[] = array(
        'key' => 'areas_of_scholarship',
        'value' => sanitize_text_field($_GET['area_of_scholarship']),
        'compare' => 'LIKE'
    );
}

if (!empty($_GET['school_college'])) {
    


    $school_college_value = $_GET['school_college'];

    // Encode the ampersand to its HTML entity or URL-encoded equivalent
    $encoded_value = str_replace('&', '&amp;', $school_college_value);
    
    $meta_query[] = array(
        'key' => 'schoolcollege',
        'value' => $encoded_value,
        'compare' => 'LIKE'
    );
}

$args = array(
    'post_type' => 'oe_scholar',
    'meta_query' => $meta_query,
    'posts_per_page' => 12,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    'orderby' => 'meta_value',
    'meta_key' => 'last_name',
    'order' => 'ASC'
);

$query = new WP_Query($args);

if ($query->have_posts()) : ?>
    <div class="scholars-archive">
        <?php while ($query->have_posts()) : $query->the_post();
            $job_title = get_field('job_title');
            $school_college = get_field('schoolcollege');
            $email = get_field('email');
            $areas_of_scholarship = get_field('areas_of_scholarship');

        ?>
            <div class="person">
                <div class="person-image-div">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php else : ?>
                        <img src="https://placehold.it/150x150&text=No%20Image" alt="No Image">
                    <?php endif; ?>
                </div>

                <div class="person-body">
                    <h3 class="person-title navy-color"><?php the_title(); ?></h3>
                    <p class="person-job-title navy-color"><?php echo $job_title; ?></p>
                    <p class="person-school-college navy-color"><?php echo $school_college; ?></p>
                    <div class="person-areas-of-scholarship navy-color">
                        <?php foreach ($areas_of_scholarship as $area) : ?>
                           <a href="<?php echo get_post_type_archive_link('oe_scholar') . '?area_of_scholarship=' . urlencode($area); ?>">
                                <span class="area"><?php echo $area; ?></span>
                            </a> 

                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="email-link">
                    <a href="mailto:<?php echo get_post_meta(get_the_ID(), 'email', true); ?>" class="email-link navy-color">
                        <?php echo $email; ?>
                    </a>
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