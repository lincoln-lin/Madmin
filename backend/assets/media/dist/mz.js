(function (exports) {

    function Mz() {
        // this.isPopup = location.href.indexOf('isPopup=1') !== -1;
        this.isPopup = !!window.opener;

        setTimeout(function () {
            $('.mz-message').fadeOut();
        }, 3000);

        $('body')
            .on('click', '.cmd-pagesize', function () {
                var pageSize = prompt('请输入每页条目数（不大于100）');
                if (pageSize > 0 && pageSize <= 100) {
                    var url = mz_CURRENT_URL.replace('_PAGESIZE_', pageSize).replace('_PAGE_', mz_CURRENT_URL_PAGE);
                    Utils.GotoUrl(url);
                }
            })
            .on('click', '.cmd-page-go', function () {
                onPageGo();
            })
            .on('keypress', '.input-page', function (e) {
                if (e.which == 13) { // 按下 Enter 键
                    onPageGo();
                }
            })
        ;

        function onPageGo()
        {
            var page = $('.input-page').val();
            if (page > 0) {
                var url = mz_CURRENT_URL.replace('_PAGE_', page).replace('_PAGESIZE_', mz_CURRENT_URL_PAGESIZE);
                Utils.GotoUrl(url);
            }
        }

        if (window.opener && window.opener.PopupSelect) {
            $('body').on('dblclick', '.grid-view tr', function () {
                var $tr = $(this)
                    ,key = $tr.data('key')
                if (key) {
                    window.opener.PopupSelect(key, window.location.href)
                    window.close()
                }
            })
        }
    }

    Mz.prototype.popup = function (elem) {
        var $elem = $(elem);
        var href = $elem.attr('href') || $elem.data('href') || location.href;
        Utils.OpenCenterWin(href);
    };

    Mz.prototype.debug = function () {
        if (this.isPopup) {
            alert(window.opener.location.href);
        }
    };

    Mz.prototype.post = function (url, data) {
        if (typeof url === 'object') {
            data = url;
            url = window.location.href;
        }
        var csrfParam = $('meta[name="csrf-param"]').attr("content");
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        data[csrfParam] = csrfToken;
        return $.post(url, data);
    };

    var mz = exports.mz = new Mz();
    // mz.debug();

})(window);
