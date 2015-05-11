;(function($){
    var $overlay = $(".primary-section .panel-overlay"),
        $starter = $("#starter-button"),
        $section1 = $("#primary-section"),
        $section2 = $("#secondary-section"),
        $upButton = $("#secondary-section .button-heavy"),
        $info = $("#information");

    $(window).on('mousewheel',
        function(e) {
            e.preventDefault();
            e.stopPropagation();
        }
    );

    $starter.click(function(){
        scrollDown();
    });

    $upButton.click(function(){
        scrollUp();
    });

    function scrollDown() {
        $('html, body').animate({
            scrollTop: $section2.offset().top
        }, 1000, "linear", function(){
            window.setTimeout(showInfo(), 1000);
        });
    }

    function scrollUp() {
        $('html, body').animate({
                scrollTop: $section1.offset().top
            },
            800,
            'linear',
            function(){
                resetPage();
            }
        );

    }

    function showInfo() {
        $info.animate({'opacity': '1', 'top': '0'}, 2000, function(){
            $upButton.animate({'opacity': '1'}, 500);
        });
    }

    function resetPage() {
        $info.css({'opacity': '0', 'top': '5em'});
        $upButton.css({'opacity': '0'});
        $('html, body').animate({
            scrollTop: $section1.offset().top
        });
    }

    $(window).load(function(){
        resetPage();
    });
})(jQuery);
