<?php
/**
 * Archive template for Movies custom post type.
 */
get_header(); ?>

<main>
    <?php if (is_user_logged_in()) : ?>
        <div class="movie-filters">
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <?php
                $genres = get_terms('genres', array(
                    'orderby' => 'name',
                    'hide_empty' => true,
                ));

                if ($genres && !is_wp_error($genres)) :
                    ?>
                    <div class="movie-filter-genre">
                        <label for="movie_genre">
                            Filter by Genre
                        </label>

                        <select name="movie_genre" id="movie_genre">
                            <option value="">All Genres</option>
                            <?php foreach ($genres as $genre) : ?>
                                <option value="<?php echo esc_attr($genre->slug); ?>"<?php if (isset($_GET['movie_genre']) && $_GET['movie_genre'] == $genre->slug) {
                                    echo ' selected="selected"';
                                } ?>><?php echo esc_html($genre->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php
                $release_years = get_terms('release_year', array(
                    'orderby' => 'name',
                    'hide_empty' => true,
                ));
                if ($release_years && !is_wp_error($release_years)) :
                    ?>
                    <div class="movie-filter-release-year">
                        <label for="movie_release_year">Filter by Release Year</label>
                        <select name="movie_release_year" id="movie_release_year">
                            <option value="">All Release Years</option>
                            <?php foreach ($release_years as $release_year) : ?>
                                <option value="<?php echo esc_attr($release_year->slug); ?>"<?php if (isset($_GET['movie_release_year']) && $_GET['movie_release_year'] == $release_year->slug) {
                                    echo ' selected="selected"';
                                } ?>><?php echo esc_html($release_year->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php
                $publishers = get_terms('publishers', array(
                    'orderby' => 'name',
                    'hide_empty' => true,
                ));
                if ($publishers && !is_wp_error($publishers)) :
                    ?>
                    <div class="movie-filter-publisher">
                        <label for="movie_publisher">Filter by Publisher</label>
                        <select name="movie_publisher" id="movie_publisher">
                            <option value="">All Publishers</option>
                            <?php foreach ($publishers as $publisher) : ?>
                                <option value="<?php echo esc_attr($publisher->slug); ?>"<?php if (isset($_GET['movie_publisher']) && $_GET['movie_publisher'] == $publisher->slug) {
                                    echo ' selected="selected"';
                                } ?>><?php echo esc_html($publisher->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="movie-filter-sort">
                    <label for="movie_sort">Sort by Rating</label>
                    <select name="movie_sort" id="movie_sort">
                        <option value="asc"<?php if (isset($_GET['movie_sort']) && $_GET['movie_sort'] == 'asc') {
                            echo ' selected="selected"';
                        } ?>>
                            Ascending
                        </option>
                        <option value="desc"<?php if (isset($_GET['movie_sort']) && $_GET['movie_sort'] == 'desc') {
                            echo ' selected="selected"';
                        } ?>>
                            Descending
                        </option>
                    </select>
                </div>

                <button type="submit">Filter</button>
            </form>
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <input type="hidden" name="movie_genre" value="">
                <input type="hidden" name="movie_release_year" value="">
                <input type="hidden" name="movie_publisher" value="">
                <input type="hidden" name="movie_sort" value="">
                <button type="submit">Reset Filters</button>
            </form>
        </div>

        <?php
        // Build the query args based on the filter options selected.
        $query_args = array(
            'post_type' => 'movie',
            'posts_per_page' => 100,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            'tax_query' => array(),
        );

        // Filter by genre.
        if (isset($_GET['movie_genre']) && $_GET['movie_genre']) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'genres',
                'field' => 'slug',
                'terms' => $_GET['movie_genre'],
            );
        }

        // Filter by release year.
        if (isset($_GET['movie_release_year']) && $_GET['movie_release_year']) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'release_year',
                'field' => 'slug',
                'terms' => $_GET['movie_release_year'],
            );
        }

        // Filter by publisher.
        if (isset($_GET['movie_publisher']) && $_GET['movie_publisher']) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'publishers',
                'field' => 'slug',
                'terms' => $_GET['movie_publisher'],
            );
        }

        // Sort by rating.
        if (isset($_GET['movie_sort']) && $_GET['movie_sort']) {
            $query_args['meta_key'] = 'movie_rating';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = $_GET['movie_sort'] == 'asc' ? 'ASC' : 'DESC';
        }

        // Get the movies based on the query args.
        $movies = new WP_Query($query_args);

        if ($movies->have_posts()) :?>
            <div class="movie-list">
                <?php while ($movies->have_posts()) : $movies->the_post(); ?>
                    <?php $movie_genres = get_the_terms(get_the_ID(), 'genres'); ?>
                    <?php $movie_release_years = get_the_terms(get_the_ID(), 'release_year'); ?>
                    <?php $movie_publishers = get_the_terms(get_the_ID(), 'publishers'); ?>

                    <article class="movie">
                        <h2>
                            <?php the_title(); ?>
                        </h2>
                        <div class="movie-meta">
                            <?php if (get_post_meta(get_the_ID(), 'movie_rating', true)) : ?>
                                <div class="movie-rating">
                                    <span class="movie-rating-label">
                                        Rating:
                                    </span>
                                    <?php echo esc_html(get_post_meta(get_the_ID(), 'movie_rating', true)); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($movie_genres) : ?>
                                <div class="movie-genre">
                                    <span class="movie-genre-label">
                                        Genre:
                                    </span>
                                    <?php
                                    $genre_list = array();
                                    foreach ($movie_genres as $genre) :
                                        $genre_list[] = esc_html($genre->name);
                                    endforeach;
                                    echo implode(', ', $genre_list);
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($movie_release_years) : ?>
                                <div class="movie-release-year">
                                    <span class="movie-release-year-label">Release Year:</span>
                                    <?php
                                    $release_year_list = array();
                                    foreach ($movie_release_years as $release_year) :
                                        $release_year_list[] = esc_html($release_year->name);
                                    endforeach;
                                    echo implode(', ', $release_year_list);
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($movie_publishers) : ?>
                                <div class="movie-publisher">
                                    <span class="movie-publisher-label">
                                        Publisher:
                                    </span>
                                    <?php
                                    $publisher_list = array();
                                    foreach ($movie_publishers as $publisher) :
                                        $publisher_list[] = esc_html($publisher->name);
                                    endforeach;
                                    echo implode(', ', $publisher_list);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="movie-excerpt"><?php the_excerpt(); ?></div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php the_posts_pagination(); ?>
    <?php endif; ?>
</main>

