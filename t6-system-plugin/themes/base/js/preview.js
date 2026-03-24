(function($){

    $(window).on('beforeunload', function () {
        // store last location and postion
        var info = {};
        info.url = location.href;
        if ($('.t6-content').scrollTop()) {
            info.pos = $('.t6-content').scrollTop();
            info.container = '.t6-content';
        } else {
            info.pos = $(window).scrollTop();
            info.container = 'window';
        }
        localStorage.setItem('last-preview', JSON.stringify(info));
    }).on('load', function () {
        try {
            var info = JSON.parse(localStorage.getItem('last-preview'));
            if (info.url == location.href && info.pos) {
                var $container = info.container == 'window' ? $(window) : $(info.container);
                $container.scrollTop(info.pos);
            }
        } catch (e) {
        }
    })

})(jQuery)
