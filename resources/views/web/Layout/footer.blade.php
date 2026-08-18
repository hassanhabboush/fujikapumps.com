<style>
    .footer-top {
    background-color: black;
    padding-top: 60px;
}
.copyright-area {
    background-color: #b7212e;
}
.cooli-item-area {
    background-color: var(--bg-color);
    margin-top: 3px;
}
.cooli-item-area .item-carousel .owl-nav {
    position: absolute;
    bottom: -11%;
    left: 51%;
}
.counterup-area {
    background: linear-gradient(rgba(57, 108, 240, 0.8), #f8f9fa) no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    text-align: center;
}
.about-us-area .about-image .hover .video-play-btn {
    position: absolute;
    left: 50%;
    margin-left: -40px;
    margin-top: -40px;
    top: 50%;
    width: 80px;
    height: 80px;
    background-color: rgb(183 33 46);
    border-radius: 50%;
    text-align: center;
    line-height: 88px;
}
.section-title .subtitle:before {
     border-top: solid 25px #b7212e;
   
}
.section-title .subtitle:after {
     border-top: solid 25px #b7212e;
   
}

    .footer {
        background: #0d0d0d;
        color: #a0a09a;
        font-family: var(--font-sans);
        padding: 48px 40px 0;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr;
        gap: 32px;
        padding-bottom: 36px;
        border-bottom: 0.5px solid rgba(255,255,255,0.1);
    }
    .brand-name {
        font-size: 20px;
        font-weight: 500;
        color: #f5f4ef;
        letter-spacing: -0.3px;
        margin-bottom: 10px;
    }
    .brand-desc {
        font-size: 13px;
        line-height: 1.7;
        color: #9a9a94;
        max-width: 260px;
        margin-bottom: 20px;
    }
    .contact-items { display: flex; flex-direction: column; gap: 8px; }
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: #9a9a94;
    }
    .contact-icon {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
        margin-top: 2px;
        opacity: 0.6;
    }
    .col-label {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #666660;
        margin-bottom: 16px;
    }
    .link-list { display: flex; flex-direction: column; gap: 11px; }
    .link-list a {
        font-size: 14px;
        color: #a8a8a2;
        text-decoration: none;
        transition: color 0.15s;
    }
    .link-list a:hover { color: #f5f4ef; }
    .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 0;
        margin: 0 -40px;          /* bleed to footer edges */
        padding-left: 40px;
        padding-right: 40px;
        background: #b7212e;
    }
    .copy {
        font-size: 12px;
        color: rgba(255,255,255,0.85);
    }
    .copy-dim {
        font-size: 12px;
        color: rgba(255,255,255,0.5);
    }
    .socials { display: flex; gap: 10px; flex-wrap: wrap; }
    .social-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 0.5px solid rgba(255,255,255,0.1);
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        text-decoration: none;
    }
    .social-btn:hover {
        border-color: rgba(255,255,255,0.25);
        background: rgba(255,255,255,0.05);
    }
    .social-btn svg {
        width: 14px;
        height: 14px;
        fill: #8a8a84;
        transition: fill 0.15s;
    }
    .social-btn:hover svg { fill: #c2c0b6; }
    .divider-line {
        width: 28px;
        height: 1px;
        background: rgba(183,33,46,0.7);
        margin-bottom: 18px;
    }
    .logo-mark {
        background: #1f1f1f;
        border: 0.5px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 10px 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .logo-mark img {
        /*height: 36px;*/
        width: auto;
        display: block;
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }
        .footer-grid > div:first-child {
            grid-column: 1 / -1;
        }
        .footer {
            padding: 36px 20px 0;
        }
        .footer-bottom {
            margin: 0 -20px;
            padding-left: 20px;
            padding-right: 20px;
        }
    }
</style>
<!-- footer area start -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <div class="brand-name">Fujika</div>
            <div class="divider-line"></div>
            <p class="brand-desc">Quality products and services. Reach out to us anytime — we're happy to help.</p>
            <div class="contact-items">
                <div class="contact-item">
                    <svg class="contact-icon" viewBox="0 0 16 16" fill="none"><path d="M8 1.5C5.51 1.5 3.5 3.51 3.5 6c0 3.75 4.5 8.5 4.5 8.5s4.5-4.75 4.5-8.5c0-2.49-2.01-4.5-4.5-4.5zm0 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" fill="currentColor"/></svg>
                    <span>{{$contact->address}}</span>
                </div>
                <div class="contact-item">
                    <svg class="contact-icon" viewBox="0 0 16 16" fill="none"><path d="M11.5 10.5c-.83 0-1.63-.13-2.37-.38a.75.75 0 00-.77.19l-1.46 1.46a9.23 9.23 0 01-4.17-4.17l1.46-1.46a.75.75 0 00.19-.77A7.46 7.46 0 014 3.5.75.75 0 003.25 2.75H1a.75.75 0 00-.75.75C.25 9.2 6.8 15.75 13.5 15.75A.75.75 0 0014.25 15v-2.25a.75.75 0 00-.75-.75h-2z" fill="currentColor"/></svg>
                    <span>{{$contact->phone1}}</span>
                </div>
                <div class="contact-item">
                    <svg class="contact-icon" viewBox="0 0 16 16" fill="none"><path d="M1.5 3.5h13a.5.5 0 01.5.5v8a.5.5 0 01-.5.5H1.5A.5.5 0 011 12V4a.5.5 0 01.5-.5zM1 4.5l7 4.5 7-4.5" stroke="currentColor" stroke-width="1" stroke-linecap="round"/></svg>
                    <span>{{$contact->email}}</span>
                </div>
            </div>
        </div>

        <div>
            <p class="col-label">Company</p>
            <nav class="link-list">
                <a href="{{url('/')}}">Home</a>
                <a href="{{url('/about')}}">About us</a>
                <a href="{{url('/contactus')}}">Contact us</a>
                <a href="#">Privacy policy</a>
                <a href="#">Terms & conditions</a>
            </nav>
        </div>

        <div>
            <p class="col-label">Follow us</p>
            <div class="socials">
                <a class="social-btn" href="{{$contact->Facebook}}" title="Facebook">
                    <svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                <a class="social-btn" href="{{$contact->Twitter}}" title="Twitter / X">
                    <svg viewBox="0 0 24 24"><path d="M4 4l6.5 8.5L4 20h2.5l5-5.5 4 5.5H20l-6.8-9L19.5 4H17l-4.5 5L8.5 4H4z"/></svg>
                </a>
                <a class="social-btn" href="{{$contact->whatsapp}}" title="WhatsApp">
                    <svg viewBox="0 0 24 24"><path d="M20.5 3.5A12 12 0 003.6 19.3L2 22l2.8-1.5A12 12 0 1020.5 3.5zm-8.5 18a10 10 0 01-5.1-1.4l-.3-.2-3 .8.8-2.9-.2-.3A10 10 0 1112 21.5zm5.5-7.4c-.3-.1-1.7-.8-2-.9-.2-.1-.4-.1-.6.1-.2.2-.7.9-.8 1.1-.2.2-.3.2-.6.1-.3-.2-1.2-.4-2.3-1.4-.8-.7-1.4-1.6-1.6-1.9-.1-.3 0-.5.1-.6l.4-.5.3-.4.1-.4-.9-2.1c-.2-.5-.5-.5-.6-.5h-.5c-.2 0-.5.1-.7.3-.2.2-.9.9-.9 2.1s.9 2.4 1 2.6c.1.1 1.7 2.7 4.2 3.7.6.2 1 .4 1.4.5.6.2 1.1.2 1.5.1.5-.1 1.5-.6 1.7-1.2.2-.5.2-1 .1-1.1-.1-.1-.3-.2-.6-.3z"/></svg>
                </a>
                <a class="social-btn" href="{{$contact->instagram}}" title="Instagram">
                    <svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" fill="#0d0d0d"/><circle cx="17.5" cy="6.5" r="1.5" fill="#0d0d0d"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke="#8a8a84" stroke-width="1.5" fill="none"/><circle cx="17.5" cy="6.5" r="1" fill="#8a8a84"/></svg>
                </a>
            </div>
            <div class="logo-block" style="margin-top: 28px;">
                <div class="logo-mark">
                    <a href="index.html" class="footer-logo"> <img src="{{ asset('assets/images/logogeek-1.png')}}" alt="footer logo" loading="lazy"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <span class="copy">&copy; 2026 Fujika. All rights reserved.</span>
        <span class="copy-dim">Site under construction</span>
    </div>
</footer>
<!-- footer area end -->



<!-- back to top area start -->
<div class="back-to-top">
    <span class="back-top"><i class="fa fa-angle-up"></i></span>
</div>
<!-- back to top area end -->

<!-- preloader area start -->
<!-- <div class="preloader" id="preloader">
    <div class="preloader-inner">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
</div> -->
<!-- preloader area end -->

    <!-- jquery -->
    <script defer src="{{ asset('assets/web/assets/js/jquery.min.js')}}"></script>
    <!-- popper -->
    <script defer src="{{ asset('assets/web/assets/js/popper.min.js')}}"></script>
    <!-- bootstrap -->
    <script defer src="{{ asset('assets/web/assets/js/bootstrap.min.js')}}"></script>
    <!-- magnific popup -->
    <script defer src="{{ asset('assets/web/assets/js/jquery.magnific-popup.js')}}"></script>
    <!-- wow -->
    <script defer src="{{ asset('assets/web/assets/js/wow.min.js')}}"></script>
    <!-- owl carousel -->
    <script defer src="{{ asset('assets/web/assets/js/owl.carousel.min.js')}}"></script>
    <!-- waypoint -->
    <script defer src="{{ asset('assets/web/assets/js/waypoints.min.js')}}"></script>
    <!-- counterup -->
    <script defer src="{{ asset('assets/web/assets/js/jquery.counterup.min.js')}}"></script>
    <!-- imageloaded -->
    <script defer src="{{ asset('assets/web/assets/js/imagesloaded.pkgd.min.js')}}"></script>
    <!-- isotope -->
    <script defer src="{{ asset('assets/web/assets/js/isotope.pkgd.min.js')}}"></script>
    <!-- slick slider -->
    <script defer src="{{ asset('assets/web/assets/js/slick.min.js')}}"></script>
    <!-- Slick Animation -->
    <script defer src="{{ asset('assets/web/assets/js/slick-animation.js')}}"></script>
     <!-- main js -->
    <script defer src="{{ versioned_asset('assets/web/assets/js/main2.js') }}"></script>
    <!-- lazy loading & blur-up -->
   <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('img[loading="lazy"]').forEach(function (img) {
        img.classList.add('lazy-img');
        if (img.complete && img.naturalWidth > 0) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function () {
                img.classList.add('loaded');
            });
            img.addEventListener('error', function () {
                img.classList.add('loaded');
            });
        }
    });

    if ('IntersectionObserver' in window) {
        var bgObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var el = entry.target;
                    var bg = el.dataset.bg;
                    if (bg) {
                        el.style.backgroundImage = 'linear-gradient(rgba(57, 108, 240, 0.8), #f8f9fa), url("' + bg + '")';
                        bgObserver.unobserve(el);
                    }
                }
            });
        }, { rootMargin: '200px 0px' });

        document.querySelectorAll('[data-bg]').forEach(function (el) {
            bgObserver.observe(el);
        });

        var imgObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var img = entry.target;
                var src = img.getAttribute('data-src');
                if (src) {
                    img.src = src;
                    img.removeAttribute('data-src');
                }
                imgObserver.unobserve(img);
            });
        }, { rootMargin: '80px 0px' });

        window.observeDeferImgs = function () {
            document.querySelectorAll('img.js-defer-img[data-src]').forEach(function (img) {
                imgObserver.observe(img);
            });
        };
        window.observeDeferImgs();
    } else {
        document.querySelectorAll('[data-bg]').forEach(function (el) {
            var bg = el.dataset.bg;
            if (bg) {
                el.style.backgroundImage = 'linear-gradient(rgba(57, 108, 240, 0.8), #f8f9fa), url("' + bg + '")';
            }
        });
        document.querySelectorAll('img.js-defer-img[data-src]').forEach(function (img) {
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
        });
    }
});
</script>
