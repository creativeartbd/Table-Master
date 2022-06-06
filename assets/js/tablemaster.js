(function ($) {

    $('.table-master ul li a').click(function (e) {
        e.preventDefault();
        var postId = $($(this).attr('href'));
        var postLocation = postId.offset().top;
    });

    $(window).scroll(function () {

        var scrollBar = $(this).scrollTop();
        var navigate = document.querySelector(".navigate").offsetTop

        $("section.navigate").each(function (index) {
            var elTop = $(this).offset().top;
            var elHeight = $(this).height();

            if (scrollBar >= elTop - navigate && scrollBar < elTop + elHeight) {
                $('.table-master ul li').eq(index).addClass('active').siblings().removeClass('active');
            }
        });

    });

})(jQuery);