; (function ($) {
    "use strict";

    $(document).ready(function () {

        /**-----------------------------
         *  Navbar fix
         * ---------------------------*/
        $(document).on('click', '.navbar-area .navbar-nav li.menu-item-has-children>a', function (e) {
        })


        /*----------------------FloadF
            Slider active
        -----------------------*/
        $('.header-slider-area').slick({
            infinite: true,
            dots: false,
            arrows: true,
            autoplay: false,
            autoplaySpeed: 3000,
            fade: true,
            fadeSpeed: 1000,
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: '<a class="slick-prev"><img src="https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-left-01-512.png" alt="Arrow Icon"></a>',
            nextArrow: '<a class="slick-next"><img src="https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-right-01-512.png" alt="Arrow Icon"></a>',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        }).on('afterChange', function () {
            new WOW().init();
        });


        /*----------------------
           Slider active
       -----------------------*/
        $('.header-slider-two').slick({
            infinite: true,
            dots: true,
            arrows: false,
            autoplay: false,
            autoplaySpeed: 3000,
            fade: true,
            fadeSpeed: 1000,
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        }).on('afterChange', function () {
            new WOW().init();
        });


        /*---------------------------
           Team carousel
        ---------------------------*/
        var $itemCarousel = $('.item-carousel');
        if ($itemCarousel.length > 0) {
            $itemCarousel.owlCarousel({
                loop: true,
                autoPlayTimeout: 5000,
                margin: 30,
                autoplay: false,
                dots: false,
                nav: true,
                smartSpeed: 3000,
                responsiveClass: true,
                navText: ['<span class="flaticon-left-chevron"></span>', '<span class="flaticon-right"></span>'],
                responsive: {
                    0: {
                        items: 2,
                        nav: true
                    },
                    411: {
                        items: 2,
                        nav: true
                    },
                    451: {
                        items: 2,
                        nav: true,
                    },
                    768: {
                        items: 2
                    },
                    960: {
                        items: 2
                    },
                    992: {
                        items: 4
                    },
                    1600: {
                        items: 4
                    },
                    1920: {
                        items: 4
                    }
                }
            });
        }



        /*---------------------------------
           project Masonry activation
       ----------------------------------*/
        var projectContainer = $('#project-masonry');
        if (projectContainer.length > 0) {
            projectContainer.imagesLoaded(function () {
                var latestWorkMasonry = $('#project-masonry').isotope({
                    itemSelector: '.grid-size',
                    percentPosition: true,
                    gutter: 0,
                    masonry: {
                        columnWidth: 0
                    }
                });
                $(document).on('click', '.our-project-menu ul li', function () {
                    var filterValue = $(this).attr('data-filter');
                    latestWorkMasonry.isotope({
                        filter: filterValue
                    });
                });
            });
        }

        /*---------------------------------
                  Testimonial activation
              ----------------------------------*/
        $(document).ready(function ($) {

            var feedbackSlider = $('.feedback-slider');
            feedbackSlider.owlCarousel({
                items: 1,
                nav: true,
                dots: true,
                autoplay: true,
                loop: false,
                mouseDrag: true,
                touchDrag: true,
                navText: ["<i class='fa fa-long-arrow-left'></i>", "<i class='fa fa-long-arrow-right'></i>"],
                responsive: {

                    // breakpoint from 767 up
                    767: {
                        nav: true,
                        dots: false
                    }
                }
            });

            feedbackSlider.on("translate.owl.carousel", function () {
                $(".feedback-slider-item h3").removeClass("animated fadeIn").css("opacity", "0");
                $(".feedback-slider-item img, .feedback-slider-thumb img, .feedback-social").removeClass("animated zoomIn").css("opacity", "0");
            });

            feedbackSlider.on("translated.owl.carousel", function () {
                $(".feedback-slider-item h3").addClass("animated fadeIn").css("opacity", "1");
                $(".feedback-slider-item img, .feedback-slider-thumb img, .feedback-social").addClass("animated zoomIn").css("opacity", "1");
            });
            feedbackSlider.on('changed.owl.carousel', function (property) {
                var current = property.item.index;
                var prevThumb = $(property.target).find(".owl-item").eq(current).prev().find("img").attr('src');
                var nextThumb = $(property.target).find(".owl-item").eq(current).next().find("img").attr('src');
                var prevRating = $(property.target).find(".owl-item").eq(current).prev().find('span').attr('data-rating');
                var nextRating = $(property.target).find(".owl-item").eq(current).next().find('span').attr('data-rating');
                $('.thumb-prev').find('img').attr('src', prevThumb);
                $('.thumb-next').find('img').attr('src', nextThumb);
            });
            $('.thumb-next').on('click', function () {
                feedbackSlider.trigger('next.owl.carousel', [300]);
                return false;
            });
            $('.thumb-prev').on('click', function () {
                feedbackSlider.trigger('prev.owl.carousel', [300]);
                return false;
            });

        }); //end ready


        /*--------------------
           wow js init
       ---------------------*/
        new WOW().init();

        /*-------------------------
            magnific popup activation
        -------------------------*/

        $('.video-play-btn,.video-popup,.video-play-btn2').magnificPopup({
            // disableOn: 700,
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false,
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com',
                        id: 'v=',
                        src: 'https://www.youtube.com/embed/hKOnuXGLssI'
                    },
                },
                srcAction: 'iframe_src',
            },
            fixedContentPos: false
        });


        /*------------------
           back to top
       ------------------*/
        $(document).on('click', '.back-to-top', function () {
            $("html,body").animate({
                scrollTop: 0
            }, 2000);
        });
        /*------------------------------
           counter section activation
       -------------------------------*/
        var counternumber = $('.counter');
        counternumber.counterUp({
            delay: 20,
            time: 3000
        });



        /*----------------------
            Search Popup
        -----------------------*/
        var bodyOvrelay = $('#body-overlay');
        var searchPopup = $('#search-popup');
        var quoteForm = $('#quoteForm');

        $(document).on('click', '#body-overlay', function (e) {
            e.preventDefault();
            bodyOvrelay.removeClass('active');
            searchPopup.removeClass('active');
            quoteForm.removeClass('active');
        });
        $(document).on('click', '#search', function (e) {
            e.preventDefault();
            searchPopup.addClass('active');
            bodyOvrelay.addClass('active');
        });

        $(document).on('click', '#quote', function (e) {
            e.preventDefault();
            quoteForm.addClass('active');
            bodyOvrelay.addClass('active');
            quoteForm.display('block');

        });


    });


    //define variable for store last scrolltop
    var lastScrollTop = '';

    $(window).on('scroll', function () {

        //back to top show/hide
        var ScrollTop = $('.back-to-top');
        if ($(window).scrollTop() > 1000) {
            ScrollTop.fadeIn(1000);
        } else {
            ScrollTop.fadeOut(1000);
        }

        /*--------------------------
         sticky menu activation
        -------------------------*/
        var st = $(this).scrollTop();
        var mainMenuTop = $('.navbar-area');
        if ($(window).scrollTop() > 1000) {
            if (st > lastScrollTop) {
                // hide sticky menu on scrolldown 
                mainMenuTop.removeClass('nav-fixed');

            } else {
                // active sticky menu on scrollup 
                mainMenuTop.addClass('nav-fixed');
            }

        } else {
            mainMenuTop.removeClass('nav-fixed ');
        }

        lastScrollTop = st;

    });

    // window.addEventListener("load", function alertFunc() {

    //         setTimeout(()=>{
    //         const loader = document.querySelector("#preloader");
    //         loader.className += "hidden"; //class="loader hidden"
    //         }, 1000)
    //     });
    $(window).on('load', function () {


        /*-----------------
            back to top
        ------------------*/
        var backtoTop = $('.back-to-top')
        backtoTop.fadeOut();

        /*---------------------
            Cancel Preloader
        ----------------------*/

        /*-------------------------
            magnificPopup  activation
        -------------------------*/
        // For Gallery Filtering
        $('.imagepopup').magnificPopup({ 'image': true });

        $('.popup-link').magnificPopup({
            removalDelay: 300,
            type: 'image',
            callbacks: {
                beforeOpen: function () {
                    this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure animated ' + this.st.el.attr('data-effect'));
                }
            },
        });


        // Toggle Collapse
        var acordionBtn = $('.accordion .question');

        if (acordionBtn.length) {
            acordionBtn.on('click', function () {
                if (!$(this).parent().hasClass('active')) {
                    $(this).parent().addClass('active');
                    $(this).parent().siblings().removeClass('active');
                    $(this).find('.plus-minus-toggle').toggleClass('collapsed');
                } else {
                    $(this).parent().removeClass('active');
                    $(this).find('.plus-minus-toggle').toggleClass('collapsed');
                }
            });
        }



        // quote form animations
        $('#quote').click(function () {
            $('#quoteForm').fadeToggle();
        })
        $(document).mouseup(function (e) {
            var container = $("#quoteForm");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut();
            }
        });


    });


})(jQuery);