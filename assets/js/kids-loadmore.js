jQuery(function ($) {

    let currentPage = 1;
    let loading = false;

    $(document).on('click', '.kids-loadmore-btn', function (e) {
        e.preventDefault();

        if (loading) return;
        loading = true;

        const $btn = $(this);
        $btn.text('Загрузка...').addClass('is-loading');

        const season   = $btn.data('season') || '';
        const category = $btn.data('category') || '';
        const age      = $btn.data('age') || '';

        $.ajax({
            url: kids_ajax.ajaxurl,
            type: 'POST',
            data: {
                action: 'kids_loadmore',
                page: currentPage,
                season: season,
                category: category,
                age: age
            },
            success: function (response) {
                if (response && response.trim() !== '') {
                    $('#kids-list').append(response);
                    currentPage++;
                    $btn.text('Загрузить ещё').removeClass('is-loading');
                } else {
                    $btn.remove();
                }
                loading = false;
            },
            error: function () {
                $btn.text('Загрузить ещё').removeClass('is-loading');
                loading = false;
            }
        });
    });

});
