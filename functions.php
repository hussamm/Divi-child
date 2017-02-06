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
          <div class="onlinesense-article-list-thumb">
            <a href="' . get_permalink($recent["ID"]) . '">'. get_the_post_thumbnail( $recent["ID"], 'thumbnail' ) . '</a>
          </div>
          <h4><a href="' . get_permalink($recent["ID"]) . '">' .   ( __($recent["post_title"])).'</a></h4>
          <p class="">' . $recent["post_excerpt"] . '</p>
        </li>';
    	}
    	wp_reset_query();

    return '<ul class="onlinesense-article-list">' . $output . '</ul>';
}









add_action( 'post_submitbox_misc_actions', 'choose_default_section' );

function choose_default_section()
{
    global $post;

    $value = get_post_meta($post->ID, 'default_section', true);
    echo '
        <div class="misc-pub-section misc-pub-section-last">
          <h4 style="margin-bottom: 3px;">Default Section: '. $default_section .'</h4>
          <ul style="margin: 0 10px">
            <li>
              <label for="default_section_primary">
                <input type="radio" name="default_section" ' . ($value == 'primary' ? 'checked' : '') . ' id="default_section_primary" value="primary" /><i style="color: #ddd">General</i>
              </label>
            </li>
            <li>
              <label for="default_section_teachers">
                <input type="radio" name="default_section" ' . ($value == 'teachers' ? 'checked' : '') . ' id="default_section_teachers" value="teachers" /> Teachers
              </label>
            </li>
            <li>
              <label for="default_section_parents">
                <input type="radio" name="default_section" ' . ($value == 'parents' ? 'checked' : '') . ' id="default_section_parents" value="parents" /> Parents
              </label>
            </li>
            <li>
              <label for="default_section_students">
                <input type="radio" name="default_section" ' . ($value == 'students' ? 'checked' : '') . ' id="default_section_students" value="students" /> Students
              </label>
            </li>
          </ul>
        </div>';
}

add_action( 'save_post', 'save_default_section');

function save_default_section($postid)
{
    /* check if this is an autosave */
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;

    /* check if the user can edit this page */
    if ( !current_user_can( 'edit_page', $postid ) ) return false;

    /* check if there's a post id and check if this is a post */
    /* make sure this is the same post type as above */
    if(empty($postid)) return false;

    /* if you are going to use text fields, then you should change the part below */
    /* use add_post_meta, update_post_meta and delete_post_meta, to control the stored value */

    /* check if the custom field is submitted (checkboxes that aren't marked, aren't submitted) */
    if($_POST['default_section']){
        /* store the value in the database */
        add_post_meta($postid, 'default_section', $_POST['default_section'], true );
    }
    else{
        /* not marked? delete the value in the database */
        // delete_post_meta($postid, 'default_section');
    }
}




?>
