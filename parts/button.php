<?php 
$post_id = get_the_ID();
$count = plumage_get_count($post_id);
?>
<span class="plumage">
    <button class="plumage-button<?php global $post; echo ( is_user_logged_in() && get_current_user_id() == $post->post_author ) ? ' user-is-author' : ''; ?><?php echo (get_current_user_id() && user_has_voted($post_id, get_current_user_id())) ? ' has-voted' : ''; ?>" data-id="<?php echo $post_id; ?>">
        <span class="plumage-button-loading"></span>
        <span class="plumage-button-icon">
            <span class="plumage-button-mark">
                <span></span>
                <span></span>
            </span>
        </span>
        <span class="plumage-button-text"><?php echo plumage_format_number($count); ?></span>
    </button>
</span>