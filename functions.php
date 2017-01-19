<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    if (is_rtl())
      wp_enqueue_style( 'parent-style-rtl', get_template_directory_uri() . '/rtl.css' );
}


add_shortcode( 'articles_list', 'articles_list' );

function articles_list( $atts ) {
    $a = shortcode_atts( array(
        'orderby' => 'latest',
        'categories' => '',
        'exclude-categories' => '',
        'max' => 5,
    ), $atts );

    // return latest articles
    $args = array(
    	'numberposts' => $a['max'],
    	'offset' => 0,
    	'category' => 0,
    	'orderby' => 'post_date',
    	'order' => $a['orderby'] == 'latest' ? 'DESC' : 'ASC',
    	'include' => '',
    	'exclude' => '',
    	'meta_key' => '',
    	'meta_value' =>'',
    	'post_type' => 'post',
    	'post_status' => 'publish',
    	'suppress_filters' => true
    );

    $recent_posts = wp_get_recent_posts( $args, ARRAY_A );
    /*
    echo '<pre>';
    var_dump($recent_posts[0]);
    echo '</pre>';
    /*/

    foreach( $recent_posts as $recent ){
    		$output .= '
        <li class="clearfix">
          <div class="onlinesense-article-list-thumb" style="float: left;">
            <a href="' . get_permalink($recent["ID"]) . '">'. get_the_post_thumbnail( $recent["ID"], 'thumbnail' ) . '</a>
          </div>
          <h4><a href="' . get_permalink($recent["ID"]) . '">' .   ( __($recent["post_title"])).'</a></h4>
          <p class="">' . $recent["post_excerpt"] . '</p>
        </li>';
    	}
    	wp_reset_query();

    return '<ul class="onlinesense-article-list">' . $output . '</ul>';
}
?>
