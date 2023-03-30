# MovieSpot WordPress Theme

## Overview

MovieSpot is a custom WordPress theme created by Filip Dimić for the purpose of showcasing his development skills. The theme version is 0.0.1 and includes a preview image selected by the author.

## Business Requirements

The MovieSpot theme includes a custom post type called Movies and three taxonomies:

* Release Year (non-hierarchical)
* Genres (non-hierarchical)
* Publishers (hierarchical)


All movies need to be part of these taxonomies, and each movie must include a custom field for rating (a float value between 0 and 5).

## Technical Requirements

The MovieSpot theme includes a custom template with filter functionality for logged-in users. The filters include:

* Genre
* Release Year
* Publisher

The results of these filters are sortable by rating from lowest to highest and vice versa. Filters work without page reload, and results are displayed on the page in real-time.

## Installation

To install the MovieSpot WordPress theme, follow these steps:

1. Clone or Download the theme files from [BitBucket](https://bitbucket.org/filipd815/moviespot/)
2. Extract the files to a new folder in the WordPress themes directory (wp-content/themes/)
3. Activate the MovieSpot theme in the WordPress admin panel under "Appearance > Themes"
4. Create movies in the WordPress admin panel under "Movies"
5. Assign Release Year, Genres, and Publishers taxonomies to each movie
6. Add a rating value (float between 0 and 5) to the Rating custom field for each movie
7. Use the custom template with filter functionality to display movies with the desired filters and sorting options.

## Alternative installation

[Download](https://bitbucket.org/filipd815/moviespotfiles/) the zipped project with database included, and import it in "Local By Flywheel". In the same directory you can find All-in-One WP Migration Export, so you can import dummy data for movies custom post type.

## Author Information

The author of the MovieSpot theme is Filip Dimić. Please feel free to contact the author with any questions about the functions and structure of the project.