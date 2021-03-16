<?php 
$post_id = get_the_ID();
$count = plumage_get_count($post_id);
$is_author = false;
global $post;
if ( is_user_logged_in() && is_array(get_field('resource_meta', $post_id)) && (get_current_user_id() == get_field('resource_meta', $post_id)['original_user']) ) {
    $is_author = true;
}
?>
<span class="plumage">
    <<?php echo ( is_user_logged_in() ) ? 'button' : 'a href="'.get_bloginfo('url').'/login/"' ?> class="plumage-button<?php echo $is_author ? ' user-is-author' : ''; ?><?php echo (get_current_user_id() && user_has_voted($post_id, get_current_user_id())) ? ' has-voted' : ''; ?>" data-id="<?php echo $post_id; ?>">
        <span class="plumage-button-loading"></span>
        <span class="plumage-button-icon">
            <span class="plumage-button-mark">
                <span></span>
                <span></span>
            </span>
        </span>
        <span class="plumage-button-text"><?php echo plumage_format_number($count); ?></span>
    </<?php echo (is_user_logged_in()) ? 'button' : 'a' ?>>
</span>