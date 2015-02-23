<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 20/02/15
 * Time: 11:27
 */

if (!defined('WP_UNISTALL_PLUGIN')) {
    exit();
}

global $wpdb;
$table_names = array(
    'questions' => $wpdb->prefix."mypoll_questions",
    'answers' => $wpdb->prefix."mypoll_answers",
    'votes' => $wpdb->prefix."mypoll_votes",
    'voters' => $wpdb->prefix."mypoll_voters"
);

foreach ($table_names as $name) {
    $wpdb->query("DROP TABLE $name");
}
