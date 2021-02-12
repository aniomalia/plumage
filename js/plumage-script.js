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


document.querySelectorAll(`.plumage-button`).forEach(element => {
    let votes = parseInt(element.getAttribute('data-votes'));
    let postId = element.getAttribute('data-id');

    element.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        // element.classList.add('is-loading');
        // element.querySelector('.l-loading').classList.add('is-active');

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
                // output = JSON.parse(text);
                console.log(text);
                // console.table(output);

                // element.querySelector('.anivote-vote-text').innerText = output.votes_total;
                // element.querySelector('.l-loading').classList.remove('is-active');
                // if (output.has_voted) {
                //     element.classList.add('has-voted');
                // } else {
                //     element.classList.remove('has-voted');
                // }
                // element.classList.remove('is-loading');
            })
            .catch((error) => {
                console.log(error);

                // element.querySelector('.l-loading').classList.remove('is-active');
                // element.classList.remove('is-loading');
            });

            console.log('end');

    });
});