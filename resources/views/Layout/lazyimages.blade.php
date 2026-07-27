<!-- Admin thumbnails: native lazy loading + blur-up + broken-image fallback -->
<style>
    .lazy-img {
        opacity: 0;
        filter: blur(6px);
        background-color: #f1f3f5;
        transition: opacity .25s ease, filter .25s ease;
    }

    .lazy-img.loaded {
        opacity: 1;
        filter: none;
    }

    .lazy-img.lazy-error {
        background-color: transparent;
    }
</style>
<script>
(function () {
    "use strict";

    var PLACEHOLDER = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' fill='%23eceff1'/%3E%3Cpath d='M9 27l6.5-8 4 5 4.5-3.5L31 27z' fill='%23b0bec5'/%3E%3Ccircle cx='15' cy='14' r='3' fill='%23b0bec5'/%3E%3C/svg%3E";

    function isLazyImage(el) {
        return el && el.tagName === "IMG" && el.classList.contains("lazy-img");
    }

    // Kendo builds grid rows long after this file is parsed, so the handlers are
    // delegated on document. load/error don't bubble, but they do capture.
    document.addEventListener("load", function (e) {
        if (isLazyImage(e.target)) {
            e.target.classList.add("loaded");
        }
    }, true);

    document.addEventListener("error", function (e) {
        var img = e.target;

        if (!isLazyImage(img) || img.getAttribute("data-lazy-fallback")) {
            return; // the placeholder itself failed — don't loop
        }

        img.setAttribute("data-lazy-fallback", "1");
        img.src = PLACEHOLDER;
        img.classList.add("loaded", "lazy-error");
    }, true);

    // Images that finished (or came from cache) before the handlers attached.
    document.addEventListener("DOMContentLoaded", function () {
        var images = document.querySelectorAll("img.lazy-img");

        for (var i = 0; i < images.length; i++) {
            if (images[i].complete && images[i].naturalWidth > 0) {
                images[i].classList.add("loaded");
            }
        }
    });
})();
</script>
