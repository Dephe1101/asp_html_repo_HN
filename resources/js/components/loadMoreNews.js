const $specialLoadMoreBtn = $('#btn-load-more-news');

function appendNewCard({ title = '', slug = '', image = '' }) {
    let currentDomain = window.location.href;
    return `<div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card">
            <a href="${currentDomain}${slug}" class="card-text" title="${title}">
                <img src="${image}" class="card-img-top" alt="${title}">
            </a>
            <div class="card-body">
                <a href="${currentDomain}${slug}" class="card-text" title="${title}"><strong>${title}</strong></a>
            </div>
        </div>
    </div>`;
}

$specialLoadMoreBtn.on('click', function (e) {
    const loadMoreState = $specialLoadMoreBtn.data('state');
    // List sim target need append data
    const appendTarget = $specialLoadMoreBtn.closest('.load-more-news').siblings('.news-list');

    if (loadMoreState == 'loading') {
        return;
    }

    const loadMoreButtonText = $specialLoadMoreBtn.html();
    const currentPage = $specialLoadMoreBtn.data('page') || 1;
    const urlParams = new URLSearchParams(window.location.search);
    const params = { 'page': currentPage + 1 };

    urlParams.forEach((value, key) => {
        params[key] = value;
    });
    console.log('import.meta.env.: ', import.meta.env.VITE_APP_URL);
    $.ajax({
        type: "GET",
        url: `${import.meta.env.VITE_APP_URL}/api/new-load-more`,
        data: params,
        beforeSend: function () {
            setLoadingButton();
        },
        success: function (res) {
            resetLoadingButton();

            if (!res.status) {
                console.log(res);
                return;
            }
            !!res.data?.data && res.data.data.forEach(item => {
                let html = appendNewCard({
                    title: item.title,
                    slug: item.slug,
                    image: item.image,
                })
                appendTarget.append(html);
            });

            $specialLoadMoreBtn.data('page', res.data.current_page);
            (res.data?.current_page == res.data?.last_page) &&
                $specialLoadMoreBtn.hide();
        },
        error: function (err) {
            resetLoadingButton();
            console.log(err);
        }
    });

    function setLoadingButton() {
        $specialLoadMoreBtn.html('Đang tải...');
        $specialLoadMoreBtn.data('state', 'loading');
    }

    function resetLoadingButton() {
        $specialLoadMoreBtn.html(loadMoreButtonText);
        $specialLoadMoreBtn.data('state', 'done');
    }
    e.preventDefault()
});
