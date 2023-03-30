<?php

// Register the movie custom post type
function create_movie_post_type()
{
    $args = array(
        'labels' => array(
            'name' => 'Movies',
            'singular_name' => 'Movie',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Movie',
            'edit_item' => 'Edit Movie',
            'new_item' => 'New Movie',
            'view_item' => 'View Movie',
            'search_items' => 'Search Movies',
            'not_found' => 'No Movies found',
            'not_found_in_trash' => 'No Movies found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Movies'
        ),
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies' => array('category', 'post_tag'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'movies')
    );
    register_post_type('movie', $args);
}

add_action('init', 'create_movie_post_type');

// Register the release year custom taxonomy
function create_release_year_taxonomy()
{
    register_taxonomy(
        'release_year',
        'movie',
        array(
            'label' => 'Release Year',
            'rewrite' => array('slug' => 'release-year'),
            'hierarchical' => false,
        )
    );
}

add_action('init', 'create_release_year_taxonomy');

// Register the genres custom taxonomy
function create_genres_taxonomy()
{
    register_taxonomy(
        'genres',
        'movie',
        array(
            'label' => 'Genres',
            'rewrite' => array('slug' => 'genres'),
            'hierarchical' => false,
        )
    );
}

add_action('init', 'create_genres_taxonomy');

// Register the publishers custom taxonomy
function create_publishers_taxonomy()
{
    register_taxonomy(
        'publishers',
        'movie',
        array(
            'label' => 'Publishers',
            'rewrite' => array('slug' => 'publishers'),
            'hierarchical' => true,
        )
    );
}

add_action('init', 'create_publishers_taxonomy');

// Add the rating meta box to the movie post type
function add_rating_meta_box() {
    add_meta_box(
        'movie_rating',
        'Rating',
        'movie_rating_callback',
        'movie',
        'normal',
        'default'
    );
}

add_action('add_meta_boxes', 'add_rating_meta_box');

// Display the rating meta box on the movie post editor page
function movie_rating_callback($post) {
    wp_nonce_field(basename(__FILE__), 'movie_rating_nonce');
    $movie_rating = get_post_meta($post->ID, 'movie_rating', true);
    ?>
    <label for="movie_rating_field">Rating (0 to 5)</label>
    <input type="number" step="0.1" min="0" max="5" id="movie_rating_field" name="movie_rating_field"
           value="<?php echo esc_attr($movie_rating); ?>"/>
    <?php
}

// Save the rating meta box data when the movie post is
function save_rating_meta_box($post_id) {
    if (!isset($_POST['movie_rating_nonce']) || !wp_verify_nonce($_POST['movie_rating_nonce'], basename(__FILE__))) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (!isset($_POST['movie_rating_field'])) {
        return;
    }
    $movie_rating = sanitize_text_field($_POST['movie_rating_field']);
    update_post_meta($post_id, 'movie_rating', $movie_rating);
}

add_action('save_post', 'save_rating_meta_box');
