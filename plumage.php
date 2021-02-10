<?php
/**
 * Plugin Name: Plumage
 * Plugin URI: https://aniomalia.com/plugins/plumage/
 * Author: Aniomalia
 * Author URI: https://aniomalia.com/
 * Description: Community voting of posts
 * Version: 0.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('WPINC')) {
    die;
}

define('PLUMAGE_VERSION', '1.0.0');

function plumage_enqueue_files() {
    wp_enqueue_script('plumage-script', plugin_dir_url( __FILE__ ) . 'js/plumage-script.js', array('jquery'), '', true);
    wp_localize_script('plumage-script', 'plumage_data',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
        )
    );
    wp_enqueue_style('plumage-style', plugin_dir_url( __FILE__ ) . 'css/plumage-style.css' );
}
add_action('wp_enqueue_scripts', 'plumage_enqueue_files');

add_action('init', 'plumage_register_db_table', 1);
add_action('plugins_loaded', 'plumage_register_db_table');

function plumage_register_db_table() {
    global $wpdb;
    $wpdb->plumage_votes = "{$wpdb->prefix}plumage_votes";
}

add_action('plugins_loaded', function () {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$wpdb->plumage_votes} (
        vote_id bigint(20) unsigned NOT NULL auto_increment,
        post_id bigint(20) unsigned NOT NULL,
        user_id bigint(20) unsigned NOT NULL,
        vote_time datetime NOT NULL,
        vote_position bigint(20) NOT NULL,
        PRIMARY KEY  (vote_id),
        KEY post_id (post_id),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});

function plumage_format_number($number, $max = 4) {
    $input = (int)$number;

    if ($input >= 1000) {
        if ($input < 1000000) {
            return intval($input / 1000) . 'k';
        } elseif ($input < 1000000000) {
            return intval($input / 1000000) . 'm';
        } else {
            return intval($input / 1000000000) . 'b';
        }
    }
    return $input;
}

function plumage_get_count($post_id) {
    global $wpdb;
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->plumage_votes WHERE post_id = $post_id");

    return $count;
}

function user_has_voted($post_id, $user_id) {
    if (!is_user_logged_in()) {
        return false;
    }
    if (!isset($user_id) && is_user_logged_in()) {
        $user_id = get_current_user_id();
    }
    global $wpdb;
    $table      = $wpdb->plumage_votes;
    $user_voted = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE post_id = $post_id AND user_id = $user_id");
    return ($user_voted) ? true : false;
}

function plumage_callback() {

    // Ensure we have the data we need to continue
    if (!isset($_POST) || empty($_POST) || !is_user_logged_in()) {
        header('HTTP/1.1 400 Empty POST Values');
        $output = array(
            'error' => 'An error occured',
        );

        echo json_encode($output);
        exit;
    }

    global $wpdb;

    $user_id     = get_current_user_id();
    $resource_id = $_POST['resource_id'];

    $votes_count = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE post_id = $resource_id");
    $user_voted  = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE post_id = $resource_id AND user_id = $user_id");

    if (!user_has_voted($resource_id, $user_id)) {
        $data = array(
            'post_id'       => $resource_id,
            'user_id'       => $user_id,
            'vote_time'     => date('c'),
            'vote_position' => $votes_count,
        );
        $wpdb->insert($table, $data);
    }

    $output = array(
        'resource_id' => (int)$resource_id,
        'date_voted'  => date('c'),
        'votes_total' => $votes,
        'user_voted'  => $user_id,
        'has_voted'   => ($already_voted) ? false : true,
    );

    echo json_encode($output);

    exit;
}
add_action('wp_ajax_nopriv_plumage_vote', 'plumage_callback');
add_action('wp_ajax_plumage_vote', 'plumage_callback');

function plumage_part($slug) {
    ob_start();
    include( 'parts/' . $slug . '.php' );
    $output = ob_get_clean();
    return $output;
}

function plumage_button() {
    echo plumage_part('button');
}