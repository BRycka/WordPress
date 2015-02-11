<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 11/02/15
 * Time: 15:55
 */

function project_resources()
{
    wp_enqueue_style('style', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'project_resources');


// Navigation Menus
register_nav_menus(array(
    'primary' => __( 'Primary Menu' ),
    'footer' => __( 'Footer Menu' )
));
