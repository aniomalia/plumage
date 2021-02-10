(function ($) {
    $(document).ready(function () {

        $('.plumage-button').each(function () {
            var $this = $(this);
            var postId = element.data('post-id');
            $this.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                alert('heey');
                // $.ajax({
                //     url: plumage_data.ajax_url,
                //     type: 'POST',
                //     data: {
                //         action: 'plumage_user_action',
                //         post_id: postId,
                //     }
                // });
            });
        });

    });
})(jQuery);