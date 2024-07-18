/* ===================================================================
 * Main JS
 * ------------------------------------------------------------------- */
(function($) {

    "use strict";
    
    var cfg = {
        scrollDuration : 800,
    },
    $WIN = $(window);

    var doc = document.documentElement;
    doc.setAttribute('data-useragent', navigator.userAgent);

    // マウス　追従
    const stalker = document.getElementById('mouse-stalker'); 
    document.addEventListener('mousemove', function (e) {
        stalker.style.transform = 'translate(' + e.clientX + 'px, ' + e.clientY + 'px)';
    });

   /* OffCanvas Menu
    * ------------------------------------------------------ */
    var clOffCanvas = function() {

        var menuTrigger     = $('.header-menu-toggle'),
            nav             = $('.header-nav'),
            closeButton     = nav.find('.header-nav__close'),
            siteBody        = $('body'),
            mainContents    = $('section, footer');

        menuTrigger.on('click', function(e){
            e.preventDefault();
            siteBody.toggleClass('menu-is-open');
        });

        closeButton.on('click', function(e){
            e.preventDefault();
            menuTrigger.trigger('click');	
        });

        siteBody.on('click', function(e){
            if( !$(e.target).is('.header-nav, .header-nav__content, .header-menu-toggle, .header-menu-toggle span') ) {
                siteBody.removeClass('menu-is-open');
            }
        });

    };
    

   /* Stat Counter
    * ------------------------------------------------------ */
    var clStatCount = function() {
        
        var statSection = $(".about-stats"),
            stats = $(".stats__count");

        statSection.waypoint({

            handler: function(direction) {

                if (direction === "down") {

                    stats.each(function () {
                        var $this = $(this);

                        $({ Counter: 0 }).animate({ Counter: $this.text() }, {
                            duration: 4000,
                            easing: 'swing',
                            step: function (curValue) {
                                $this.text(Math.ceil(curValue));
                            }
                        });
                    });

                } 

                this.destroy();

            },

            offset: "90%"

        });
    };


   /* Masonry
    * ---------------------------------------------------- */ 
    var clMasonryFolio = function () {
        
        var containerBricks = $('.masonry');

        containerBricks.imagesLoaded(function () {
            containerBricks.masonry({
                itemSelector: '.masonry__brick',
                resize: true
            });
        });
    };

   /* Smooth Scrolling
    * ------------------------------------------------------ */
    var clSmoothScroll = function() {
        
        $('.smoothscroll').on('click', function (e) {
            var target = this.hash,
            $target    = $(target);
            
                e.preventDefault();
                e.stopPropagation();

            $('html, body').stop().animate({
                'scrollTop': $target.offset().top
            }, cfg.scrollDuration, 'swing').promise().done(function () {

                if ($('body').hasClass('menu-is-open')) {
                    $('.header-menu-toggle').trigger('click');
                }

                window.location.hash = target;
            });
        });

    };

   /* Animate On Scroll
    * ------------------------------------------------------ */
    var clAOS = function() {
        
        AOS.init( {
            offset: 200,
            duration: 600,
            easing: 'ease-in-sine',
            delay: 300,
            once: true,
            disable: 'mobile'
        });

    };

   /* トップに戻る
    * ------------------------------------------------------ */
    var clBackToTop = function() {

        var pxShow  = 250,
        pxHide  = 870,
        fadeInTime  = 400,
        fadeOutTime = 400,
        scrollSpeed = 300,
        goTopButton = $(".go-top");

        var winH = $(window).height() - 250;
        let Top = $("#contact").offset().top - winH;
        let Bottom = $("#contact").offset().top + $("#contact").innerHeight();
        
        $(window).on('scroll', function() {
            let scrollTop = $(window).scrollTop();

            Top = $("#contact").offset().top - winH;
            Bottom = $("#contact").offset().top + $("#works").innerHeight();

            if (scrollTop > pxHide) {
                goTopButton.fadeOut(fadeInTime);
            }else if (scrollTop >= pxShow) {
                goTopButton.fadeIn(fadeInTime);
            } else {
                goTopButton.fadeOut(fadeOutTime);
            }

            // 右スライドアニメーション
            // WORKS
            if (scrollTop > Top && scrollTop < Bottom) {
                $("#contact").find('.bgextend').addClass("bgLRextend");
            } else {
                $("#contact").find('.bgextend').removeClass("bgLRextend");
            }

        });

    };

   /* Initialize
    * ------------------------------------------------------ */
    (function ssInit() {
        
        // clPreloader();
        // clMenuOnScrolldown();
        clOffCanvas();
        // clPhotoswipe();
        clStatCount();
        clMasonryFolio();
        clSmoothScroll();
        // clAOS();
        clBackToTop();
        // clSlickSlider();

    })();

})(jQuery);