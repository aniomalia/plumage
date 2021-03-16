// jQuery(document).ready(function () {

//     jQuery('.plumage-button').each(function () {
//         var $this = jQuery(this);
//         var postId = element.data('post-id');
//         $this.on('click', function (e) {
//             e.preventDefault();
//             e.stopPropagation();
//             alert('heey');
//             // $.ajax({
//             //     url: plumage_data.ajax_url,
//             //     type: 'POST',
//             //     data: {
//             //         action: 'plumage_user_action',
//             //         post_id: postId,
//             //     }
//             // });
//         });
//     });

// });


document.querySelectorAll(`button.plumage-button`).forEach(element => {
    let votes = parseInt(element.getAttribute('data-votes'));
    let postId = element.getAttribute('data-id');

    element.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        element.classList.add('is-loading');
        element.querySelector('.plumage-button-loading').classList.add('is-active');

        fetch(plumage_data.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache',
            },
            body: new URLSearchParams({
                action: 'plumage_user_action',
                post_id: postId,
                security: plumage_data.ajax_nonce
            })
        })
            .then(response => response.text())
            .then((text) => {
                output = JSON.parse(text);
                if (output.error.length > 0 && output.message.length > 0 ) {
                    alert(output.message);
                }
                if (output.error == 'login') {
                    location.href = plumage_data.site_url + '/login/';
                } else {
                    element.querySelector('.plumage-button-text').innerText = output.votes_count;
                    if (output.user_has_voted == 'false') {
                        element.classList.add('has-voted');
                    } else {
                        element.classList.remove('has-voted');
                    }
                }
                element.querySelector('.plumage-button-loading').classList.remove('is-active');
                element.classList.remove('is-loading');
            })
            .catch((error) => {
                element.querySelector('.plumage-button-loading').classList.remove('is-active');
                element.classList.remove('is-loading');
            });
    });
});