<?php 
$post_id = get_the_ID();
$count = plumage_get_count($post_id);
?>
<span class="plumage">
    <button class="plumage-button" data-id="<?php echo $post_id; ?>">
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